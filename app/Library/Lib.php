<?php
namespace App\Library;

use Exception;

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
        return 'https://www.104.com.tw/jobbank/custjob/index.php?j=' . $c_code;
    }

    /**
     * 取得 104 工作網址
     * @param  string $j_code 104 工作代碼
     * @return string         104 工作網址
     */
    public static function get_104_job_url($j_code)
    {
        return 'https://www.104.com.tw/jobbank/custjob/index.php?r=job&j=' . $j_code;
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
            return ($low == $height) ? $low : "{$low} ~ {$height}";
        }
    }

    /**
     * 轉換員工人數
     * @param  integer $employees 員工人數
     * @return string             易於閱讀的格式
     */
    public static function convert_employees($employees)
    {
        return ($employees == 0) ? '暫不提供' : number_format((int) $employees);
    }

    /**
     * 取得查詢條件
     * @return array
     * @throws Exception
     */
    public static function get_conditions()
    {
        // 查詢條件預設值
//        $conditions = [
//            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
//            'area' => ['6001001000', '6001002000'],
//            'role' => [1, 4],
//            'exp'  => 7,
//            'kws'  => 'php python',
//            'kwop' => 3,
//        ];

        // 從 json 取得查詢條件
        $json_file = public_path()."/../resources/json/condition.json";
        if (file_exists($json_file))
        {
            $json = file_get_contents($json_file);
            $data = json_decode($json, TRUE);

            if ($data)
            {
                $conditions = $data;
            }
            else
            {
                throw new Exception("JSON 格式壞了！請檢查一下");
            }
        } else {
            throw new Exception("找不到設定檔 [$json_file]。");
        }

        return $conditions;
    }
}
