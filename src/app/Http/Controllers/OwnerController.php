<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\Reservation;
use App\Http\Requests\ShopRequest;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $areas = Area::all();
        $genres = Genre::all();
        $myShop = Owner::find($id)->shop;
        $now = Carbon::now();
        if (!empty($myShop)) {
            $reservations = Reservation::where('shop_id', $myShop->id);
            $records = $reservations->get()->filter(function ($item) use ($now) {
                return strtotime($item->date . ' ' . $item->time) >= strtotime($now);
            });
            $records = $records->sortBy([['date', true], ['time', true]]);
        } else {
            $records = [];
        }
        return view('owner/owner', compact('areas', 'genres', 'records', 'myShop'));
    }

    public function store(ShopRequest $request)
    {
        $owner_id = Auth::id();
        if ($request->area=='other') {
            Area::create(['area' => $request->newArea]);
            $area = Area::where('area', $request->newArea)->first();
            $area_id = $area->id;
        } else {
            $area_id = $request->area;
        }

        if ($request->genre=='other') {
            Genre::create(['genre' => $request->newGenre]);
            $genre = Genre::where('genre', $request->newGenre)->first();
            $genre_id = $genre->id;
        } else {
            $genre_id = $request->genre;
        }

        $path = $request->file('image')->store('public/images');
        $img = Storage::url($path);

        Shop::create([
            'owner_id' => $owner_id,
            'name' => $request->name,
            'area_id' => $area_id,
            'genre_id' => $genre_id,
            'summary' => $request->summary,
            'img' => $img
        ]);
        return redirect()->route('owner.home')->with('message', '店舗情報を登録しました。');
    }

    public function update(ShopRequest $request)
    {
        $id = Auth::id();
        $myShop = Owner::find($id)->shop;

        $judge = 'false';
        if ($request->name != $myShop->name) {
            $myShop->name = $request->name;
            $judge = 'true';
        }
        if ($request->area=='other') {
            Area::create(['area' => $request->newArea]);
            $area = Area::where('area', $request->newArea)->first();
            $myShop->area = $area->id;
            $judge = 'true';
        }

        if ($request->genre=='other') {
            Genre::create(['genre' => $request->newGenre]);
            $genre = Genre::where('genre', $request->newGenre)->first();
            $myShop->genre = $genre->id;
            $judge = 'true';
        }

        $path = $request->file('image');
        if (isset($path)) {
            // 画像削除
            $replace = str_replace('/storage', 'public', $myShop->img);
            Storage::delete($replace);

            $path = $path->store('public/images');
            $myShop->img = Storage::url($path);
            $judge = 'true';
        }

        if ($request->summary != $myShop->summary) {
            $myShop->summary = $request->summary;
            $judge = 'true';
        }

        if ($judge == 'true') {
            $myShop->save();
            return redirect()->route('owner.home')->with('message', '店舗情報を変更しました。');
        }
        return redirect()->route('owner.home');

    }
}
