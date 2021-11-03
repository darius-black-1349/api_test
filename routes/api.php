<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::prefix('v1')->group(function(){


    // AUTH ROUTES
    Route::prefix('/auth')->group(function(){


        Route::post('/register', 'Api\v1\Auth\AuthController@register')->name('auth.register');
        Route::post('/login', 'Api\v1\Auth\AuthController@login')->name('auth.login');
        Route::post('/logout', 'Api\v1\Auth\AuthController@logout')->name('auth.logout');

        Route::get('/user', 'Api\v1\Auth\AuthController@user')->name('auth.user');

    });


    // Channel ROUTES
    Route::prefix('/channel')->group(function(){


        Route::get('/all', 'Api\v1\Channel\ChannelController@getAllChannelsList')->name('channel.all');
        Route::post('/create', 'Api\v1\Channel\ChannelController@createNewChannel')->name('channel.create');
        Route::put('/update', 'Api\v1\Channel\ChannelController@updateChannel')->name('channel.update');


    });


});
