<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Library\Lib;
use App\Models\Job;

/**
* 爬蟲
*/
class Crawler104
{
    /**
     * 爬 104 的公司資訊
     * @param  string $j_code 104 的公司代碼
     * @return array          公司資訊
     */
    public static function get_company($j_code = '')
    {
        $url = "http://www.104.com.tw/jobbank/custjob/index.php?r=cust&j={$j_code}";
        $data = Curl::get_response($url);

        if ($data['status'])
        {
            $start_pos = strpos($data['data'], '<dl>');
            $end_pos = strpos($data['data'], '</dl>');
            $str = substr($data['data'], $start_pos, $end_pos - $start_pos + 1);
            preg_match_all('/<dd>(.*)<\/dd>/', $str, $matches);

            return [
                'employees' => $matches[1][2],
                'capital'   => Lib::capital2number($matches[1][3]),
                'url'       => isset($matches[1][7]) ? $matches[1][7] : NULL,
            ];
        }
        else
        {
            return [
                'employees' => -1,
                'capital'   => -1,
                'url'       => NULL,
            ];
        }
    }
}