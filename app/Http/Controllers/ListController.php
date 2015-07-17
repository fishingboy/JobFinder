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
                $job = new JobPtt();
                break;

            case '104':
            default:
                $job = new Job104();
                break;
        }
        return $job;
    }

    /**
     * 顯示工作列表
     *
     * @return Response
     */
    public function index($source = '104')
    {
        // 取得 job 實體
        $job = $this->_create_job($source);

        // 取得查詢資料
        $data = $job->search();

        // 畫面輸出
        $content = "<pre>data = " . print_r($data, TRUE). "</pre>";
        return view('joblist', ['content' => $content]);
    }

    /**
     * 從來源更新工作資料庫
     * @param  string $source 來源類別
     * @return Response
     */
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
