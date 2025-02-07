<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <div class="menu">
        <input type="checkbox" id="overlay-input" class="overlay-input" />
        <label for="overlay-input" class="overlay-button"><span></span></label>
        <div class="overlay-menu">
            <ul>
                <li><a href="/admin/home">Home</a></li>
                <li>
                    <form class="form" action="/logout" method="post">
                        @csrf
                        <button class="logout-btn" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        <h1 class="title">Rese</h1>
    </div>
    @if (session('message'))
        <p class="message">{{ session('message') }}</p>
    @endif
    <div class="main">
        <div class="register-content">
            <div class="content-heading">
                <div class="title_text">店舗代表者新規登録</div>
            </div>
            <form class="form" action="/admin/store" method="post">
                @csrf
                <div class="form__group">
                    <div class="form__input-text">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" />
                    </div>
                </div>
                <div class="form__error">
                    <!-- エラーメッセージ -->
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form__group">
                    <div class="form__input-text">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
                    </div>
                </div>
                <div class="form__error">
                    <!-- エラーメッセージ -->
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
                <div class="form__group">
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
                    <input type="hidden" name="user_type" value="owner">
                    <button class="form__button-submit" type="submit">登録</button>
                </div>
            </form>
        </div>
        <div class="mail-content">
            <div class="content-heading">
                <div class="title_text">メールフォーム</div>
            </div>
            <form action="{{ route('admin.send.email') }}" method="POST">
                @csrf
                <div class="content-select">
                    <select name="name" class="form-select">
                        <option disabled selected>To</option>
                        <option value="all">一括</option>
                        @foreach($owners as $owner)
                        <option value={{ $owner->id }} {{ old('name')==$owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="content-text">
                    <textarea name="message" rows="5" placeholder="message" required></textarea>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">メール送信</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>