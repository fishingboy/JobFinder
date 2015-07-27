<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Models\Job;
use App\Models\Company;

/**
 * Job API
 */
class JobController extends Controller
{
    /**
     * 允許查詢的參數
     * @var array
     */
    private $_allow_param = [
        'page_size',
        'page',
        'companyID',
        'orderby'
    ];

    /**
     * 取得 get 查詢欄位
     *
     * @param  array $search_field 可查詢欄位
     * @return array               查詢參數
     */
    private function _get_param(Request $request)
    {
        // 預設值
        $search_param = [
            'page_size' => 20,
            'page'      => 1,
            'orderby' => [
                'sal_month_high'       => 'DESC',
                'sal_month_low'        => 'DESC',
                'period'               => 'DESC',
                'job_addr_no_descript' => 'ASC',
            ]
        ];

        // 取得參數
        foreach ($this->_allow_param as $field)
        {
            $value = $request->input($field);
            if ($value)
            {
                $search_param[$field] = $value;
            }
        }

        return $search_param;
    }

    /**
     * Job API 入口
     * @param  Request $request
     * @param  string  $format  回應格式
     * @return Response
     */
    public function get(Request $request, $format = 'json')
    {
        // 查詢參數(先寫死)
        $search_param = $this->_get_param($request);

        // 取得查詢資料
        $data = Job::search($search_param);
        if ($data)
        {
            $data = array_merge(['status' => TRUE], $data);
        }

        if ($format == 'json')
            return response()->json($data);
        else
            return "<pre>data = " . print_r($data, TRUE). "</pre>";
    }

    /**
     * 取得網址
     * @param  array  $search_param 查詢參數
     * @param  array  $diff_param   要修改的參數
     * @return string               網址
     */
    private function _get_url($search_param, $diff_param = [])
    {
        $param = [];
        foreach ($search_param as $key => $value)
        {
            // 取代參數
            if (array_key_exists($key, $diff_param))
            {
                $search_param[$key] = $diff_param[$key];
            }

            // 組合網址
            if (gettype($search_param[$key]) == 'array')
            {
                foreach ($search_param[$key] as $array_key => $array_value)
                {
                    $param[] = "{$key}[{$array_key}]={$array_value}";
                }
            }
            else
            {
                $param[] = "{$key}={$search_param[$key]}";
            }
        }

        return "/job/test?" . implode('&', $param);
    }

    /**
     * API 測試頁面
     * @param  Request $request Request
     * @param  string  $format  回應格式
     * @return Response
     */
    public function test(Request $request)
    {
        // 查詢參數(先寫死)
        $search_param = $this->_get_param($request);

        // 取得查詢資料
        $data = Job::search($search_param);

        // 取得網址
        $data['url'] = [
            'prev_url'           => $this->_get_url($search_param, ['page' => $data['curr_page'] - 1]),
            'next_url'           => $this->_get_url($search_param, ['page' => $data['curr_page'] + 1]),
            'pay_low_desc_url'   => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['sal_month_low' => 'DESC', 'employees' => 'DESC']]),
            'pay_low_asc_url'    => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['sal_month_low' => 'ASC', 'employees' => 'DESC']]),
            'pay_high_desc_url'  => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['sal_month_high' => 'DESC', 'employees' => 'DESC']]),
            'pay_high_asc_url'   => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['sal_month_high' => 'ASC', 'employees' => 'DESC']]),
            'employees_desc_url' => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['employees' => 'DESC', 'capital' => 'DESC']]),
            'employees_asc_url'  => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['employees' => 'ASC', 'capital' => 'DESC']]),
            'capital_desc_url'   => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['capital' => 'DESC', 'employees' => 'DESC']]),
            'capital_asc_url'    => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['capital' => 'ASC', 'employees' => 'DESC']]),
            'time_desc_url'      => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['job.created_at' => 'DESC', 'employees' => 'DESC']]),
            'time_asc_url'       => $this->_get_url($search_param, ['page' => 1, 'orderby' => ['job.created_at' => 'ASC', 'employees' => 'DESC']]),
        ];

        // 傳入 controller 名稱
        $data['controller'] = self::class;

        // 輸出
        return view('test/job', $data);
    }
}
