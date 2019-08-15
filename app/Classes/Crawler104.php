<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Library\Lib;
use App\Models\Job;
use Exception;

/**
* 爬蟲
*/
class Crawler104
{
    public static function get_company($j_code = '')
    {
        return self::get_company_2019($j_code);
    }

    /**
     * 爬 104 的公司資訊 (舊版網頁)
     * @deprecated
     * @param  string $j_code 104 的公司代碼
     * @return array          公司資訊
     */
    public static function get_company_old($j_code = '')
    {
        $url = "https://www.104.com.tw/jobbank/custjob/index.php?r=cust&j={$j_code}";
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

    /**
     * 爬 104 的公司資訊 (舊版網頁)
     * @param  string $j_code 104 的公司代碼
     * @return array          公司資訊
     */
    public static function get_company_2019($j_code = '')
    {
        $url = "https://www.104.com.tw/jobbank/custjob/index.php?r=cust&j={$j_code}";
        $data = Curl::get_response($url);

        if ( ! $data['status'])
        {
            return [
                'employees' => -1,
                'capital'   => -1,
                'url'       => NULL,
            ];
        }

        // 找公司新網址
        $response = $data['data'];
        preg_match_all('/<meta property="og:url" content="(.*)">/', $response, $matches);

        if ( ! isset($matches[1][0])) {
            throw new Exception("找不到公司的網址。");
        }
        $company_new_url = $matches[1][0];

        // 找公司代碼
        $tmp = explode("/", $company_new_url);
        $company_code = $tmp[count($tmp)-1];

        // 呼叫 api
        $api_url = "https://www.104.com.tw/company/ajax/content/{$company_code}?";
        $data = Curl::get_response($api_url);

        if ( ! $data['status'])
        {
            return [
                'employees' => -1,
                'capital'   => -1,
                'url'       => NULL,
            ];
        }
        $company_data = json_decode($data['data'], true);

        return [
            'employees' => intval($company_data['data']['empNo']),
            'capital'   => Lib::capital2number($company_data['data']['capital']),
            'url'       => $company_new_url,
        ];
    }
}