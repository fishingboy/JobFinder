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
     * 建立 job 實體
     * @param  string $source 來源
     * @return object         job 實體
     */
    private function _create_job($source = '104')
    {
        switch ($source)
        {
            case 'ptt':
                echo 'ptt';
                $job = new JobPtt();
                break;

            case '104':
                echo '104';
                $job = new Job104();
            default:
                break;
        }
        return $job;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($source = '104')
    {
        // 取得 job 實體
        $job = $this->_create_job($source);

        return $job->search();
        // return "ListController::index";
    }

    public function update($source = '104')
    {
        // 取得 job 實體
        $job = $this->_create_job($source);

        // 更新資料庫
        return $job->update();
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
