<?php


// AUTH ROUTES

use App\Http\Controllers\Api\v1\Thread\ThreadController;
use Illuminate\Support\Facades\Route;


Route::apiResource('/threads', ThreadController::class);
