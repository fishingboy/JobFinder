<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Models\Job;

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
    private $_ptt_url ='https://www.ptt.cc/bbs/Soft_Job/';
    
    private $_ptt_list_url = [];
    
    private $_content = "";
    
    public $_total_page = 0;
    
    private $_limit = 55;

    /**
     * 從來源更新資料庫
     */
    public function update($condition = [])
    {
    	//先抓第一頁
    	if ($this->_clawer_first_page())
    	{
    		//有頁數後開始往後抓
    		$this->_clawer_prev_job();
    		
    		//開始抓單頁的資料
    		$this->_clawer_job_page();
    		
    	}
    	
        //return view('update_report', ['source' => self::class]);
    }
    
    /**
     * 先抓第一頁中的所有有符合的 url, 及頁數
     * 
     * @param unknown $param
     */
    private function _clawer_first_page()
    {
    	$result = Curl::get_response($this->_ptt_url);
    	
    	if (!$result['status'])
    	{
    		exit("今天不順，抓不到資料");	
    	}
    	
    	$this->_content = $result['data'];
    		
    	$this->_total_page = $this->_find_page_btn($this->_content);
    		
    	$this->_find_job_title($this->_content);
    		
    	return TRUE;
    	
    	
    }
    
    /**
     * 先抓頁面中的所有有符合的 url
     *
     * @param unknown $param
     */
    private function _clawer_prev_job()
    {
    	$limit = $this->_total_page - $this->_limit; 
    	
    	for ($page = $this->_total_page; $page >= $limit; $page--)
    	{
	    	$url_page = 'index' . ((0) ? "" : $page) . '.html';
	    	$url      = $this->_ptt_url . $url_page; 
	    	
	    	$result = Curl::get_response($url);
    		
	    	if (!$result['status'])
	    	{
	    		exit("今天不順，抓不到資料");	
	    	}
    		
	    	$this->_content = $result['data'];
	    	
	    	$this->_find_job_title($this->_content);
	    	
	    	
    	}
    }
    
    
    private function _find_job_title($content = "")
    {
    	$patten = '/.*\<a\ href="\/bbs\/Soft_Job\/(.*).html\"\>\[徵才\]\ (.*)\<\/a\>.*/';
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return FALSE;
    	}
    	
    	$this->_ptt_list_url[] = $this->_ptt_url . $match[1] . '.html';
    	
    	
    }
    
    private function _find_page_btn($content = "")
    {
    	
    	$patten = '/.*href=\"\/bbs\/Soft_Job\/index(\d+).html">\&lsaquo\; 上頁<\/a>.*/';
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return FALSE;
    	}
    	
    	return (int) $match[1];
    	
    	
    }
    
    
    private function _clawer_job_page()
    {
    	if (count($this->_ptt_list_url) == 0 )
    	{
    		return FALSE;
    		
    	}
    	
    	foreach ($this->_ptt_list_url as $url)
    	{
    		$result = Curl::get_response($url);
    		
    		if (!$result['status'])
    		{
    			continue;
    		}
    		
    		//整理格式後寫到 DB
    		$job['title']        = "";
    		$job['content']      = "";
    		$job['company_name'] = "";
    		$job['location']     = "";
    		$job['work_time']    = "";
    		$job['contract']     = "";
    		$job['other']        = "";
    		
    		
    	}
    	
    }

    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }
}
