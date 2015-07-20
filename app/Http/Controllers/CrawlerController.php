<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Curl;
use App\Models\Company;

use App\Classes\Crawler104;

/**
 * 爬蟲
 */
class CrawlerController extends Controller
{
    public function get_company($companyID = '1')
    {
        // 取得 Company 資料
        $row = Company::get(1);
        if ($row)
        {
            echo "<pre>row = " . print_r($row, TRUE). "</pre>";

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
}
