<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Actions\Fortify\CustomLoginAction;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::verifyEmailView(function () {
            $guard = Auth::getDefaultDriver();

            if ($guard === 'owner') {
                return view('auth.owner-verify-email'); // Admin専用のビュー
            } else {
                return view('auth.user-verify-email'); // User専用のビュー
            }
        });

        // Fortifyのログイン処理を独自のLoginRequestで設定
        Fortify::authenticateUsing(function ($request) {
            $userType = $request->input('user_type');
            if ($userType === 'owner') {
                $credentials = $request->only('email', 'password');
                if (Auth::guard('owner')->attempt($credentials)) {
                    return Auth::guard('owner')->user();
                }
            } elseif ($userType === 'user') {
                $credentials = $request->only('email', 'password');
                if (Auth::guard('user')->attempt($credentials)) {
                    return Auth::guard('user')->user();
                }
            }
            return null;
        });

        // ユーザー登録後のリダイレクトロジック
        app()->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            function () {
                return new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                    public function toResponse($request)
                    {
                        return Redirect::to('/thanks'); // リダイレクト先を指定
                    }
                };
            }
        );

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        // ログアウト後のリダイレクト先を指定
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });

    }
}
