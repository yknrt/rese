@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="search">
    <form action="/search" method="get">
        <select name="area" class="search-select" value="{{ request('area') }}">
            <option value="">All area</option>
            @foreach($areas as $area)
            <option value={{ $area->id }} {{ request('area')==$area->id ? 'selected' : '' }}>{{ $area->area }}</option>
            @endforeach
        </select>
        <select name="genre" class="search-select" value="{{ request('genre') }}">
            <option value="">All genre</option>
            @foreach($genres as $genre)
            <option value={{ $genre->id }} {{ request('genre')==$genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
            @endforeach
        </select>
        <div class="search-text">
            <button class="search-btn" type="submit">
                <img src="{{ asset('images/search.svg') }}" class="icon">
            </button>
            <input type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Search ...">
        </div>
    </form>
</div>
@endsection

@section('main')
    <div class="shops">
        <div class="shops-content">
            @foreach($shops as $shop)
            <div class="card">
                <div class="card__img">
                    <img src="{{ asset($shop->img) }}" alt="shop" />
                </div>
                <div class="card__content">
                    <div class="card__content-ttl">{{ $shop->name }}</div>
                    <div class="card__content-tag">
                        <p class="card__content-tag-item">#{{ $shop->area->area }}</p>
                        <p class="card__content-tag-item">#{{ $shop->genre->genre }}</p>
                    </div>
                    <div class="card__content-detail">
                        <a href="{{ route('shop.detail', $shop->id) }}">詳しくみる</a>
                        <form action="/favorite" method="post">
                        @csrf
                            <input type="hidden" name="shop_id" value={{ $shop->id }}>
                            <button class="favorite-btn" type="submit">
                                @if (in_array($shop->id, $favoriteShopIds))
                                <img src="{{ asset('images/heart_on.svg') }}">
                                @else
                                <img src="{{ asset('images/heart_off.svg') }}">
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection