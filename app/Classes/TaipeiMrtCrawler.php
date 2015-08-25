<?php
/**
 * Created by PhpStorm.
 * User: rainlay
 * Date: 2015/8/19
 * Time: 下午 02:53
 */

namespace App\Classes;

use Goutte\Client;
use App\Classes\IMrtCrawler as IMrtCrawler;

class TaipeiMrtCrawler implements IMrtCrawler
{
    protected $indexUrl = "http://www.metro.taipei/";
    protected $stationDetailUrl = "http://web.metro.taipei/c/stationdetail2010.asp";
    protected $client;

    /**
     * mrtCrawler constructor.
     * @param string $url
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    public function startCraw()
    {
        $stations = $this->requestStations();
//        echo "<PRE>";
        foreach ($stations as $index => $lines)
        {
            foreach($lines as $lineName => $stops)
            {
                foreach($stops as $stop)
                {
                    sleep(0.1);
                    $address = $this->requestDetail($stop["id"], $lineName);
                    $tmpRes = [
                        "line" => $lineName,
                        "id" => $stop["id"],
                        "address" => $address,
                        "name" => $stop["name"]
                    ];
                    $result[] = $tmpRes;
                }
            }
        }
//        var_dump($result);
//        echo "</PRE>";

        return $result;
    }

    /**
     * 爬取台北捷運站的站名
     */
    public function requestStations()
    {
        $crawler = $this->client->request('GET', $this->indexUrl);

        /*
         |----------------------------------------------------------------------
         | 將爬到的捷運站，整理成 捷運線 => N個捷運站的格式
         |----------------------------------------------------------------------
         */
        $stations = $crawler->filter('#sstation optgroup')->each(function ($node, $i) {

            $station[$node->attr("label")] = $node->filter('option')->each(function ($node, $i) {
                $eachStop = [
                    "id" => $node->attr('value'),
                    "name" => $node->text()
                ];
                return $eachStop;
            });
            return $station;

        });
//        echo "<PRE>";
//        var_dump($stations);
//        echo "</PRE>";
//        exit();
        return $stations;
    }

//    public function extractStation($crawler)
//    {
//        $stations = $crawler->filter('#sstation optgroup option')->each(function($node, $i){
//            $station = [
//                "id" => $node->attr('value'),
//                "name" => $node->text()
//            ];
//            return $station;
//        });
//
//        return $stations;
//    }

//    public function extractGroup($crawler)
//    {
//        $group = $crawler->filter('#sstation optgroup')->each(function($node, $i){
//            $stationGroup = [
//                "name" => $node->attr("label")
//            ];
//            return $stationGroup;
//        });
//
//        echo "<PRE>";
//        var_dump($group);
//        echo "</PRE>";
//        exit();
//
//        return $group;
//    }

    /**
     * 透過捷運站的ID 向台北捷運站的網頁取得地址
     *
     * @param string $id
     * @return mixed
     */
    public function requestDetail($id = "", $groupName = "")
    {
//        $id = "010";
//        $groupName = "文湖線";
        $crawler = $this->client->request('POST', 'http://web.metro.taipei/c/stationdetail2010.asp',array("ID" => $id));
        $crawler = $crawler->filter('body > div > table > tr')->eq(1)->filter('td')->eq(0)->filter('font')->eq(1);
//        echo '<PRE>';
//        var_dump($crawler);
//        echo '</PRE>';
        $address = $this->explodeAddress($crawler->html(), $groupName);
        return $address;
    }

    public function explodeAddress($address, $groupName)
    {
        $addressArray = explode("<br>", $address);

//        echo $groupName;
//        var_dump($addressArray);
        $pattern = "/" . $groupName .".*：/";
//        echo $pattern;
        foreach($addressArray as $addr)
        {
            if (preg_match($pattern, $addr))
            {
                $result = preg_replace($pattern,"",$addr);
                break;
            }
            else
            {
                $result = $addressArray[0];
            }
        }
        return $result;

    }
}