<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// 歡迎
Route::get('/welcome', function()
{
    return view('welcome');
});

// 工作列表
Route::get('/', 'ListController@index');
Route::get('/list', 'ListController@index');
Route::get('/ptt_list', 'ListController@ptt_list');
