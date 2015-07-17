<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * company 資料表操作
 */
class Company extends Model
{
    /**
     * c_code 列表
     * @var array
     */
    private static $_c_code_list;

    /**
     * 新增 company
     * @param  array $param 新增資料
     * @return boolean      新增是否成功
     */
    public static function insert($param = '')
    {
        // 無論是物件還是陣列，一律轉成陣列
        $param = (array) $param;

        // 取出 c_code
        $c_code = ($param['c_code']) ? $param['c_code'] : NULL;

        // 資料已存在則取消操作
        if ($c_code && isset(self::$_c_code_list[$c_code]))
        {
            return self::$_c_code_list[$c_code];
        }

        // 查詢 company 是否已存在
        $company_rows = DB::table('company')->where('c_code', $c_code)->get();
        $row = ($company_rows) ? $company_rows[0] : NULL;

        // 新增/更新
        if ($row)
        {
            $id = $row->companyID;
            DB::table('company')->where('companyID', $id)->update($param);
        }
        else
        {
            $id = DB::table('company')->insertGetId($param);
        }

        // 儲存 c_code
        self::$_c_code_list[$c_code] = $id;

        return $id;
    }
}
