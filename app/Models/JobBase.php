<?php
namespace App\Models;

/**
* Job 基礎類別
*/
abstract class JobBase
{
	/**
	 * 查詢
	 */
	abstract public function search();
}
