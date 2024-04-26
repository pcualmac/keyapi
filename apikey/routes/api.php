<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUser;


// Route::prefix('v2')->group(function () {
//     Route::prefix('app1')->group(function () {
//         Route::post('login', [AppOneAuthController::class, 'login']);
//     });
//     Route::prefix('app2')->group(function () {
//         Route::post('login', [AppTwoAuthController::class, 'login']);
//     });
// });

Route::post('register/user',[AuthUser::class, 'registerUser']);
Route::post('login/user',[AuthUser::class, 'loginUser']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('verify/user',[AuthUser::class, 'verifyToken']);
});
