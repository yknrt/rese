@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('main')
    <div class="shop-detail">
        <div class="detail-content">
            <div class="detail-header">
                <button type="button" onClick="history.back()">&lt;</button>
                <h1 class="name">{{ $shop->name }}</h1>
            </div>
            <div class="detail-img">
                <img src="{{ $shop->img }}" alt="shop" />
            </div>
            <div class="detail-tag">
                <p class="detail-tag-item">#{{ $shop->area->area }}</p>
                <p class="detail-tag-item">#{{ $shop->genre->genre }}</p>
            </div>
            <div class="detail-comment">{{ $shop->summary}}</div>
        </div>
        <div class="reserve">
            <form action="/reservation" method="post">
                @csrf
                <div class="reserve-content">
                    <div class="reserve-ttl">
                        <h1>予約</h1>
                    </div>
                    <input type="hidden" name="shop_id" value={{ $shop->id }}>
                    <div class="reserve-date">
                        <input type="date" name="date" id="inputDate" min="" value="{{ old('date') }}">
                    </div>
                    <div class="form__error">
                        @error('date')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="reserve-select">
                        <select name="time" id="inputTime">
                            <option disabled selected>時:分</option>
                            @foreach(range(mktime(10,0,0,0,0,0),mktime(23,30,0,0,0,0),60*30) as $val)
                            @php $time = date('H:i',$val) @endphp
                            <option value={{ $time }} {{ old('time')==$time ? 'selected' : '' }}>{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__error">
                        @error('time')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form__error">
                        @error('datetime')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="reserve-select">
                        <select name="number" id="inputNumber">
                            <option disabled selected>人数</option>
                            @for ($n =1; $n <=20; $n++)
                            <option value={{ $n }} {{ old('number')==$n ? 'selected' : '' }}>{{ $n }}人</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form__error">
                        @error('number')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="output-table">
                        <table class="output-table__inner">
                            <tr class="output-table__row">
                                <th class="output-table__header">shop</th>
                                <td class="output-table__text">{{ $shop->name }}</td>
                            </tr>
                            <tr class="output-table__row">
                                <th class="output-table__header">Date</th>
                                <td class="output-table__text" id="displayDate"></td>
                            </tr>
                            <tr class="output-table__row">
                                <th class="output-table__header">Time</th>
                                <td class="output-table__text" id="displayTime"></td>
                            </tr>
                            <tr class="output-table__row">
                                <th class="output-table__header">Number</th>
                                <td class="output-table__text" id="displayNumber"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="reserve-btn">
                        <button type="submit">予約する</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
    // 要素を取得
    const inputDateField = document.getElementById('inputDate');
    const displayDateField = document.getElementById('displayDate');
    const inputTimeField = document.getElementById('inputTime');
    const displayTimeField = document.getElementById('displayTime');
    const inputNumberField = document.getElementById('inputNumber');
    const displayNumberField = document.getElementById('displayNumber');

    // 今日の日付を取得してmin属性に設定
    const today = new Date().toISOString().split('T')[0];
    inputDateField.setAttribute('min', today);

    // 入力時に値を更新
    inputDateField.addEventListener('input', function() {
        displayDateField.textContent = inputDateField.value;
    });
    inputTimeField.addEventListener('input', function() {
        displayTimeField.textContent = inputTimeField.value;
    });
    inputNumberField.addEventListener('input', function() {
        displayNumberField.textContent = inputNumberField.value + '人';
    });
</script>
@endsection