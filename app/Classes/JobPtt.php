<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;

/**
* Job PTT
*/
class JobPtt extends JobBase
{
    /**
     * 允許搜尋的欄位
     *
     * @var array
     */
    protected $_allow_search_field = [
        'keyword',
    ];

    /**
     * 104 API 網址
     * @var string
     */
    private $_url =
    [
        'https://www.ptt.cc/bbs/Soft_Job/index.html'
    ];

    /**
     * 從來源更新資料庫
     */
    public function update()
    {

    }

    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }
}
