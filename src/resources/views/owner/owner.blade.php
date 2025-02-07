<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner.css') }}">
</head>

<body>
    <div class="menu">
        <input type="checkbox" id="overlay-input" class="overlay-input" />
        <label for="overlay-input" class="overlay-button"><span></span></label>
        <div class="overlay-menu">
            <ul>
                <li><a href="/">Home</a></li>
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
        <div class="content">
            @if( empty($myShop) )
            <div class="content-form">
                <div class="form--ttl">店舗情報作成</div>
                <form action="/owner/store" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form_group">
                        <div class="form__input-text">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Shop Name" />
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-select">
                            <select name="area" class="select" id="store-area" onchange="toggleInput(this, 'input1')">
                                <option disabled selected>Area</option>
                                @foreach($areas as $area)
                                <option value={{ $area->id }} {{ old('area')==$area->id ? 'selected' : '' }}>{{ $area->area }}</option>
                                @endforeach
                                <option value="other">その他</option>
                            </select>
                            <input type="text" id="input1" name="newArea" placeholder="New Area" disabled>
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('area')
                            {{ $message }}
                            @enderror
                            @error('newArea')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-select">
                            <select name="genre" class="select" id="store-genre" onchange="toggleInput(this, 'input2')">
                                <option disabled selected>Genre</option>
                                @foreach($genres as $genre)
                                <option value={{ $genre->id }} {{ old('genre')==$genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                                @endforeach
                                <option value="other">その他</option>
                            </select>
                            <input type="text" id="input2" name="newGenre" placeholder="New Genre" disabled>
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('genre')
                            {{ $message }}
                            @enderror
                            @error('newGenre')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-text">
                            <textarea name="summary" placeholder="summary" row="5"></textarea>
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('summary')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="img-label">アップロードするファイルを選択してください</div>
                        <div class="form__input-img">
                            <input type="file" name="image">
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('image')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__btn">
                            <input type="hidden" name="user_type" value="owner">
                            <button class="form__btn-submit" type="submit">登録</button>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="content-form">
                <div class="form--ttl">店舗情報編集</div>
                <form class="form" action="/owner/update" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form_group">
                        <div class="form__input-text">
                            <input type="text" name="name" value="{{ $myShop->name }}" placeholder="Shop Name" />
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-select">
                            <select name="area" class="select" id="store-area" onchange="toggleInput(this, 'input3')">
                                <option disabled selected>Area</option>
                                @foreach($areas as $area)
                                <option value={{ $area->id }} {{ $myShop->area_id==$area->id ? 'selected' : '' }}>{{ $area->area }}</option>
                                @endforeach
                                <option value="other">その他</option>
                            </select>
                            <input type="text" id="input3" name="newArea" placeholder="New Area" disabled>
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('area')
                            {{ $message }}
                            @enderror
                            @error('newArea')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-select">
                            <select name="genre" class="select" id="store-genre" onchange="toggleInput(this, 'input4')">
                                <option value="">Genre</option>
                                @foreach($genres as $genre)
                                <option value={{ $genre->id }} {{ $myShop->genre_id==$genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                                @endforeach
                                <option value="other">その他</option>
                            </select>
                            <input type="text" id="input4" name="newGenre" placeholder="New Genre" disabled>
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('genre')
                            {{ $message }}
                            @enderror
                            @error('newGenre')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__input-text">
                            <textarea name="summary" placeholder="summary" row="5">{{ $myShop->summary }}</textarea>
                        </div>
                        <div class="now-img">
                            <div class="img-label">現在のファイル</div>
                            <img src="{{ asset($myShop->img) }}" alt="">
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('summary')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="img-label">変更したい場合アップロードするファイルを選択してください</div>
                        <div class="form__input-img">
                            <input type="file" name="image" value="{{ $myShop->img }}">
                        </div>
                        <div class="form__error">
                            <!-- エラーメッセージ -->
                            @error('image')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__btn">
                            <input type="hidden" name="user_type" value="owner">
                            <button class="form__btn-submit" type="submit">保存</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
        <div class="reservation">
            <div class="reservation-ttl">予約情報一覧</div>
            @foreach($records as $record)
            <div class="reservation-table">
                <table class="reservation-table__inner">
                    <tr class="reservation-table__row">
                        <th class="reservation-table__header">Name</th>
                        <td class="reservation-table__text">{{ $record->user->name }}</td>
                    </tr>
                    <tr class="reservation-table__row">
                        <th class="reservation-table__header">Date</th>
                        <td class="reservation-table__text">{{ $record->date }}</td>
                    </tr>
                    <tr class="reservation-table__row">
                        <th class="reservation-table__header">Time</th>
                        <td class="reservation-table__text">{{ \Carbon\Carbon::parse($record->time)->format('H:i') }}</td>
                    </tr>
                    <tr class="reservation-table__row">
                        <th class="reservation-table__header">Number</th>
                        <td class="reservation-table__text">{{ $record->number }}人</td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</body>
<script>
    function toggleInput(select, inputId) {
        // 対応するinput要素を取得
        const inputField = document.getElementById(inputId);

        // 特定の値が選ばれた場合のみinputを有効化
        if (select.value === 'other') {
            inputField.disabled = false; // 有効化
        } else {
            inputField.disabled = true;  // 無効化
            inputField.value = '';       // 値をリセット
        }
    }
</script>
</html>