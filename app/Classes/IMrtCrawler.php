<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/24
 * Time: 上午 10:44
 */

namespace App\Classes;

interface IMrtCrawler
{
    /**
     * 開始爬蟲 傳回住址
     * @return mixed
     */
    public function startCraw();
}