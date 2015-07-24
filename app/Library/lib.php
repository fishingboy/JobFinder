<?php
namespace App\Library;

/**
 * curl library
 */
class Lib
{
    /**
     * 取得 104 公司網址
     * @param  string $c_code 104 公司代碼
     * @return string         104 公司網址
     */
    public static function get_104_company_url($c_code)
    {
        return 'http://www.104.com.tw/jobbank/custjob/index.php?j=' . $c_code;
    }

    /**
     * 取得 104 工作網址
     * @param  string $j_code 104 工作代碼
     * @return string         104 工作網址
     */
    public static function get_104_job_url($j_code)
    {
        return 'http://www.104.com.tw/jobbank/custjob/index.php?r=job&j=' . $j_code;
    }
    /**
     * 將資本額轉成數字
     * @param  string $str 資本額中文
     * @return Integer     資本額的數字
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

    /**
     * 將資本額數字轉成中文
     * @param  integer $number 數字資本額
     * @return string          中文資本額
     */
    public static function number2capital($number)
    {
        if ($number == 0)
        {
            return '暫不提供';
        }

        $money = '';
        $e = 10000 * 10000;
        $w = 10000;

        if ($number >= $e)
        {
            $n = intval($number / $e);
            $number -= $n * $e;
            $money .= $n . '億';
        }

        if ($number >= $w)
        {
            $n = intval($number / $w);
            $money .= $n . '萬';
        }

        return $money;
    }

    /**
     * 把薪資範圍轉成比較友善的格式
     * @param  integer $low    薪資下限
     * @param  integer $height 薪資上限
     * @return string          薪資
     */
    public static function convert_pay($low, $height)
    {
        if ($low == 0 && $height == 0)
        {
            return '面議';
        }
        else
        {
            $low    = number_format($low / 1000) . 'K';
            $height = number_format($height / 1000) . 'K';
            return "{$low} ~ {$height}";
        }
    }

    /**
     * 轉換員工人數
     * @param  integer $employees 員工人數
     * @return string             易於閱讀的格式
     */
    public static function convert_employees($employees)
    {
        return ($employees == 0) ? '暫不提供' : number_format($employees);
    }
}
