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
                <li><a href="/">Home</a></li>
                @auth
                    <!-- <li><a href="/login">Logout</a></li> -->
                    <li>
                        <form class="form" action="/logout" method="post">
                            @csrf
                            <button class="logout-btn" type="submit">Logout</button>
                        </form>
                    </li>
                    <li><a href="/mypage">Mypage</a></li>
                @endauth
                @guest
                    <li><a href="/register">Registration</a></li>
                    <li><a href="/login">Login</a></li>
                @endguest
            </ul>
        </div>
        <h1 class="title">Rese</h1>
        @yield('content')
    </div>
    @yield('main')
</body>

</html>