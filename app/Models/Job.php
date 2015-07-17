<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * job 資料表的操作
 */
class Job extends Model
{
    /**
     * j_code 列表
     * @var array
     */
    private static $_j_code_list;

    /**
     * 新增 job
     * @param  array $param 新增資料
     * @return boolean      新增是否成功
     */
    public static function insert($param = '')
    {
        // 無論是物件還是陣列，一律轉成陣列
        $param = (array) $param;

        // 取出 j_code
        $j_code = ($param['j_code']) ? $param['j_code'] : NULL;

        // 資料已存在則取消操作
        if ($j_code && isset(self::$_j_code_list[$j_code]))
        {
            return FALSE;
        }

        // 查詢 job 是否已存在
        $job_rows = DB::table('job')->where('j_code', $j_code)->get();
        $row = ($job_rows) ? $job_rows[0] : NULL;

        // 新增/更新
        if ($row)
        {
            $id = $row->jobID;
            DB::table('job')->where('jobID', $id)->update($param);
        }
        else
        {
            $id = DB::table('job')->insertGetId($param);
        }

        // 儲存 j_code
        self::$_j_code_list[$j_code] = $id;

        return TRUE;
    }

    /**
     * 搜尋
     * @param  array  $param 搜尋條件
     * @return array         job 資料
     */
    public static function search($param = [])
    {
        return DB::table('job')->limit(100)->get();
    }
}
