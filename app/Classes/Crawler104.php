<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Models\Job;

/**
* 爬蟲
*/
class Crawler104
{
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
                'employees' => intval($matches[1][2]),
                'capital'   => $matches[1][3],
                'url'       => $matches[1][7],
            ];
        }
        else
        {
            return NULL;
        }
    }
}