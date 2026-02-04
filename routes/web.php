<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 歡迎
Route::get('/welcome', function(){return view('welcome'); });

// 目前的 route 總表，方便測試
Route::get('/', function(){return view('link');});
Route::get('/map', function(){return view('gmap/gmap');});

// 工作列表
Route::get('/list', 'App\Http\Controllers\ListController@listJob');
Route::get('/list/104', 'App\Http\Controllers\ListController@listJob');
Route::get('/list/ptt', 'App\Http\Controllers\ListController@listJobPtt');
Route::get('/list/company', 'App\Http\Controllers\ListController@listCompany');

// 資料更新
Route::get('/update', 'App\Http\Controllers\UpdateController@update');
Route::get('/update/{source}', 'App\Http\Controllers\UpdateController@update');
Route::get('/old_list', 'App\Http\Controllers\UpdateController@index');
Route::get('/old_list/{source}', 'App\Http\Controllers\UpdateController@index');

// 爬蟲
Route::get('/crawler/company/get/{companyID}', 'App\Http\Controllers\CrawlerController@get_company');
Route::get('/crawler/company/get', 'App\Http\Controllers\CrawlerController@get_company');
Route::get('/crawler/company/update', 'App\Http\Controllers\CrawlerController@update_company');
Route::get('/crawler/mrt', 'App\Http\Controllers\CrawlerController@getMrt');

// API
// job
Route::match(['get', 'post'], '/job', 'App\Http\Controllers\JobController@get');
Route::match(['get', 'post'], '/job/position', 'App\Http\Controllers\JobController@get_position');
Route::match(['get', 'post'], '/job/position/{format}', 'App\Http\Controllers\JobController@get_position');
Route::match(['get', 'post'], '/job/test', 'App\Http\Controllers\JobController@test');
Route::match(['get', 'post'], '/job/{format}', 'App\Http\Controllers\JobController@get');
Route::match(['get', 'post'], '/job/get', 'App\Http\Controllers\JobController@get');
Route::match(['get', 'post'], '/job/get/{format}', 'App\Http\Controllers\JobController@get');

// company
Route::match(['get', 'post'], '/company', 'App\Http\Controllers\CompanyController@get');
Route::match(['get', 'post'], '/company/test', 'App\Http\Controllers\CompanyController@test');
Route::match(['get', 'post'], '/company/post_test', 'App\Http\Controllers\CompanyController@post_test');
Route::match(['get', 'post'], '/company/{format}', 'App\Http\Controllers\CompanyController@get');
Route::match(['get', 'post'], '/company/get/{format}', 'App\Http\Controllers\CompanyController@get');

// favorite
Route::match(['get', 'post'], '/favorite', 'App\Http\Controllers\FavoriteController@add');
Route::match(['get', 'post'], '/favorite/add', 'App\Http\Controllers\FavoriteController@add');
Route::match(['get', 'post'], '/favorite/sort', 'App\Http\Controllers\FavoriteController@sort');
Route::match(['get', 'post'], '/favorite/test', 'App\Http\Controllers\FavoriteController@test');
Route::match(['get', 'post'], '/favorite/post_test', 'App\Http\Controllers\FavoriteController@post_test');
Route::match(['get', 'post'], '/favorite/{format}', 'App\Http\Controllers\FavoriteController@get');
Route::match(['get', 'post'], '/favorite/get/{format}', 'App\Http\Controllers\FavoriteController@get');

// 統計
Route::get('/statistics/dashboard', 'App\Http\Controllers\StaticsController@dashboard');
Route::get('/statistics/dashboard/{month}', 'App\Http\Controllers\StaticsController@dashboard');
Route::get('/statistics/update', 'App\Http\Controllers\StaticsController@update');
Route::get('/statistics/update/{date}', 'App\Http\Controllers\StaticsController@update');
Route::get('/statistics/trend_line/', 'App\Http\Controllers\StaticsController@trend_line');
Route::get('/statistics/trend_line/{language}', 'App\Http\Controllers\StaticsController@trend_line');
Route::get('/statistics/rank_bar', 'App\Http\Controllers\StaticsController@rank_bar');
Route::get('/statistics/rank_bar/{month}', 'App\Http\Controllers\StaticsController@rank_bar');
Route::get('/statistics/skill_pie', 'App\Http\Controllers\StaticsController@skill_pie');
Route::get('/statistics/skill_pie/{month}', 'App\Http\Controllers\StaticsController@skill_pie');
Route::get('/statistics/skill_group_pie', 'App\Http\Controllers\StaticsController@skill_group_pie');
Route::get('/statistics/skill_group_pie/{month}', 'App\Http\Controllers\StaticsController@skill_group_pie');

// 轉址器
Route::get('/go/job/{j_code}', 'App\Http\Controllers\GoController@job');
Route::get('/go/company/{j_code}', 'App\Http\Controllers\GoController@company');

// 資料庫操作
Route::get('/truncate', 'App\Http\Controllers\ToolController@truncate');
Route::get('/clear_readed', 'App\Http\Controllers\ToolController@clear_readed');

// test
Route::get('/plugin/firephp', 'App\Http\Controllers\PluginController@firephp');

Route::resource('mrt', 'App\Http\Controllers\MrtController', ['only' => 'index']);
