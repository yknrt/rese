<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 共通ログインルート
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/thanks', [AuthController::class, 'showThanks'])->name('thanks');


// メール認証
// メール確認リンクをクリック後の処理
Route::get('/user/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if (!$request->user('user')->hasVerifiedEmail()) {
        $request->user('user')->markEmailAsVerified();
    }
    return redirect()->route('user.mypage');
})->middleware(['auth:user', 'signed'])->name('user.verification.verify');

Route::middleware('auth:user')->group(function () {
    Route::get('/user/email/verify', function () {
        return view('auth.user-verify-email');
    })->name('user.verification.notice');

    Route::post('/user/email/verification-notification', function () {
        request()->user('user')->sendEmailVerificationNotification();
        return back()->with('message', '確認メールが送信されました');
    })->middleware(['auth:user', 'throttle:6,1'])->name('user.verification.resend');
});

Route::get('/owner/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if (!$request->user('owner')->hasVerifiedEmail()) {
        $request->user('owner')->markEmailAsVerified();
    }
    return redirect()->route('owner.home');
})->middleware(['auth:owner', 'signed'])->name('owner.verification.verify');

Route::middleware('auth:owner')->group(function () {
    Route::get('/owner/email/verify', function () {
        return view('auth.owner-verify-email');
    })->name('owner.verification.notice');

    Route::post('/owner/email/verification-notification', function () {
        request()->user('owner')->sendEmailVerificationNotification();
        return back()->with('message', '確認メールが送信されました');
    })->middleware(['auth:owner', 'throttle:6,1'])->name('owner.verification.resend');
});

// 管理者ルート
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::post('/store', [AdminController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logoutAdmin'])->name('logout');
    Route::post('/send-email', [AdminController::class, 'sendEmail'])->name('send.email'); // メール送信
});

// 店舗代表者ルート
Route::prefix('owner')->name('owner.')->middleware('auth:owner', 'verified')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('home');
    Route::post('/store', [OwnerController::class, 'store']);
    Route::post('/update', [OwnerController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logoutOwner'])->name('logout');
});

// 利用者ルート
Route::name('user.')->middleware('auth:user', 'verified')->group(function () {
    Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
    Route::post('/mypage/update', [UserController::class, 'update']);
    Route::post('/reservation', [UserController::class, 'store']);
    Route::post('/reservation/review', [UserController::class, 'review']);
    Route::post('/delete', [UserController::class, 'delete']);
    Route::post('/favorite', [UserController::class, 'storeFavorite']);
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');
    Route::get('/checkout/:{reservation_id}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/charge', [PaymentController::class, 'charge'])->name('charge');
});

// guest
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/search', [ShopController::class, 'search']);
Route::get('/detail/:{shop_id}', [ShopController::class, 'detail'])->name('shop.detail');
