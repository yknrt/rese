@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('main')
    <div class="login-content">
        <div class="content-heading">
            <div class="title_text">Login</div>
        </div>
        <form class="form" action="/login" method="post">
            @csrf
            <div class="form__group">
                <img src="{{ asset('images/email.svg') }}" alt="email icon" class="icon">
                <div class="form__input-text">
                    <input type="email" name="email" placeholder="Email" />
                </div>
            </div>
            <div class="form__error">
                <!-- エラーメッセージ -->
                @error('email')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <img src="{{ asset('images/key.svg') }}" alt="key icon" class="icon">
                <div class="form__input-text">
                    <input type="password" name="password" placeholder="Password" />
                </div>
            </div>
            <div class="form__error">
                <!-- エラーメッセージ -->
                @error('password')
                {{ $message }}
                @enderror
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">ログイン</button>
            </div>
        </form>
    </div>
@endsection