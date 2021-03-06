<?php

namespace App\Models;

use Faker\Provider\DateTime;
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
        DB::enableQueryLog();
        Debug::fblog('Models\Job.param', $param);

        $page_size = (isset($param['page_size'])) ? intval($param['page_size']) : 50;
        $page      = (isset($param['page'])) ? intval($param['page']) : 1;
        $companyID = (isset($param['companyID'])) ? $param['companyID'] : NULL;
        $source    = (isset($param['source'])) ? $param['source'] : NULL;

        $obj = DB::table('job')
                 ->join('company', 'job.companyID', '=', 'company.companyID')
                 ->select('company.*', 'job.*');
        
        // 搜尋
        if (isset($param['keyword']) && $param['keyword'])
        {
            if (strpos($param['keyword'], '&')) {
                $keywords = explode("&", $param['keyword']);
                $obj->where(function ($query) use ($param, $keywords) {
                    foreach ($keywords as $keyword) {
                        $query->where(function ($query) use ($keyword) {
                            $query->where('job.title', 'like', "%{$keyword}%");
                            $query->orWhere('job.description', 'like', "%{$keyword}%");
                            $query->orWhere('job.others', 'like', "%{$keyword}%");
                        });
                    }
                });
            } else {
                $keywords = explode(",", $param['keyword']);
                $obj->where(function ($query) use ($param, $keywords) {
                    $first = true;
                    foreach ($keywords as $keyword) {
                        if ($first) {
                            $query->where(function ($query) use ($keyword) {
                                $query->where('job.title', 'like', "%{$keyword}%");
                                $query->orWhere('job.description', 'like', "%{$keyword}%");
                                $query->orWhere('job.others', 'like', "%{$keyword}%");
                            });
                        } else {
                            $query->orWhere(function ($query) use ($keyword) {
                                $query->where('job.title', 'like', "%{$keyword}%");
                                $query->orWhere('job.description', 'like', "%{$keyword}%");
                                $query->orWhere('job.others', 'like', "%{$keyword}%");
                            });
                        }
                        $first = false;
                    }
                });
            }
        }

        // 排除搜尋
        if (isset($param['not_keyword']) && $param['not_keyword'])
        {
            $not_keywords = explode(",", $param['not_keyword']);
            foreach ($not_keywords as $not_keyword) {
                $obj->where(function ($query) use ($param, $not_keyword) {
                    $query->where('job.title', 'not like', "%{$not_keyword}%");
                    $query->where('job.description', 'not like', "%{$not_keyword}%");
                    $query->where('job.others', 'not like', "%{$not_keyword}%");
                });
            }
        }

        if (isset($param['source']) && $param['source'])
        {
        	$obj->orWhere('job.source', '=', "{$param['source']}");
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

        
//         $queries = DB::getQueryLog();
//        echo "<pre>queries = " . json_encode($queries, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE) . "</pre>\n";
//        dd($obj->toSql());
        
//         dd($queries);
        
        
        /* 轉換資料格式 */
        $rows = self::_convert_row_data($rows);

        return [
            'companyID'  => $companyID,
            'count'      => $count,
            'page_size'  => $page_size,
            'curr_page'  => $page,
            'total_page' => $total_page,
            'orderby'    => isset($param['orderby']) ? $param['orderby'] : NULL,
            'keyword'    => isset($param['keyword']) ? $param['keyword'] : '',
            'not_keyword'    => isset($param['not_keyword']) ? $param['not_keyword'] : '',
            'rows'       => $rows,
        ];
    }

    /**
     * 取得工作地點
     * @param  array  $param 搜尋條件
     * @return array         工作地點資料
     */
    public static function position($param = [])
    {
        DB::enableQueryLog();
        Debug::fblog('Models\Job.param', $param);

        $page_size = (isset($param['page_size'])) ? intval($param['page_size']) : 50;
        $page      = (isset($param['page'])) ? intval($param['page']) : 1;
        $companyID = (isset($param['companyID'])) ? $param['companyID'] : NULL;



        $obj = DB::table('job')
                 ->join('company', 'job.companyID', '=', 'company.companyID')
                 ->groupBy('job.lat', 'job.lon');

        // 計算距離
        if (isset($param['lat']) && $param['lat'] && isset($param['lon']) && $param['lon'])
        {
            $obj->select(DB::raw("job.lat,
                                  job.lon,
                                  COUNT(*) AS job_count,
                                  SQRT(
                                        pow(job.lat - {$param['lat']}, 2) +
                                        pow(job.lon - {$param['lon']}, 2)
                                      ) AS far"));
        }
        else
        {
            $obj->select(DB::raw('job.lat, job.lon, COUNT(*) AS job_count'));
        }


        // 搜尋
        if (isset($param['keyword']) && $param['keyword'])
        {
            $obj->where('job.title', 'like', "%{$param['keyword']}%");
            $obj->orWhere('job.description', 'like', "%{$param['keyword']}%");
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

        // 距離排序
        if (isset($param['lat']) && $param['lat'] && isset($param['lon']) && $param['lon'])
        {
            $obj->orderBy('far', 'asc');
        }

        // 參數排序
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

        $queries = DB::getQueryLog();
        $last_query = end($queries);

        // 顯示 sql
        Debug::fblog('$last_query', $queries);

        /* 取得工作資料 */
        foreach ($rows as $key => $row)
        {
            $job_rows = DB::table('job')
                 ->join('company', 'job.companyID', '=', 'company.companyID')
                 ->where('job.lat', '=', $row->lat)
                 ->where('job.lon', '=', $row->lon)
                 ->select('job.*', 'company.*')
                 ->get();

            $job_rows = self::_convert_row_data($job_rows);
            $row->companies = self::_group_company_data($job_rows);
        }



        return [
            'companyID'  => $companyID,
            'count'      => $count,
            'page_size'  => $page_size,
            'curr_page'  => $page,
            'total_page' => $total_page,
            'orderby'    => isset($param['orderby']) ? $param['orderby'] : NULL,
            'keyword'    => isset($param['keyword']) ? $param['keyword'] : '',
            'rows'       => $rows,
        ];
    }

    /**
     * 轉換 job 資料
     */
    private static function _convert_row_data($rows)
    {
        // 三天內為最新
        $new_limit = new \DateTime();
        $new_limit = $new_limit->sub(new \DateInterval("P3D"))->format("Y-m-d H:i:s");

        foreach ($rows as $key => $row)
        {
            // 員人工數
            $row->employees = Lib::convert_employees($row->employees);
            // 資本額
            $row->capital   = Lib::number2capital($row->capital);
            // 薪資
            $row->pay       = Lib::convert_pay($row->sal_month_low, $row->sal_month_high);
            // 是否為最新
            $row->is_new = ($row->created_at >= $new_limit) ? true : false;
        }
        return $rows;
    }

    /**
     * group 公司資訊
     */
    private static function _group_company_data($rows)
    {
        $companies = [];
        foreach ($rows as $row)
        {
            $data = self::_split_row_data($row);
            if ( ! isset($companies[$row->companyID]))
            {
                $companies[$row->companyID] = $data['company'];
                $companies[$row->companyID]->job_count = 1;
                $companies[$row->companyID]->jobs[] = $data['job'];
            }
            else
            {
                $companies[$row->companyID]->job_count++;
                $companies[$row->companyID]->jobs[] = $data['job'];
            }
        }
        return array_values($companies);
    }

    /**
     * 切割公司及工作資訊
     * @param  array $row 工作及公司資訊
     * @return array      切割完畢的工作及公司資訊
     */
    private static function _split_row_data($row = '')
    {
        $company = $job = [];
        $company_flag = 0;
        foreach ($row as $key => $value)
        {
            if ($key == 'c_code')
            {
                $company_flag = 1;
            }

            if ($key == 'companyID')
            {
                $company[$key] = $value;
            }
            else if ( ! $company_flag || $key == 'pay')
            {
                $job[$key] = $value;
            }
            else
            {
                $company[$key] = $value;
            }
        }

        return [
            'company' => (object) $company,
            'job'     => (object) $job
        ];
    }
}
