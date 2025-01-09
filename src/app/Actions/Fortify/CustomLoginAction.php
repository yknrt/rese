<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse;

class CustomLoginAction
{
    public function __invoke(LoginRequest $request)
    {
        // バリデーション済みのリクエストデータを使用
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // ログイン成功時にセッション再生成
            $request->session()->regenerate();

            // FortifyのLoginResponseインターフェースを使ってリダイレクト
            return app(LoginResponse::class);
        }

        // 認証失敗時の処理
        return back()->withErrors([
            'email' => '認証に失敗しました。',
        ]);
    }
}