<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// RSS関連API
Route::get('/rss/get', [ApiController::class, 'RssGet']);
Route::get('/rss/set', [ApiController::class, 'RssSet']);