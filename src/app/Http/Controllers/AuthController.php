<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    // ログイン画面
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $userType = $request->user_type;

        if (Auth::guard($userType)->attempt($credentials)) {
            // ログイン成功時のリダイレクト
            if (!$request->user($userType)->hasVerifiedEmail()) {
                switch ($userType) {
                    case 'owner':
                        return redirect()->route('owner.verification.notice');
                    case 'user':
                        return redirect()->route('user.verification.notice');
                }
            }

            switch ($userType) {
                case 'admin':
                    return redirect()->route('admin.home');
                case 'owner':
                    return redirect()->route('owner.home');
                case 'user':
                    return redirect()->route('user.mypage');
            }
        }

        // ログイン失敗時
        return back()->withErrors([
            'email' => 'メールアドレスかパスワードが間違っています。',
        ])->withInput($request->except('password'));
    }

    // ログアウト処理
    public function logoutAdmin()
    {
        Auth::guard('admin')->logout();
        Session::flush();
        Cache::flush();
        return redirect()->route('login');
    }

    public function logoutOwner()
    {
        Auth::guard('owner')->logout();
        Session::flush();
        Cache::flush();
        return redirect()->route('login');
    }

    public function logoutUser()
    {
        Auth::guard('user')->logout();
        Session::flush();
        Cache::flush();
        return redirect()->route('login');
    }

    // ユーザー登録画面
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // ユーザー登録処理
    public function register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password');
        $data['password'] = Hash::make($data['password']);
        $userType = $request->user_type;

        if ($userType==='user') {
            $user = User::create($data);
            return redirect()->route('thanks');
        }
        return back()->withErrors(['user_type' => 'Invalid user type selected.']);
    }

    public function showThanks()
    {
        return view('auth.thanks');
    }

    // メール確認画面を表示
    public function showVerify()
    {
        return view('auth.verify-email');
    }

}
