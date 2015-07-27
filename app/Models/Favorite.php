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
            $sql = "SELECT SQL_CALC_FOUND_ROWS
                           `favorite`.`favoriteID`,
                           `job`.*,
                           `company`.*
                    FROM  `favorite`
                          INNER JOIN `job`
                                ON `favorite`.`resID` = `job`.`jobID`
                          INNER JOIN `company`
                                ON `job`.`companyID` = `company`.`companyID`
                    WHERE `favorite`.`type` = 1
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
            $sql = "SELECT SQL_CALC_FOUND_ROWS
                           `favorite`.`favoriteID`,
                           `company`.*,
                           count(*) AS `job_count`
                    FROM  `favorite`
                          INNER JOIN `company`
                                ON `favorite`.`resID` = `company`.`companyID`
                          INNER JOIN `job`
                                ON `job`.`companyID` = `company`.`companyID`
                    WHERE `favorite`.`type` = 2
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
     * 排序
     */
    public static function sort($param = [])
    {
        if ( ! isset($param['id']) || ! isset($param['sn']))
        {
            Debug::fblog('Sort Param Error!!!');
            return FALSE;
        }

        $id = $param['id'];
        $sn = $param['sn'];
        $type = 1;

        // 查詢 favorite
        $rows = DB::table('favorite')->where('favoriteID', $id)->get();
        if (count($rows) == 0)
        {
            Debug::fblog("No Such Record (favorite.favoriteID={$id})!!!");
            return FALSE;
        }
        $row = $rows[0];
        Debug::fblog('favorite.sort.row', $row);

        $type = $row->type;

        // 如果是往後排序的話，sn 要加 1
        Debug::fblog("({$sn} * 10) > {$row->sn}");
        if (($sn * 10) > $row->sn)
        {
            Debug::fblog("sn = sn +1");
            $sn++;
        }

        // 插入到兩點之間
        Debug::fblog('sort', "sort({$id}, {$sn})");

        $sn = intval($sn . '0') + 5;
        $sql = "UPDATE `favorite`
                SET sn=:sn
                WHERE favoriteID=:id
                      AND type=:type";
        $data = [
            'sn'   => $sn,
            'id'   => $id,
            'type' => $type
        ];
        $r = DB::update($sql, $data);

        Debug::fblog('sort.sql', $sql);
        Debug::fblog('sort.data', $data);
        Debug::fblog('sort.r', $r);

        // 重整順序
        self::rebuild_sn($type);
        return TRUE;
    }

    /**
     * 重新整理順序
     */
    public static function rebuild_sn($type = 1)
    {
        $sql = "UPDATE favorite as F,
                       (
                            SELECT favoriteID, (@rownum := @rownum + 10) as rownum
                            FROM favorite, (SELECT @rownum :=0) as R
                            WHERE type=:type
                            ORDER BY sn ASC, favoriteID ASC
                       ) as SN
                SET F.sn = SN.rownum
                WHERE F.favoriteID=SN.favoriteID";
        Debug::fblog('rebuild_sn.sql', $sql, [':type' => $type]);
        DB::update($sql, [':type' => $type]);
        return TRUE;
    }
}
