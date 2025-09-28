<?php

use App\Http\Controllers\RssController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;


Route::redirect('/', '/search');

// 登録画面
Route::get('/registration', [RssController::class, 'registration'])->name('registration');
Route::post('/registration', [RssController::class, 'registration'])->name('registration');

// 検索画面
Route::get('/search', [RssController::class, 'search'])->name('search');
Route::post('/search', [RssController::class, 'search'])->name('search');

// RSS詳細画面
Route::get('/detail', [RssController::class, 'detail'])->name('detail');

// Feed一覧画面
Route::get('/feedlist', [RssController::class, 'feedlist'])->name('feedlist');
Route::post('/feedlist', [RssController::class, 'feedlist'])->name('feedlist');

// RSS情報編集画面
Route::get('/edit', [RssController::class, 'edit'])->name('edit');
Route::post('/edit', [RssController::class, 'edit'])->name('edit');

// 認証
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// API
Route::get('/api/get', 'ApiController@RssGet');
Route::get('/api/set', 'ApiController@RssSet');
