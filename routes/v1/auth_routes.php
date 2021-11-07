<?php


 // AUTH ROUTES

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;




Route::prefix('/auth')->group(function () {


    Route::post('/register', [
        AuthController::class, 'register'
    ])->name('auth.register');

    Route::post('/login', [
        AuthController::class, 'login'
    ])->name('auth.login');

    Route::post('/logout', [
        AuthController::class, 'logout'
    ])->name('auth.logout');


    Route::get('/user', [
        AuthController::class, 'user'
    ])->name('auth.user');
});
