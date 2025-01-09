@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('main')
    <div class="done-content">
        <div class="done-content-text">ご予約ありがとうございます</div>
        <div class="done-content-back">
            <a href="/mypage">戻る</a>
            <!-- <button type="button" onClick="history.back()">戻る</button> -->
        </div>
    </div>
@endsection