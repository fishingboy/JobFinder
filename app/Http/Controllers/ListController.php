<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Job;
use App\Models\Job104;
use App\Models\JobPtt;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Job104 $job)
    {
        // 更新資料庫
        return $job->update();

        // return $job->search();
        // return "ListController::index";
    }

    public function list_104(Job104 $job)
    {
        # code...
    }

    /**
     * PTT
     *
     * @return Response
     */
    public function ptt_list(JobPtt $job)
    {
        return $job->search();
        // return "ListController::index";
    }

    /**
     * 資料庫測試
     *
     * @return Response
     */
    public function db_test($id=1)
    {
        $rows = User::query();

        echo "<pre>rows = " . print_r($rows, TRUE). "</pre>";
    }
}
