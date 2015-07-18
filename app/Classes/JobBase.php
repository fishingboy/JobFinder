<?php
namespace App\Classes;

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
     * 允許搜尋的欄位
     * (由各子類別自行定義，將影響搜尋時的行為)
     *
     * @var array
     */
    protected $_allow_search_field = [];

    /**
     * 更新(從來源更新資料庫)
     */
    abstract public function update();

    /**
     * 查詢(從本地資料庫查詢)
     */
    abstract public function search();

    /**
     * 取得允許查詢欄位
     * @return array 允許查詢欄位
     */
    public function get_allow_search_field()
    {
        return $this->_allow_search_field;
    }
}
