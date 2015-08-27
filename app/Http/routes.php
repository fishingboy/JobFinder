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
Route::get('/welcome', function(){return view('welcome'); });

// 目前的 route 總表，方便測試
Route::get('/', function(){return view('link');});

// 工作列表
Route::get('/list', 'ListController@listJob');
Route::get('/list/104', 'ListController@listJob');
Route::get('/list/ptt', 'ListController@listJobPtt');
Route::get('/list/company', 'ListController@listCompany');

// 資料更新
Route::get('/update', 'UpdateController@update');
Route::get('/update/{source}', 'UpdateController@update');
Route::get('/old_list', 'UpdateController@index');
Route::get('/old_list/{source}', 'UpdateController@index');

// 爬蟲
Route::get('/crawler/company/get/{companyID}', 'CrawlerController@get_company');
Route::get('/crawler/company/get', 'CrawlerController@get_company');
Route::get('/crawler/company/update', 'CrawlerController@update_company');

// API
// job
Route::match(['get', 'post'], '/job', 'JobController@get');
Route::match(['get', 'post'], '/job/position', 'JobController@get_position');
Route::match(['get', 'post'], '/job/position/{format}', 'JobController@get_position');
Route::match(['get', 'post'], '/job/test', 'JobController@test');
Route::match(['get', 'post'], '/job/{format}', 'JobController@get');
Route::match(['get', 'post'], '/job/get', 'JobController@get');
Route::match(['get', 'post'], '/job/get/{format}', 'JobController@get');

// company
Route::match(['get', 'post'], '/company', 'CompanyController@get');
Route::match(['get', 'post'], '/company/test', 'CompanyController@test');
Route::match(['get', 'post'], '/company/post_test', 'CompanyController@post_test');
Route::match(['get', 'post'], '/company/{format}', 'CompanyController@get');
Route::match(['get', 'post'], '/company/get/{format}', 'CompanyController@get');
Route::match(['get', 'post'], '/company/get/{format}', 'CompanyController@get');

// favorite
Route::match(['get', 'post'], '/favorite', 'FavoriteController@add');
Route::match(['get', 'post'], '/favorite/add', 'FavoriteController@add');
Route::match(['get', 'post'], '/favorite/sort', 'FavoriteController@sort');
Route::match(['get', 'post'], '/favorite/test', 'FavoriteController@test');
Route::match(['get', 'post'], '/favorite/post_test', 'FavoriteController@post_test');
Route::match(['get', 'post'], '/favorite/{format}', 'FavoriteController@get');
Route::match(['get', 'post'], '/favorite/get/{format}', 'FavoriteController@get');
Route::match(['get', 'post'], '/favorite/get/{format}', 'FavoriteController@get');

// 轉址器
Route::get('/go/job/{j_code}', 'GoController@job');
Route::get('/go/company/{j_code}', 'GoController@company');

// 資料庫操作
Route::get('/truncate', 'ToolController@truncate');
Route::get('/clear_readed', 'ToolController@clear_readed');

// test
Route::get('/plugin/firephp', 'PluginController@firephp');
