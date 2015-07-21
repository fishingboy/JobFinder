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
    /**
     * 將資本額轉成數字
     * @param  string $str 資料額
     * @return Integer     資料額的數字
     */
    public static function capital2number($str)
    {
        $str = str_replace('元', '', $str);
        $str = str_replace('萬', '', $str);
        $str = str_replace('億', 'e', $str);
        $tmp = explode('e', $str);
        if (count($tmp) == 1)
            $money = $tmp[0] * 10000;
        else
            $money = $tmp[0] * 10000 * 10000 + $tmp[1] * 10000;

        return $money;
    }

    public static function number2capital($number)
    {
        if ($number == 0)
        {
            return '暫不提供';
        }

        $money = '';
        $e = 10000 * 10000;
        $w = 10000;

        if ($number > $e)
        {
            $n = intval($number / $e);
            $number -= $n * $e;
            $money .= $n . '億';
        }

        if ($number > $w)
        {
            $n = intval($number / $w);
            $money .= $n . '萬';
        }

        return $money;
    }

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
                'capital'   => self::capital2number($matches[1][3]),
                'url'       => isset($matches[1][7]) ? $matches[1][7] : NULL,
            ];
        }
        else
        {
            return NULL;
        }
    }
}