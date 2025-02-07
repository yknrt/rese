<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認メール送信</title>
</head>

<body>
    <h1>メール認証が必要です</h1>
    <p>下のボタンをクリックすると登録したメールアドレスに確認メールを送信します。</p>
    <p>メール内のリンクをクリックして認証を完了してください。</p>

    @if (session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('owner.verification.resend') }}">
        @csrf
        <button type="submit">確認メールを送信</button>
    </form>
</body>
