<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Lib;
use App\Library\Debug;
use DB;

Class Statistics extends Model
{	
	
	protected $fillable = ['language', 'Aug', 'skill'];
	
	public static function get_language ($code_language = "")
	{
		if ($code_language == "")
		{
			return FALSE;
		}
		 
		$obj = DB::table('job')
				->select(DB::raw('count(j_code) as language_total'))
				->where('description', 'LIKE', '%' . $code_language . '%')
				->whereBetween('appear_date', '');
		
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
		
		Statistics::updateOrCreate(
			array('language' => $pk),	
			$params
		);
		
		
	}
}