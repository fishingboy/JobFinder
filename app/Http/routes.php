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
Route::get('/list', 'ListController@index');
Route::get('/list/{source}', 'ListController@index');
Route::get('/update', 'ListController@update');
Route::get('/update/{source}', 'ListController@update');

// 爬蟲
Route::get('/crawler/company/get/{companyID}', 'CrawlerController@get_company');
Route::get('/crawler/company/get', 'CrawlerController@get_company');
Route::get('/crawler/company/update', 'CrawlerController@update_company');

// API
Route::get('/job/get', 'JobController@get');
Route::get('/job/get/{format}', 'JobController@get');
Route::get('/company/get', 'CompanyController@get');
Route::get('/company/get/{format}', 'CompanyController@get');


// 資料庫操作
Route::get('/truncate', 'ToolController@truncate');

// test
Route::get('/db_test', 'ListController@db_test');
Route::get('/plugin/firephp', 'PluginController@firephp');

