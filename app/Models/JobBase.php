<?php
namespace App\Models;

/**
* Job 基礎類別
*/
abstract class JobBase
{
	/**
	 * 查詢
	 */
	abstract public function search();

    public static function curl_get_json_data($url)
    {
        $data = self::curl_get_response($url);
        if ($data['status'])
        {
            return json_decode($data['data']);
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
	public static function curl_get_response($url)
	{
		$timeout = 10;
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
		@ curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        @ curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        @ curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

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
