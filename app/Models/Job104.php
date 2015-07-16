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
     * 轉換 104 API 來的 JOB DATA
     * @param  object $row api 資料
     * @return array      job 資料表資料
     */
    private function _convert_job_row_data($row)
    {
        return [
            'title'                =>  isset($row->JOB) ? $row->JOB : NULL,
            'j_code'               =>  isset($row->J) ? $row->J : NULL,
            'job_addr_no_descript' =>  isset($row->JOB_ADDR_NO_DESCRIPT) ? $row->JOB_ADDR_NO_DESCRIPT : NULL,
            'job_address'          =>  isset($row->JOB_ADDRESS) ? $row->JOB_ADDRESS : NULL,
            'jobcat_descript'      =>  isset($row->JOBCAT_DESCRIPT) ? $row->JOBCAT_DESCRIPT : NULL,
            'description'          =>  isset($row->DESCRIPTION) ? $row->DESCRIPTION : NULL,
            'period'               =>  isset($row->PERIOD) ? $row->PERIOD : NULL,
            'appear_date'          =>  isset($row->APPEAR_DATE) ? $row->APPEAR_DATE : NULL,
            'dis_role'             =>  isset($row->DIS_ROLE) ? $row->DIS_ROLE : NULL,
            'dis_level'            =>  isset($row->DIS_LEVEL) ? $row->DIS_LEVEL : NULL,
            'dis_role2'            =>  isset($row->DIS_ROLE2) ? $row->DIS_ROLE2 : NULL,
            'dis_level2'           =>  isset($row->DIS_LEVEL2) ? $row->DIS_LEVEL2 : NULL,
            'dis_role3'            =>  isset($row->DIS_ROLE3) ? $row->DIS_ROLE3 : NULL,
            'dis_level3'           =>  isset($row->DIS_LEVEL3) ? $row->DIS_LEVEL3 : NULL,
            'driver'               =>  isset($row->DRIVER) ? $row->DRIVER : NULL,
            'handicompendium'      =>  isset($row->HANDICOMPENDIUM) ? $row->HANDICOMPENDIUM : NULL,
            'role'                 =>  isset($row->ROLE) ? $row->ROLE : NULL,
            'role_status'          =>  isset($row->ROLE_STATUS) ? $row->ROLE_STATUS : NULL,
            's2'                   =>  isset($row->S2) ? $row->S2 : NULL,
            's3'                   =>  isset($row->S3) ? $row->S3 : NULL,
            'sal_month_low'        =>  isset($row->SAL_MONTH_LOW) ? $row->SAL_MONTH_LOW : NULL,
            'sal_month_high'       =>  isset($row->SAL_MONTH_HIGH) ? $row->SAL_MONTH_HIGH : NULL,
            'worktime'             =>  isset($row->WORKTIME) ? $row->WORKTIME : NULL,
            'startby'              =>  isset($row->STARTBY) ? $row->STARTBY : NULL,
            'cert_all_descript'    =>  isset($row->CERT_ALL_DESCRIPT) ? $row->CERT_ALL_DESCRIPT : NULL,
            'jobskill_all_desc'    =>  isset($row->JOBSKILL_ALL_DESC) ? $row->JOBSKILL_ALL_DESC : NULL,
            'pcskill_all_desc'     =>  isset($row->PCSKILL_ALL_DESC) ? $row->PCSKILL_ALL_DESC : NULL,
            'language1'            =>  isset($row->LANGUAGE1) ? $row->LANGUAGE1 : NULL,
            'language2'            =>  isset($row->LANGUAGE2) ? $row->LANGUAGE2 : NULL,
            'language3'            =>  isset($row->LANGUAGE3) ? $row->LANGUAGE3 : NULL,
            'lat'                  =>  isset($row->LAT) ? $row->LAT : NULL,
            'lon'                  =>  isset($row->LON) ? $row->LON : NULL,
            'major_cat_descript'   =>  isset($row->MAJOR_CAT_DESCRIPT) ? $row->MAJOR_CAT_DESCRIPT : NULL,
            'minbinary_edu'        =>  isset($row->MINBINARY_EDU) ? $row->MINBINARY_EDU : NULL,
            'need_emp'             =>  isset($row->NEED_EMP) ? $row->NEED_EMP : NULL,
            'need_emp1'            =>  isset($row->NEED_EMP1) ? $row->NEED_EMP1 : NULL,
            'ondutytime'           =>  isset($row->ONDUTYTIME) ? $row->ONDUTYTIME : NULL,
            'offduty_time'         =>  isset($row->OFFDUTY_TIME) ? $row->OFFDUTY_TIME : NULL,
            'others'               =>  isset($row->OTHERS) ? $row->OTHERS : NULL,
        ];
    }

    /**
     * 轉換 104 API 來的 JOB DATA
     * @param  object $row api 資料
     * @return array      company 資料表資料
     */
    private function _convert_company_row_data($row)
    {
        return [
            'name'             => isset($row->NAME)             ? $row->NAME : NULL,
            'c_code'           => isset($row->C)                ? $row->C : NULL,
            'addr_no_descript' => isset($row->ADDR_NO_DESCRIPT) ? $row->ADDR_NO_DESCRIPT : NULL,
            'address'          => isset($row->ADDRESS)          ? $row->ADDRESS : NULL,
            'addr_indzone'     => isset($row->ADDR_INDZONE)     ? $row->ADDR_INDZONE : NULL,
            'indcat'           => isset($row->INDCAT)           ? $row->INDCAT : NULL,
            'link'             => isset($row->LINK)             ? $row->LINK : NULL,
            'product'          => isset($row->PRODUCT)          ? $row->PRODUCT : NULL,
            'profile'          => isset($row->PROFILE)          ? $row->PROFILE : NULL,
            'welfare'          => isset($row->WELFARE)          ? $row->WELFARE : NULL,
        ];
    }

    /**
     * 從來源更新資料庫
     */
    public function update()
    {
        $url = $this->_get_api_url();
        $json_data = Curl::get_json_data($url);
        foreach ($json_data->data as $row)
        {
            // 寫入 job 資料表
            $job_data = $this->_convert_job_row_data($row);
            Job::insert($job_data);

            // 寫入 company 資料表
            $company_data = $this->_convert_company_row_data($row);
            Company::insert($company_data);
        }

        return view('joblist', ['content' => $content]);
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
