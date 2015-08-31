<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Lib;
use App\Library\Debug;
use DB;

Class Statistics extends Model
{	
	
	//protected $fillable = ['language', 'skill', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	
	public static function get_job_language ($code_language = "", $current_month)
	{
		if ($code_language == "")
		{
			return FALSE;
		}
		
	

		$current_month = date('Y-m-d', strtotime($current_month));
		
		$last_month    = date('Y-m-d', strtotime($current_month . "-1 month"));
		$obj = DB::table('job')
				->select(DB::raw('count(j_code) as language_total'))
				->whereBetween('appear_date', [$last_month, $current_month]);

		switch ($code_language)
		{
			case 'java' :
				$obj->where(function ($obj) {
					$obj->where('description', 'NOT LIKE', '%javascript%')
						->Where('description', 'LIKE', '%java%');
				});
				break;
				
			case 'ruby_on_rail' :
				
				$obj->where(function ($obj) {
					$obj->where('description', 'LIKE', '%ruby on rail%')
						->where('description', 'LIKE', '%ror%');
				});
				break;
				
			default:
				$obj->where('description', 'LIKE', '%' . $code_language . '%');
				break;
		}		
		
		
				
		
		echo $obj->toSql();
		$count = $obj->count();
		
		return $count;
	}
	
	
	/**
	 * 新增 
	 * @param  array $param 新增資料
	 * @return boolean      新增是否成功
	 */
	public static function insert($params = array())
	{
		$pk = $params['language'];
		
// 		Statistics::updateOrCreate(
// 			array('language' => $pk),	
// 			$params
// 		);
		
		
		// 查詢 job 是否已存在
		$result = DB::table('statistics')->where('language', $pk)->get();
		$row = ($result) ? $result[0] : NULL;
		
		// 新增/更新
		if ($row)
		{
			$params['updated_at'] = DB::raw('NOW()');
			DB::table('statistics')->where('language', $pk)->update($params);
		}
		else
		{
			$params['created_at'] = DB::raw('NOW()');
			$params['updated_at'] = DB::raw('NOW()');
			$id = DB::table('statistics')->insert($params);
		}
		
	}
	
	public static function get_all_month_by_language($language)
	{
		if ($language == "")
		{
			return FALSE;
		}
		
		$obj = DB::table('statistics')->select("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

		if ($language != "")
		{
			$obj->where('language', $language);
		}
		
		$result = $obj->get();
		//dd($obj->toSql());
		
		return $result[0];
	}
}