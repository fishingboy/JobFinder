<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2015/8/24
 * Time: ¤W¤È 10:19
 */

namespace app\Services;

use App\Classes\IMrtCrawler;

class MrtService
{

    /**
     * MrtService constructor.
     */
    public function __construct(IMrtCrawler $mrtCrawler)
    {
        $mrtCrawler->startCraw();
    }

}