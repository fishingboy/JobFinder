<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class ToolController extends Controller
{
    /**
     * 資料庫清空
     *
     * @return Response
     */
    public function truncate()
    {
        // 清空 Job
        DB::table('job')->truncate();

        // 清空 Company
        DB::table('company')->truncate();

        return "Table job, company is truncated.<br>";
    }

    /**
     * 清除已讀狀態
     *
     * @return Response
     */
    public function clear_readed()
    {
        // 清空 Job
        DB::table('job')->update(['job_readed' => 0]);

        // 清空 Company
        DB::table('company')->update(['company_readed' => 0]);

        return "Table job, company is clear readed.<br>";
    }
}
