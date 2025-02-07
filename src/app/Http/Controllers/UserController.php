<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Review;
use App\Http\Requests\ReservationRequest;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class UserController extends Controller
{
    //マイページ
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorite;
        $favoriteShopIds = Auth::user()->favorite()->pluck('shop_id')->toArray();

        $now = Carbon::now();
        $noVisited = $user->reservation->where('is_visited', 0);
        $records = $noVisited->filter(function ($item) use ($now) {
            return strtotime($item->date . ' ' . $item->time) <= strtotime($now);
        });
        foreach ($records as $record) {
            $record->is_visited = true;
            $record->save();
        }
        $reservations = $user->reservation->where('is_reviewed', 0)->sortBy([['date', true], ['time', true]]);

        return view('mypage', compact('user', 'favorites', 'reservations', 'favoriteShopIds'));
    }

    //店舗予約
    public function store(ReservationRequest $request)
    {
        $user = Auth::id();
        $shop = $request->shop_id;
        $reservation = Reservation::create([
            'user_id' => $user,
            'shop_id' => $shop,
            'date' => $request->date,
            'time' => $request->time,
            'number' => $request->number
        ]);

        // QRコードを生成
        $writer = new PngWriter();
        $qrCode = new QrCode('reservation id : '. $reservation->id);
        $qrCode->setSize(400);
        $qrCode->setMargin(10);
        $result = $writer->write($qrCode);

        // 一時的なファイルとして作成
        $tempPath = tempnam(sys_get_temp_dir(), 'qrCode');
        $result->saveToFile($tempPath);
        // QRコード画像をストレージに保存
        $fileName = 'public/code/qr_' . $reservation->id . '.png';
        Storage::put($fileName, file_get_contents($tempPath));
        // 一時ファイルを削除
        unlink($tempPath);

        return view('done');
    }

    //店舗予約変更
    public function update(ReservationRequest $request)
    {
        $reservation = Reservation::find($request->id);
        $newData = $request->only(['date', 'time', 'number']);
        $judge = 'false';
        if ($request->number != $reservation->number) {
            $judge = 'true';
        }
        $time = Carbon::parse($reservation->time)->format('H:i');
        if ($request->time != $time) {
            $judge = 'true';
        }
        $date = Carbon::parse($reservation->date)->format('H:i');
        if ($request['date'] != $reservation['date']) {
            $judge = 'true';
        }
        if ($judge == 'true') {
            // データが変更されている場合
            $reservation->update($newData);
        }
        return redirect()->back()->with('message', '予約を変更しました。');
    }

    //店舗予約削除
    public function delete(Request $request)
    {
        Reservation::find($request->id)->delete();
        return redirect()->back()->with('message', '予約を削除しました。');
    }

    // お気に入り
    public function storeFavorite(Request $request)
    {
        $user = Auth::user();
        $favorite = Favorite::all();
        // 既にお気に入り登録済みかチェック
        if ($user->favorite()->where('shop_id', $request->shop_id)->exists()) {
            // 削除
            Favorite::where('user_id', $user->id)->where('shop_id', $request->shop_id)->delete();
        } else {
            // 登録
            $form = [
                'user_id' => $user->id,
                'shop_id' => $request->shop_id,
            ];
            Favorite::create($form);
        }
        return redirect()->back();
    }

    public function review(Request $request)
    {
        Review::create([
            'reservation_id' => $request->id,
            'score' => $request->score,
            'comment' => $request->comment
        ]);
        $reservation = Reservation::find($request->id);
        $reservation->is_reviewed = true;
        $reservation->save();
        return redirect()->back()->with('message', '評価レビューを送信しました。');
    }
}
