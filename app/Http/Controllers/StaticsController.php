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
	public $all_language = array( 
			1 => 'backend', 
			2 => 'forend',
			3 => 'mobile',
			4 => 'os',
			5 => 'db'
	);
	
	public $backend_language_list = [
			'java',
			'asp_net',
			'c#',
			'python',
			'php',
			'ruby_on_rail',
			
	];
	
	public $forend_language_list = [
			'node_js',
			'javascript',
			'angular_js',
			'jquery',
			'css3',
			'html5',
				
	];
	
	public $mobile_language_list = [
			'android',
			'ios',
				
	];
	
	public $os_language_list = [
			'c++',
			'linux',
			'nginx',
			'apache',
			'iis',
			
	];
	
	public $db_language_list = [
			'mysql',
			'nosql',
			'mongodb',
			'sqlite',
			'memcache',
			'redis',
			'mssql',
			'oracle'
	];
	
	public function update($date = "")
	{
		if ($date == "")
		{
			$current_mon = date('M');
		}
		else
		{
			$current_mon = date('M', strtotime($date));
		}
		
		foreach ($this->all_language as $skill => $lang_list)
		{
			$var_name = $lang_list . '_language_list';
			foreach ($this->$var_name as $language)
			{
				
				$statics['language'] = $language;
				$statics['skill'] = $skill;
				$statics[$current_mon] = Statistics::get_job_language($language, $current_mon);
				
				var_dump($statics);
				
				Statistics::insert($statics);	
			}
		}
		
	}
	
	private function _code_num ($language)
	{
	
		$statics = (array) Statistics::get_all_month_by_language($language);
		$statics = array_values($statics);
	
		return $statics;
	}
	
	public function trend_line($language = "all")
	{
		if ($language == "all")
		{
			foreach ($this->all_language as $skill => $lang_list)
			{
				$var_name = $lang_list . '_language_list';
				
				foreach ($this->$var_name as $language)
				{
					$statics[$language] = self::_code_num ($language);
				}
			}
		
		}
		else 
		{
			$statics[$language] = self::_code_num ($language);
		}
		
		//var_dump($statics);
		//return view('statistics/chart', array('data' => $statics ) );
		
		
		return view('statistics/chart', array('data' => $statics ) );
	}
	
	
	public function rank_bar($month = "")
	{
		
		$statics = (array) Statistics::get_all_language_by_month($month);
			
		return view('statistics/rank_bar', array('data' => $statics ) );
	}
	
	public function skill_pie($month = "")
	{
		$statics = (array) Statistics::get_all_skill_by_month($month);
			
		return view('statistics/skill_pie', array('data' => $statics, 'language' => $this->all_language ) );
	}
	
	public function skill_group_pie ($skill = "")
	{
		$statics = (array) Statistics::get_language_by_skill($skill);
			
		return view('statistics/skill_pie', array('data' => $statics, 'language' => $this->all_language ) );
	}
	
	public function dashboard ()
	{
		
	}
	
	
}