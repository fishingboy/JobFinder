<?php namespace App\Models;

use App\Models\JobBase;
use App\Library\Curl;

/**
* Job 104
*/
class Job104 extends JobBase
{
    public function __construct()
    {
        define('JSON_DIR', __DIR__ . '/../../resources/json/');
    }

    /**
     * 104 API 網址
     * @var string
     */
    private $_api_url = 'http://www.104.com.tw/i/apis/jobsearch.cfm';

    /**
     * API 參數
     * @var string
     */
    private $_api_params = 'cat=2007001006&role=1,4&fmt=8';

    /**
     * @return string url
     */
    private function _get_api_url()
    {
        return $this->_api_url . '?' . $this->_api_params;
    }

    /**
     * 從來源更新資料庫
     */
    public function update()
    {

    }

    /**
     * 搜尋
     */
    public function search()
    {
        $url = $this->_get_api_url();
        // $content = $this->show_category();
        $content = Curl::get_json_data($url);
        $content = "<pre>" . print_r($content, TRUE) . "</pre>";
        return view('joblist', ['content' => $content]);
    }

    public function show_category()
    {
        $file = JSON_DIR . '104/category.json';
        $content = file_get_contents($file);
        return "<pre>" .  print_r(json_decode($content), TRUE). "</pre>";
    }
}
