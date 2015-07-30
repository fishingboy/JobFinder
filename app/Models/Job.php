<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Lib;
use App\Library\Debug;
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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job';

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
            return self::$_j_code_list[$j_code];
        }

        // 查詢 job 是否已存在
        $job_rows = DB::table('job')->where('j_code', $j_code)->get();
        $row = ($job_rows) ? $job_rows[0] : NULL;

        // 新增/更新
        if ($row)
        {
            $id = $row->jobID;
            $param['updated_at'] = DB::raw('NOW()');
            DB::table('job')->where('jobID', $id)->update($param);
        }
        else
        {
            $param['created_at'] = DB::raw('NOW()');
            $param['updated_at'] = DB::raw('NOW()');
            $id = DB::table('job')->insertGetId($param);
        }

        // 儲存 j_code
        self::$_j_code_list[$j_code] = $id;

        return $id;
    }

    /**
     * 搜尋
     * @param  array  $param 搜尋條件
     * @return array         job 資料
     */
    public static function search($param = [])
    {
        Debug::fblog('Models\Job.param', $param);

        $page_size = (isset($param['page_size'])) ? intval($param['page_size']) : 50;
        $page      = (isset($param['page'])) ? intval($param['page']) : 1;
        $companyID = (isset($param['companyID'])) ? $param['companyID'] : NULL;

        $obj = DB::table('job')
                 ->join('company', 'job.companyID', '=', 'company.companyID')
                 ->select('company.*', 'job.*');

        // 搜尋
        if (isset($param['keyword']) && $param['keyword'])
        {
            $obj->where('job.description', 'like', "%{$param['keyword']}%");
            $obj->orWhere('job.others', 'like', "%{$param['keyword']}%");
        }

        // 取得總筆數
        $count = $obj->count();

        // 分頁
        $obj->skip(($page -1) * $page_size)
            ->take($page_size);

        // 過瀘公司
        if ($companyID)
        {
            $obj->where('job.companyID', $companyID);
        }

        // 排序
        if (isset($param['orderby']))
        {
            foreach ($param['orderby'] as $key => $asc)
            {
                $obj->orderBy($key, $asc);
            }
        }

        // 取得資料
        $rows       = $obj->get();
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


        return [
            'count'      => $count,
            'page_size'  => $page_size,
            'curr_page'  => $page,
            'total_page' => $total_page,
            'rows'       => $rows,
        ];
    }
}
