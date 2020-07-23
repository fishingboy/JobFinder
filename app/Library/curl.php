<?php
namespace App\Library;

/**
 * curl library
 */
class Curl
{
    public static function get_json_data($url, $param = [], $method='GET')
    {
        $data = self::get_response($url, $param);
        if ($data['status'])
        {
            return json_decode($data['data'], TRUE);
        }
        else
        {
            return NULL;
        }
    }

    /**
     * curl 呼叫
     *
     * @param  string url
     * @return string data
     */
    public static function get_response($url, $param = [], $method='GET', $referer = "")
    {
        $timeout = 100;
        $curl = curl_init($url);
        if (substr($url, 0, 5) == "https")
        {
            @ curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            @ curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
            @ curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        else
        {
            @ curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP);
        }

        // 傳遞資料
        if (count($param))
        {
            @ curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  $method);
            @ curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }

        @ curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        @ curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        @ curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

        if ($referer) {
            @ curl_setopt($curl, CURLOPT_REFERER, $referer);
        }

        $data = curl_exec($curl);

        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($response_code != "200")
        {
            return ['status' => FALSE, 'response_code' => $response_code];
        }

        return ['status' => TRUE, 'data' => $data];;
    }
}
