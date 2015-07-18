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
}
