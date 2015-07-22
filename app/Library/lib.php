<?php
namespace App\Library;

/**
 * curl library
 */
class Lib
{
    public static function get_104_company_url($c_code)
    {
        return 'http://www.104.com.tw/jobbank/custjob/index.php?j=' . $c_code;
    }
}
