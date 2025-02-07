@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('main')
    <div class="thanks-content">
        <div class="thanks-content-text">ご予約ありがとうございます</div>
        <div class="thanks-content-btn">
            <a href="/mypage">戻る</a>
        </div>
    </div>
@endsection