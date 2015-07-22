<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Models\Job;
use App\Models\Company;

class JobController extends Controller
{
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
            'page_size' => 10,
            'page'      => 1,
            'orderby' => [
                'sal_month_high'        => 'DESC',
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

    public function test(Request $request)
    {
        // 查詢參數(先寫死)
        $search_param = $this->_get_param($request);

        // 取得查詢資料
        $data = Job::search($search_param);

        // 輸出
        return view('job_test', $data);
    }
}
