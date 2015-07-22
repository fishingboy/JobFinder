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
    public function get(Request $request, $format = 'json')
    {
        // 取得查詢參數
        $search_param = [];

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
}
