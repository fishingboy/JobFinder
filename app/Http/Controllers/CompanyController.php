<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Models\Job;
use App\Models\Company;
use App\Library\Debug;


class CompanyController extends Controller
{

    public function get(Request $request, $format = 'json')
    {
        // 查詢參數(先寫死)
        $search_param = [
            'page_size' => 50,
            'page'      => 3,
            'orderby' => [
                'employees' => 'DESC',
                'job_count' => 'DESC',
                'capital'   => 'DESC'
            ]
        ];

        // 取得查詢資料
        $data = Company::search($search_param);
        if ($data)
        {
            $data = array_merge(['status' => TRUE], $data);
        }

        if ($format == 'json')
            return response()->json($data);
        else
        {
            Debug::fblog($data);
            return "<pre>data = " . print_r($data, TRUE). "</pre>";
        }
    }
}
