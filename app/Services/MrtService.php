<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2015/8/24
 * Time: ¤W¤È 10:19
 */

namespace app\Services;

use App\Classes\IMrtCrawler;
use App\Library\GoogleGeocoding;
class MrtService
{

    protected $mrtCrawler;
    protected $geoLib;

    /**
     * MrtService constructor.
     */
    public function __construct(IMrtCrawler $mrtCrawler)
    {
        $this->mrtCrawler = $mrtCrawler;
        $this->geoLib = new GoogleGeocoding();
    }

    public function getLocationWithGeo()
    {
        set_time_limit(900);

        $stations = $this->mrtCrawler->startCraw();
        echo "<PRE>";
        foreach ($stations as $index => $stop)
        {
            $location = $this->geoLib->addressToGeocode($stop["address"]);
            $stations[$index]["lat"] = $location["lat"];
            $stations[$index]["lng"] = $location["lng"];
        }
        var_export($stations);
        echo "</PRE>";
//        $mrtCrawler = new TaipeiMrtCrawler();
//
//        $stations = $mrtCrawler->startCraw();
//
//        exit();
////        $stations = $this->getMrtDummyData();
//
//        $geo = new GoogleGeocoding();
//
//        foreach ($stations as $index => $stop)
//        {
//            $location = $geo->addressToGeocode($stop["address"]);
//            $stations[$index]["lat"] = $location["lat"];
//            $stations[$index]["lng"] = $location["lng"];
//        }
//        echo "<PRE>";
//        var_dump($stations);
//        echo "</PRE>";
    }

}