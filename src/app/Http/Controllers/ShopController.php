<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Http\Requests\ReservationRequest;

use App\Models\User;

class ShopController extends Controller
{
    // 一覧ページ
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::all();
        $user = Auth::user();
        // ログイン中のユーザーのお気に入り店舗IDを取得
        if (!empty($user)) {
            $favoriteShopIds = $user->favorite()->pluck('shop_id')->toArray();
        } else {
            $favoriteShopIds = [];
        }
        return view('index', compact('areas', 'genres', 'shops', 'favoriteShopIds'));
    }

    //検索機能
    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/')->withInput();
        }

        $query = Shop::query();
        if (!empty($request->area)) {
            $query->where('area_id', '=', $request->area);
        }
        if (!empty($request->genre)) {
            $query->where('genre_id', '=', $request->genre);
        }
        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
            ->orWhere('summary', 'like', '%' . $request->keyword . '%');
        }
        $shops = $query->get();

        if (!empty(Auth::user())) {
            $favoriteShopIds = Auth::user()->favorite()->pluck('shop_id')->toArray();
        } else {
            $favoriteShopIds = [];
        }
        $areas = Area::all();
        $genres = Genre::all();
        return view('index', compact('areas', 'genres', 'shops', 'favoriteShopIds'));
    }

    // 詳細ページ
    public function detail($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop_detail', compact('shop'));
    }
}