<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;


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

Route::middleware('auth')->group(function () {
    Route::get('/', [ShopController::class, 'index']);
    Route::get('/search', [ShopController::class, 'search']);
    Route::get('/detail/:{shop_id}', [ShopController::class, 'detail'])->name('shop.detail');
    Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
    Route::post('/mypage/edit', [UserController::class, 'edit']);
    Route::post('/reservation', [UserController::class, 'store']);
    Route::post('/delete', [UserController::class, 'delete']);
    Route::post('/favorite', [UserController::class, 'storeFavorite']);
    Route::post('/reservation/review', [UserController::class, 'review']);
});

Route::get('/thanks', function () {
    return view('thanks');
})->middleware(['auth'])->name('thanks');