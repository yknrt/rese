@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('main')
    <div class="thanks-content">
        <div class="thanks-content-text">会員登録ありがとうございます</div>
        <div class="thanks-content-btn">
            <a href="{{ route('login') }}">ログインする</a>
        </div>
    </div>
@endsection