@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
    <div class="username">{{ $user->name }}さん</div>
    <div class="mypage">
        <div class="reservation-content">
            <div class="reservation-content-heading">
                <div class="title_text">予約状況</div>
            </div>
            @foreach($reservations as $reservation)
            <div class="form_group">
                <div class="form__header">
                    <div class="reservation-number">
                        <img src="{{ asset('images/clock.svg') }}" alt="clock icon" class="icon">
                        <div class="number">予約{{ $loop->iteration }}</div>
                    </div>
                    @if( $reservation->is_visited == 1 )
                    <div class="visited-text">済</div>
                    @else
                    <div class="btn">
                        <!-- 編集ボタン -->
                        <button class="edit-btn" id="edit{{ $reservation->id }}">
                            <img src="{{ asset('images/edit.svg') }}">
                        </button>
                        <!-- 削除ボタン -->
                        <form action="/delete" method="POST">
                            @csrf
                            <input type="hidden" name="id" value={{ $reservation->id }}>
                            <button class="cancel-btn" type="submit" id="delete{{ $reservation->id }}">
                                <img src="{{ asset('images/cancel.svg') }}">
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="reservation-table"  data-editing="false">
                    <table class="reservation-table__inner">
                        <tr class="reservation-table__row">
                            <th class="reservation-table__header">shop</th>
                            <td class="reservation-table__text">{{ $reservation->shop->name }}</td>
                        </tr>
                        <tr class="reservation-table__row">
                            <th class="reservation-table__header">Date</th>
                            <td class="reservation-table__text">{{ $reservation->date }}</td>
                        </tr>
                        <tr class="reservation-table__row">
                            <th class="reservation-table__header">Time</th>
                            <td class="reservation-table__text">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                        </tr>
                        <tr class="reservation-table__row">
                            <th class="reservation-table__header">Number</th>
                            <td class="reservation-table__text">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                </div>
                <div class="edit-form" style="display: none;">
                    <form action="/mypage/edit" method="POST">
                        @csrf
                        <input type="hidden" name="id" value={{ $reservation->id }}>
                        <div class="edit--date">
                            <input type="date" name="date" min="" value="{{ old('date') }}">
                        </div>
                        <div class="form__error">
                            @error('date')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="edit--select">
                            <select name="time">
                                <option disabled selected>時:分</option>
                                @foreach(range(mktime(10,0,0,0,0,0),mktime(23,30,0,0,0,0),60*30) as $val)
                                @php $time = date('H:i',$val) @endphp
                                <option value={{ $time }} {{ old('time')==$time ? 'selected' : '' }}>{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form_error">
                            @error('time')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form_error">
                            @error('datetime')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="edit--select">
                            <select name="number">
                                <option disabled selected>人数</option>
                                @for ($n =1; $n <=20; $n++)
                                <option value={{ $n }} {{ old('number')==$n ? 'selected' : '' }}>{{ $n }}人</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form_error">
                            @error('number')
                            {{ $message }}
                            @enderror
                        </div>
                        <button class="save-btn" id="save{{ $reservation->id }}">
                            <input type="hidden" name="edit" value="edit">
                            <img src="{{ asset('images/save.svg') }}">
                        </button>
                    </form>
                </div>
                @if( $reservation->is_visited == 1)
                <div class="review-form">
                    <form action="/reservation/review" method="POST">
                        @csrf
                        <div class="review--score">
                            @for ($i =1; $i <=5; $i++)
                            <input type="radio" id="scoreChoice{{ $i }}" name="score" value={{ $i }} {{ old('score') === $i ? 'checked' : '' }} checked />
                            <label for="scoreChoice{{ $i }}">{{ $i }}</label>
                            @endfor
                        </div>
                        <div class="review--comment">
                            <textarea name="comment" rows="3" placeholder="レビューを書く">{{ old('comment') }}</textarea>
                        </div>
                        <input type="hidden" name="id" value={{ $reservation->id }}>
                        <button class="review--btn">投稿</button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="favorite">
            <div class="favorite-heading">
                <div class="title_text">お気に入り店舗</div>
            </div>
            <div class="favorite-content">
                @foreach($favorites as $favorite)
                <div class="card">
                    <div class="card__img">
                        <img src="{{ $favorite->shop->img }}" alt="shop" />
                    </div>
                    <div class="card__content">
                        <div class="card__content-ttl">{{ $favorite->shop->name }}</div>
                        <div class="card__content-tag">
                            <p class="card__content-tag-item">#{{ $favorite->shop->area->area }}</p>
                            <p class="card__content-tag-item">#{{ $favorite->shop->genre->genre }}</p>
                        </div>
                        <div class="card__content-detail">
                            <a href="{{ route('shop.detail', $favorite->shop_id) }}">詳しくみる</a>
                            <form action="/favorite" method="post">
                            @csrf
                                <input type="hidden" name="shop_id" value={{ $favorite->shop_id }}>
                                <button class="favorite-btn" type="submit">
                                    @if (in_array($favorite->shop_id, $favoriteShopIds))
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
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection