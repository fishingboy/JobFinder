<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Statistics
;


/**
 * Static API
 */
class StaticsController extends Controller
{
	public $backend_language_list = [
			'java',
			'asp_net',
			'c#',
			'phyton',
			'php',
			'ruby_on_rail',
			
	];
	
	public $forend_language_list = [
			'node_js',
			'javascript',
			'anglar_js',
			'jquery',
			'css3',
			'html5',
				
	];
	
	public $mobile_language_list = [
			'andorid',
			'ios',
				
	];
	
	public $os_language_list = [
			'c++',
			'linux',
			'ngix',
			'apache',
			'iis',
			
	];
	
	public $db_language_list = [
			'mysql',
			'nosql',
			'mongodb',
			'sqlite',
			'memcache',
			'radis',
			'mssql',
			'oracle'
	];
	
	public function update()
	{
		
		foreach ($this->backend_language_list as $language)
		{
			
			$current_mon = date('M');
			$statics['language'] = $language;
			$statics['skill'] = 1;
			$statics[$current_mon] = Statistics::get_language($language);
			
			var_dump($statics);
			
			Statistics::insert($statics);	
		}
		
		
		exit;
	}
	
	public function get($code_language = "all")
	{
		if ($code_language == "all")
		{
			
		}
		
		//StaticJob::get($code_language);
		
		
		return view('static/chart');
	}
	
}