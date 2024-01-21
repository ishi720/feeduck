<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
  return redirect('/search');
});

Route::get('/registration', 'RssController@registration')->name('registration');
Route::post('/registration','RssController@registration')->name('registration');

Route::get('/search', 'RssController@search')->name('search');
Route::post('/search', 'RssController@search')->name('search');

Route::get('/detail', 'RssController@detail')->name('detail');

Route::get('/feedlist', 'RssController@feedlist')->name('feedlist');
Route::post('/feedlist', 'RssController@feedlist')->name('feedlist');

Route::get('/api/get', 'ApiController@RssGet');
Route::get('/api/set', 'ApiController@RssSet');

Route::get('/edit', 'RssController@edit')->name('edit');
Route::post('/edit', 'RssController@edit')->name('edit');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
