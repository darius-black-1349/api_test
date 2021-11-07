<?php


// AUTH ROUTES

use App\Http\Controllers\Api\v1\Channel\ChannelController;
use Illuminate\Support\Facades\Route;


Route::prefix('/channel')->middleware('can:channel management')->group(function () {


    Route::get('/all', [

        ChannelController::class, 'getAllChannelsList'

    ])->name('channel.all');

    Route::post('/create', [

        ChannelController::class, 'createNewChannel'

    ])->name('channel.create');
    Route::put('/update', [

        ChannelController::class, 'updateChannel'

    ])->name('channel.update');
    Route::delete('/delete', [

        ChannelController::class, 'deleteChannel'

    ])->name('channel.delete');
});
