<?php namespace App\Models;

use App\Models\JobBase;

/**
* Job 104
*/
class JobPtt extends JobBase
{
    /**
     * 104 API 網址
     * @var string
     */
    private $_url =
    [
        'https://www.ptt.cc/bbs/Soft_Job/index.html'
    ];

    public function search()
    {
        $content = self::curl_get_response($this->_url[0]);
        $content = ($content['status']) ? $content['data'] : NULL;
        return view('joblist', ['content' => $content]);
    }
}
