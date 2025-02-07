<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    @yield('css')
</head>

<body>
    <div class="menu">
        <input type="checkbox" id="overlay-input" class="overlay-input" />
        <label for="overlay-input" class="overlay-button"><span></span></label>
        <div class="overlay-menu">
            <ul>
                @auth
                    @if (Auth::guard('admin')->check())
                    <li><a href="/admin/home">Home</a></li>
                    @elseif (Auth::guard('owner')->check())
                    <li><a href="/owner/home">Home</a></li>
                    @elseif (Auth::guard('user')->check())
                    <li><a href="/">Home</a></li>
                    @endif
                    <li>
                        <form class="form" action="/logout" method="post">
                            @csrf
                            <button class="logout-btn" type="submit">Logout</button>
                        </form>
                    </li>
                    @if (Auth::guard('user')->check())
                    <li><a href="/mypage">Mypage</a></li>
                    @endif
                @endauth
                @guest
                    <li><a href="/">Home</a></li>
                    <li><a href="/register">Registration</a></li>
                    <li><a href="/login">Login</a></li>
                @endguest
            </ul>
        </div>
        <h1 class="title">Rese</h1>
        @yield('content')
    </div>
    @if (session('message'))
        <p class="message">{{ session('message') }}</p>
    @endif
    @yield('main')
</body>

</html>