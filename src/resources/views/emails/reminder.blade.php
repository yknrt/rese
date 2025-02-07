<!DOCTYPE html>
<html>
<head>
    <title>リマインダー通知</title>
</head>
<body>
    <h1>{{ $data['name'] }} 様</h1>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p>ご利用いただきありがとうございます。<br>本日の予約内容をお知らせします。<br>※このメールは{{ \Carbon\Carbon::now()->format("Y/m/d H:i") }}時点の情報を元にお送りしています。<br></p>
    <p><strong>■予約内容</strong></p>
    <p>店名    :{{ $data['shop'] }}</p>
    <p>予約日    {{ $data['date'] }}</p>
    <p>予約時刻  {{ $data['time'] }}</p>
</body>
</html>