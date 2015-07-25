<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Lib;
use App\Library\Debug;
use DB;

/**
 * favorite 資料表操作
 */
class Favorite extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorite';

    /**
     * 新增 favorite
     * @param  array $param 新增資料
     * @return boolean      新增是否成功
     */
    public static function insert($param = '')
    {
        // 無論是物件還是陣列，一律轉成陣列
        $param = (array) $param;

        // 查詢 company 是否已存在
        $rows = DB::table('favorite')->where('resID', $param['resID'])->get();
        $row = ($rows) ? $rows[0] : NULL;

        // 新增/更新
        if ($row)
        {
            return $row->favoriteID;
        }
        else
        {
            $param['created_at'] = DB::raw('NOW()');
            $param['updated_at'] = DB::raw('NOW()');
            return DB::table('favorite')->insertGetId($param);
        }
    }

    /**
     * 搜尋
     * @param  array  $param 搜尋條件
     * @return array         company 資料
     */
    public static function search($param = [])
    {
        $page_size = (isset($param['page_size'])) ? $param['page_size'] : 50;
        $page      = (isset($param['page'])) ? $param['page'] : 1;

        // 分頁
        $page_start = ($page - 1) * $page_size;

        if (isset($param['type']) && $param['type'] == 1)
        {
            // 排序
            $orderby = [];
            if (isset($param['orderby']))
            {
                foreach ($param['orderby'] as $key => $asc)
                {
                    $orderby[] = "{$key} {$asc}";
                }
            }
            $orderby = 'ORDER BY ' . implode(',', $orderby);

            // 查詢 company 資料
            //                     WHERE `favorite`.`type` = 2
            $sql = "SELECT SQL_CALC_FOUND_ROWS `job`.*, `company`.*
                    FROM  `favorite`
                          INNER JOIN `job`
                                ON `favorite`.`resID` = `job`.`jobID`
                          INNER JOIN `company`
                                ON `job`.`companyID` = `company`.`companyID`
                    $orderby
                    LIMIT $page_start, $page_size";
            // echo "<pre>sql = " . print_r($sql, TRUE). "</pre>";
            $rows  = DB::select($sql);
            $count = DB::select("SELECT FOUND_ROWS() as cnt")[0]->cnt;
            $total_page = ceil($count / $page_size);

            /* 轉換資料格式 */
            foreach ($rows as $key => $row)
            {
                // 員人工數
                $row->employees = Lib::convert_employees($row->employees);
                // 資本額
                $row->capital   = Lib::number2capital($row->capital);
                // 薪資
                $row->pay       = Lib::convert_pay($row->sal_month_low, $row->sal_month_high);
            }
        }
        else
        {
            // 排序
            $orderby = [];
            if (isset($param['orderby']))
            {
                foreach ($param['orderby'] as $key => $asc)
                {
                    $orderby[] = "{$key} {$asc}";
                }
            }
            $orderby = 'ORDER BY ' . implode(',', $orderby);

            // 查詢 company 資料
            //                     WHERE `favorite`.`type` = 2
            $sql = "SELECT SQL_CALC_FOUND_ROWS `company`.*, count(*) AS `job_count`
                    FROM  `favorite`
                          INNER JOIN `company`
                                ON `favorite`.`resID` = `company`.`companyID`
                          INNER JOIN `job`
                                ON `job`.`companyID` = `company`.`companyID`
                    GROUP BY companyID
                    $orderby
                    LIMIT $page_start, $page_size";
            // echo "<pre>sql = " . print_r($sql, TRUE). "</pre>";
            $rows  = DB::select($sql);
            $count = DB::select("SELECT FOUND_ROWS() as cnt")[0]->cnt;
            $total_page = ceil($count / $page_size);

            // 查詢 job 資料
            foreach ($rows as $key => $row)
            {
                $jobs = DB::table('job')
                             ->join('company', 'job.companyID', '=', 'company.companyID')
                             ->where('job.companyID', $row->companyID)
                             ->select('job.*')
                             ->get();
                $row->jobs = $jobs;

                /* 轉換資料格式 */
                // 員人工數
                $row->employees = Lib::convert_employees($row->employees);
                // 資本額
                $row->capital   = Lib::number2capital($row->capital);
                // 薪資
                foreach ($jobs as $key => $job_row)
                {
                    $job_row->pay = Lib::convert_pay($job_row->sal_month_low, $job_row->sal_month_high);
                }
            }
        };

        Debug::fblog('Favorite.sql' ,$sql);

        return [
            'count'      => $count,
            'page_size'  => $page_size,
            'curr_page'  => $page,
            'total_page' => $total_page,
            'orderby'    => isset($param['orderby']) ? $param['orderby'] : NULL,
            'rows'       => $rows
        ];
    }

    /**
     * 取得 Company 資料
     * @param  Integer $companyID companyID
     * @return object             data
     */
    public static function get($companyID)
    {
        $rows = DB::table('company')->where('companyID', $companyID)->get();
        return ($rows) ? $rows[0] : NULL;
    }

    /**
     * 取得 1 筆員工人數為 NULL 的公司
     */
    public static function get_null_employees()
    {
        $count      = DB::table('company')->count();
        $null_count = DB::table('company')->whereNull('employees')->count();
        $rows       = DB::table('company')->whereNull('employees')->limit(1)->get();
        if ($count)
        {
            return [
                'count'      => $count,
                'null_count' => $null_count,
                'row'        => (count($rows)) ? $rows[0] : NULL
            ];
        }
        else
        {
            return NULL;
        }
    }
}
