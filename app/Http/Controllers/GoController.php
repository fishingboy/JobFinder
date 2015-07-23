<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Lib;
use DB;

/**
 * 轉址器 (為了記錄已讀狀態)
 */
class GoController extends Controller
{
    /**
     * 轉到 104 工作網址
     * @param  string $j_code 104 工作代碼
     */
    public function job($j_code)
    {
        // 記錄已讀
        DB::table('job')->where('j_code', $j_code)->update(['job_readed' => 1]);

        // 轉址
        $url = Lib::get_104_job_url($j_code);
        return redirect()->away($url);
    }

    /**
     * 轉到 104 工作網址
     * @param  string $j_code 104 工作代碼
     */
    public function company($c_code)
    {
        // 記錄已讀
        $r = DB::table('company')->where('c_code', $c_code)->update(['company_readed' => 1]);

        // 轉址
        $url = Lib::get_104_company_url($c_code);
        return redirect()->away($url);
    }
}
