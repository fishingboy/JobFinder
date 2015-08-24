<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Models\Job;
use App\Models\Company;

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
    
    private $_limit = 50;

    /**
     * 從來源更新資料庫
     */
    public function update($condition = [])
    {
    	//先抓第一頁
    	if ($this->clawer_first_page())
    	{
    		//有頁數後開始往後抓
    		$this->clawer_prev_job();
    		
    		//開始抓單頁的資料
    		$this->clawer_job_page();
    		
    	}
    	
        //return view('update_report', ['source' => self::class]);
    }
    
    /**
     * 先抓第一頁中的所有有符合的 url, 及頁數
     * 
     * @param unknown $param
     */
    protected function clawer_first_page()
    {
    	$result = Curl::get_response($this->_ptt_url);
    	
    	if (!$result['status'])
    	{
    		exit("今天不順，抓不到資料");	
    	}
    	
    	$this->_content = $result['data'];
    		
    	$this->_total_page = $this->_find_list_page_btn($this->_content);
    		
    	$this->_find_list_title($this->_content);
    		
    	return TRUE;
    	
    	
    }
    
    /**
     * 先抓頁面中的所有有符合的 url
     *
     * @param unknown $param
     */
    protected function clawer_prev_job()
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
	    	
	    	$this->_find_list_title($this->_content);
	    	
	    	
    	}
    }
    
    protected function clawer_job_page()
    {
    	if (count($this->_ptt_list_url) == 0 )
    	{
    		return FALSE;
    
    	}
    	 
    	foreach ($this->_ptt_list_url as  $num =>  $url)
    	{
    		$result = Curl::get_response($url);
    
    		if (!$result['status'])
    		{
    			echo "取不到該筆資料 ". $url;
    			continue;
    		}
    
    		$content = $result['data'];
    
    		//整理格式後寫到 DB
    		$job_data['title']        = $this->_find_job_title($content);
    		
    		if (NULL == $job_data['title'])
    		{
    			echo "該筆抓不到 title " . $url; 
    			continue;
    		}
    		$company_data['name']   = $this->_find_company_name($content);
    		$company_data['c_code'] = $this->_gen_hash_code($company_data['name']);
    		
    		$companyID = Company::insert($company_data);
    		
    		if ($companyID)
    		{
	     		$job_data['description']  = $this->_find_job_description($content);
	     		$job_data['source_url']   = $url;
				$job_data['source']       = 'ptt';
				$job_data['j_code']       = $this->_gen_hash_code($job_data['title']);
				$job_data['companyID']    = $companyID;
				$job_data['post_date']    = $this->_find_postdate($content);
				
				$jobID = Job::insert($job_data);
    		}
     		
    	}
    	
    	exit("為了畢免跑到 view 發生錯誤訊息，先在這裡中斷了");
    	
    	
    	 
    }
    
    private function _gen_hash_code ($str)
    {
    	$num = 0;
    	return $hash_str = md5($str);
    }
    
    private function _find_company_name($content = "")
    {
    	$content = $this->_exchage_content_format($content);
    	
    	$patten[] = '/(.*有限公司).*/';
    	$patten[] = '/(.*顧問公司).*/';
    	$patten[] = '/.*公司名稱：(.*)/';
    	 
    	$content = strip_tags($content);
    	
    	foreach ($patten as $ptn)
    	{
	    	if (preg_match($ptn, $content, $match))
	    	{
	    		break;
	    	}
	    	
	    	
    	}
    	
    	if (!isset($match[1]))
    	{
    		return "來源 PTT";
    	}
    	
    	
    	$rep_patten[] = "/【公司名稱：/";
    	$rep_patten[] = "/公司名稱：/";
    	$rep_patten[] = "/(【.*：)/";
    	
    	$match[1] = trim($match[1]);
    	$match[1] = preg_replace($rep_patten, '', $match[1]);
    	
    	if ($match[1] == "")
    	{
    		return "來源 PTT";
    	}
    	$title = $match[1];
    	 
    	return $title;
    }
    
    private function _find_list_title($content = "")
    {
    	$patten = '/.*\<a\ href="\/bbs\/Soft_Job\/(.*).html\"\>\[徵才\]\ (.*)\<\/a\>.*/';
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return FALSE;
    	}
    	
    	$this->_ptt_list_url[] = $this->_ptt_url . $match[1] . '.html';
    	
    	
    }
    
    private function _find_list_page_btn($content = "")
    {
    	
    	$patten = '/.*href=\"\/bbs\/Soft_Job\/index(\d+).html">\&lsaquo\; 上頁<\/a>.*/';
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return FALSE;
    	}
    	
    	return (int) $match[1];
    	
    }
    
    private function _find_postdate($content = "")
    {
    	$content = $this->_exchage_content_format($content);
    	
    	$patten = '/.*\<span\ class=\"article\-meta\-tag\">時間<\/span><span\ class=\"article-meta-value\">(.*)<\/span><\/div>.*/';
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return NULL;
    	}
    	$match[1] = str_replace('：', ':', $match[1]);
    	
    	$post_date = date("Y-m-d H:i:s", strtotime($match[1]));
    	
    	return  $post_date;
    }
    
    private function _exchage_content_format($content)
    {
    	$patten[] = "/\:/";
    	$patten[] = "/\】/";
    	$patten[] = "/》/";
    	return preg_replace($patten,"：", $content);
    }
    
    private function _find_job_title($content)
    {
    	$content = $this->_exchage_content_format($content);
    	
    	$patten = '/\[徵才\]\ (.*)\ \-\ 看板\ Soft_Job/';

    	
    	if (!preg_match($patten, strip_tags($content), $match))
    	{
    		return NULL;
    	}
    	$title = $match[1];
    	
    	return $title;
    }
    
    
    private function _find_job_description($content)
    {
    	
    	$content = preg_replace('/\s(?=\s)/', '', $content);
    	$content = preg_replace('/[\n\r\t]/', ' ', $content);
    	
    	$patten = '/<span class=\"article\-meta-value\">.*<\/span><\/div>(.*)\<span\ class\=\"f2\"\>.*發信站.*/';
    	
    	
    	if (!preg_match($patten, $content, $match))
    	{
    		return NULL;
    	}
    	
    	$descript = strip_tags($match[1]);
    	 
    	return $descript;
    }
    
    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }
}
