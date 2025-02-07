<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    /**
     * 認証後のリダイレクト処理をカスタマイズ
     */
    protected function redirectTo(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return route('admin.home'); // 管理者なら admin.home へ
        } elseif (Auth::guard('owner')->check()) {
            return route('owner.home'); // 通常ユーザーなら user.home へ
        } elseif (Auth::guard('user')->check()) {
            return route('user.home'); // 通常ユーザーなら user.home へ
        }
    }

    /**
     * ログイン処理をオーバーライドしてリダイレクト先を変更
     */
    public function store(Request $request)
    {
        $response = parent::store($request); // Fortifyのログイン処理を実行

        return redirect($this->redirectTo($request)); // ログイン後のリダイレクトを適用
    }
}
