<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Curl;
use App\Library\Lib;
use App\Models\Company;

use App\Classes\Crawler104;

/**
 * 爬蟲
 */
class CrawlerController extends Controller
{
    /**
     * 取得公司資訊(員工人數、資本額、網址)
     * @param  Integer $companyID 公司流水號
     * @return Response
     */
    public function get_company($companyID = '1')
    {
        // 取得 Company 資料
        $row = Company::get($companyID);
        if ($row)
        {
            echo "<pre>row = " . print_r($row, TRUE). "</pre>";
            echo Lib::number2capital($row->capital);

            $c_code = $row->c_code;

            // 爬 104 網頁取得資訊
            $data = Crawler104::get_company($c_code);
            if ($data)
            {
                echo "<pre>data = " . print_r($data, TRUE). "</pre>";
                Company::where('companyID', $companyID)->update($data);
            }
            else
            {
                return 'Crawler No Response!';
            }
        }
        else
        {
            return 'No Company Data.';
        }
    }

    /**
     * 更新公司資料(員工人數、資本額)
     */
    public function update_company()
    {
        $company_data = Company::get_null_employees();
        if ($company_data['null_count'] == 0)
        {
            return '更新完畢，沒有需要更新的公司！';
        }

        $companyID = $company_data['row']->companyID;
        $c_code    = $company_data['row']->c_code;

        // 爬 104 網頁取得資訊
        $data = Crawler104::get_company($c_code);
        if ($data)
        {
            // 更新資料庫
            Company::where('companyID', $companyID)->update($data);

            // 完成筆數加 1
            $company_data['null_count']--;

            // 計算完成度
            $company_data['finish_percent'] = number_format(($company_data['count'] - $company_data['null_count']) / $company_data['count'] * 100, 2);

            // 取得爬蟲資訊
            $company_data['employees'] = $data['employees'];
            $company_data['capital']   = Lib::number2capital($data['capital']);
            $company_data['url']       = $data['url'];
        }
        else
        {
            return 'Crawler No Response!';
        }

        $view_data = $company_data;

        // 判斷更新是否要自動跳轉下一頁
        $view_data['go_next_page_js'] = '';
        if ($company_data['null_count'] > 0)
        {
            $next_url = "/crawler/company/update";
            $view_data['go_next_page_js'] = "<script>window.location.href = '{$next_url}';</script>";
        }

        return view('update_company_report', $view_data);
    }
}
