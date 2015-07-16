<?php
namespace App\Models;

/**
* Job 基礎類別
*/
abstract class JobBase
{
    /**
     * 資料來源的過瀘條件
     * @var array
     */
    protected $_condition;

    /**
     * 更新(從來源更新資料庫)
     */
    abstract public function update();

    /**
     * 查詢(從本地資料庫查詢)
     */
    abstract public function search();
}
