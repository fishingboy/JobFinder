<?php
/**
 * User: Rain
 * Date: 2015/8/20
 * Time: ¤U¤È 03:07
 */

namespace app\Library;

use App\Library\Curl;


class GoogleGeocoding
{
    protected $apiKey = "AIzaSyAb8QX9Z37EXAlwYwI2a8rE3q_Ci_87kGo";
    protected $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json";

    public function addressToGeocode($address = "")
    {
        sleep(1.5);
        $data = [
            "address" => $address,
            "key" => $this->apiKey
        ];

        $queryString = http_build_query($data);
        $url = $this->apiUrl . "?" . $queryString;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL , $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST , 0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER , 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER , 1);

        $response = curl_exec($ch);

        curl_close($ch);

        $geoData = json_decode($response,true);

        if($geoData["status"] === "OK")
        {
            return $this->extractLocation($geoData);
        }
        else
        {
            return false;
        }
    }

    public function extractLocation($data)
    {
        return $data["results"][0]["geometry"]["location"];
    }

}