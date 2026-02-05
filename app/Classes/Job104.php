<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Library\Curl;
use App\Library\Debug;
use App\Models\Company;
use App\Models\Job;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;


/**
* Job 104
*/
class Job104 extends JobBase
{
    /**
     * 是否為預灠模式(不做資料匯入，只顯示 104 條件及資訊)
     * @var boolean
     */
    private $_preview_mode = FALSE;

    public function __construct()
    {
        if (!defined('JSON_DIR')) {
            define('JSON_DIR', __DIR__ . '/../../resources/json/');
        }
    }

    /**
     * 允許搜尋的欄位
     *
     * @var array
     */
    protected $_allow_search_field = [
        'keyword',
        'cat',
        'area',
        'ind',
        'major',
        'comp',
        'jskill',
        'cert',
    ];

    /**
     * 104 搜尋頁面網址
     * @var string
     */
    private $_search_url = 'https://www.104.com.tw/jobs/search/';

    /**
     * 104 職缺詳細 API 網址
     * @var string
     */
    private $_job_detail_api_url = 'https://www.104.com.tw/job/ajax/content/';

    /**
     * Selenium WebDriver 網址
     * @var string
     */
    private $_selenium_url = 'http://dev_chrome:4444';

    /**
     * 呼叫 104 API 的查詢條件
     * @var array
     */
    private $_update_conditions = [
        'cat'  => '2007001006',
        'role' => [1, 4],
    ];

    /**
     * 組合搜尋頁面的 URL
     * @param array $conditions 查詢條件
     * @param int $page 頁碼
     * @return string 完整搜尋 URL
     */
    private function _buildSearchUrl($conditions, $page = 1)
    {
        $params = [];

        // 關鍵字：condition.json 用 kws，搜尋頁用 keyword
        if (isset($conditions['kws'])) {
            $params['keyword'] = $conditions['kws'];
        }
        if (isset($conditions['keyword'])) {
            $params['keyword'] = $conditions['keyword'];
        }

        // 職務類別：condition.json 用 cat，搜尋頁用 jobcat
        if (isset($conditions['cat'])) {
            $cat = is_array($conditions['cat']) ? implode(',', $conditions['cat']) : $conditions['cat'];
            $params['jobcat'] = $cat;
        }

        // 地區
        if (isset($conditions['area'])) {
            $area = is_array($conditions['area']) ? implode(',', $conditions['area']) : $conditions['area'];
            $params['area'] = $area;
        }

        // 角色
        if (isset($conditions['role'])) {
            $role = is_array($conditions['role']) ? implode(',', $conditions['role']) : $conditions['role'];
            $params['ro'] = $role;
        }

        // 經歷
        if (isset($conditions['exp'])) {
            $params['expmin'] = $conditions['exp'];
        }

        // 關鍵字搜尋模式
        if (isset($conditions['kwop'])) {
            $params['kwop'] = $conditions['kwop'];
        }

        // 排序
        if (!isset($params['order'])) {
            $params['order'] = 12; // 依日期排序
        }
        if (!isset($params['asc'])) {
            $params['asc'] = 0; // 降冪
        }

        // 每頁筆數
        $params['pagesize'] = isset($conditions['pgsz']) ? $conditions['pgsz'] : 20;

        // 頁碼
        $params['page'] = $page;

        return $this->_search_url . '?' . http_build_query($params);
    }

    /**
     * 設定更新過瀘條件
     * @param array $conditions 更新時的查詢條件
     */
    public function _set_update_condition($conditions = NULL)
    {
        $this->_update_conditions = $conditions;
    }

    /**
     * 用 Selenium 從搜尋頁面取得 Job ID 列表
     * @param array $conditions 查詢條件
     * @param int $page 頁碼
     * @return array ['job_ids' => [...], 'total_count' => int, 'total_page' => int]
     */
    private function _getJobIdsFromSearchPage($conditions, $page = 1)
    {
        $url = $this->_buildSearchUrl($conditions, $page);

        $driver = null;
        try {
            // 連線到 Selenium
            $capabilities = DesiredCapabilities::chrome();
            $driver = RemoteWebDriver::create($this->_selenium_url, $capabilities);

            // 開啟搜尋頁面
            $driver->get($url);

            // 等待職缺列表渲染（等待 article 標籤出現，104 用 article 包住每筆職缺）
            $wait = new WebDriverWait($driver, 15);
            $wait->until(
                WebDriverExpectedCondition::presenceOfElementLocated(
                    WebDriverBy::cssSelector('article.b-block--top-bord, article.job-list-item, div.job-list-container')
                )
            );

            // 額外等待確保 Vue 渲染完成
            sleep(2);

            // 取得總筆數
            $totalCount = 0;
            $totalPage = 1;
            try {
                // 嘗試從頁面取得總筆數資訊
                $countElements = $driver->findElements(
                    WebDriverBy::cssSelector('.filter-label span, .job-list-summary .t3, div[data-v] .t3')
                );
                foreach ($countElements as $el) {
                    $text = $el->getText();
                    if (preg_match('/(\d[\d,]+)\s*個/u', $text, $m)) {
                        $totalCount = intval(str_replace(',', '', $m[1]));
                        break;
                    }
                }

                // 計算總頁數
                $pageSize = isset($conditions['pgsz']) ? $conditions['pgsz'] : 20;
                if ($totalCount > 0) {
                    $totalPage = ceil($totalCount / $pageSize);
                }
            } catch (\Exception $e) {
                // 忽略計數錯誤
            }

            // 取得所有職缺連結，解析 job ID
            $jobIds = [];
            $links = $driver->findElements(WebDriverBy::cssSelector('a[href*="/job/"]'));
            foreach ($links as $link) {
                $href = $link->getAttribute('href');
                // 從連結中取得 job ID，格式如 /job/xxxxx 或 https://www.104.com.tw/job/xxxxx
                if (preg_match('/\/job\/([a-zA-Z0-9_]+)/', $href, $matches)) {
                    $jobId = $matches[1];
                    // 排除非職缺頁面的連結（如 ajax, search 等）
                    if (!in_array($jobId, ['ajax', 'search', 'bank']) && strlen($jobId) > 3) {
                        $jobIds[$jobId] = $jobId;
                    }
                }
            }

            $jobIds = array_values($jobIds);

            $driver->quit();

            return [
                'job_ids'     => $jobIds,
                'total_count' => $totalCount,
                'total_page'  => $totalPage,
                'search_url'  => $url,
            ];

        } catch (\Exception $e) {
            if ($driver) {
                try { $driver->quit(); } catch (\Exception $qe) {}
            }
            throw new \Exception("Selenium 抓取失敗: " . $e->getMessage());
        }
    }

    /**
     * 用 Curl 取得單一職缺的詳細資料
     * @param string $jobId 職缺 ID
     * @return array|null JSON 資料
     */
    private function _getJobDetail($jobId)
    {
        $url = $this->_job_detail_api_url . $jobId;
        $referer = "https://www.104.com.tw/job/" . $jobId;

        $response = Curl::get_response($url, [], 'GET', $referer);

        if ($response['status']) {
            return json_decode($response['data'], TRUE);
        }

        return null;
    }

    /**
     * 將新 API 的 JSON 欄位對應到現有 job DB 欄位
     * @param array $data API 回傳的 JSON 資料
     * @param string $jobId 職缺 ID
     * @return array job 資料表資料
     */
    private function _convertNewApiJobData($data, $jobId)
    {
        $header = isset($data['data']['header']) ? $data['data']['header'] : [];
        $jobDetail = isset($data['data']['jobDetail']) ? $data['data']['jobDetail'] : [];
        $condition = isset($data['data']['condition']) ? $data['data']['condition'] : [];

        // 薪資
        $salaryLow = null;
        $salaryHigh = null;
        if (isset($jobDetail['salary'])) {
            $salaryLow = isset($jobDetail['salary']['min']) ? $jobDetail['salary']['min'] : null;
            $salaryHigh = isset($jobDetail['salary']['max']) ? $jobDetail['salary']['max'] : null;
        }
        if (isset($header['salary'])) {
            // 嘗試從 header 中的月薪文字解析
            if (preg_match('/([\d,]+)\s*[~～至]\s*([\d,]+)/u', $header['salary'], $m)) {
                $salaryLow = $salaryLow ?: intval(str_replace(',', '', $m[1]));
                $salaryHigh = $salaryHigh ?: intval(str_replace(',', '', $m[2]));
            }
        }

        // 工作描述
        $description = isset($jobDetail['jobDescription']) ? $jobDetail['jobDescription'] : null;

        // 其他條件
        $others = isset($condition['other']) ? $condition['other'] : null;

        // 工作地址
        $jobAddress = null;
        $jobAddrNoDescript = null;
        if (isset($jobDetail['addressRegion'])) {
            $jobAddrNoDescript = $jobDetail['addressRegion'];
        }
        if (isset($jobDetail['address'])) {
            $jobAddress = is_array($jobDetail['address'])
                ? implode('', $jobDetail['address'])
                : $jobDetail['address'];
        }
        // 有時候地址資訊在 header
        if (!$jobAddress && isset($header['jobAddress'])) {
            $jobAddress = $header['jobAddress'];
        }

        // 職務類別
        $jobcatDescript = '';
        if (isset($jobDetail['jobCategory']) && is_array($jobDetail['jobCategory'])) {
            $cats = array_map(function ($c) {
                return isset($c['description']) ? $c['description'] : '';
            }, $jobDetail['jobCategory']);
            $jobcatDescript = implode('、', array_filter($cats));
        }

        // 經歷
        $period = null;
        if (isset($condition['workExp'])) {
            if (preg_match('/(\d+)/', $condition['workExp'], $m)) {
                $period = intval($m[1]);
            }
        }

        // 上班時間
        $ondutyTime = null;
        $offdutyTime = null;
        if (isset($jobDetail['workPeriod'])) {
            $workPeriod = $jobDetail['workPeriod'];
            if (preg_match('/(\d{1,2}:\d{2})/', $workPeriod, $m)) {
                $ondutyTime = $m[1];
            }
            if (preg_match('/\d{1,2}:\d{2}.*?(\d{1,2}:\d{2})/', $workPeriod, $m)) {
                $offdutyTime = $m[1];
            }
        }

        // 出現日期
        $appearDate = isset($header['appearDate']) ? $header['appearDate'] : null;

        // 學歷
        $edu = isset($condition['edu']) ? $condition['edu'] : null;

        // 需要人數
        $needEmp = isset($header['needEmp']) ? $header['needEmp'] : null;

        // 技能
        $skills = '';
        if (isset($condition['skill']) && is_array($condition['skill'])) {
            $skillNames = array_map(function ($s) {
                return isset($s['description']) ? $s['description'] : '';
            }, $condition['skill']);
            $skills = implode('、', array_filter($skillNames));
        }

        // 語言
        $languages = [null, null, null];
        if (isset($condition['language']) && is_array($condition['language'])) {
            foreach ($condition['language'] as $i => $lang) {
                if ($i >= 3) break;
                $languages[$i] = isset($lang['language']) ? $lang['language'] : null;
            }
        }

        // 經緯度
        $lat = isset($jobDetail['lat']) ? $jobDetail['lat'] : null;
        $lon = isset($jobDetail['lon']) ? $jobDetail['lon'] : null;

        return [
            'title'                => isset($header['jobName']) ? $header['jobName'] : null,
            'j_code'               => $jobId,
            'job_addr_no_descript'  => $jobAddrNoDescript,
            'job_address'          => $jobAddress,
            'jobcat_descript'      => $jobcatDescript ?: null,
            'description'          => $description,
            'period'               => $period,
            'appear_date'          => $appearDate,
            'sal_month_low'        => $salaryLow,
            'sal_month_high'       => $salaryHigh,
            'worktime'             => isset($jobDetail['workType']) ? $jobDetail['workType'] : null,
            'startby'              => isset($jobDetail['startBy']) ? $jobDetail['startBy'] : null,
            'jobskill_all_desc'    => $skills ?: null,
            'language1'            => $languages[0],
            'language2'            => $languages[1],
            'language3'            => $languages[2],
            'lat'                  => $lat,
            'lon'                  => $lon,
            'minbinary_edu'        => $edu,
            'need_emp'             => $needEmp,
            'ondutytime'           => $ondutyTime,
            'offduty_time'         => $offdutyTime,
            'others'               => $others,
            'source'               => '104',
            'source_url'           => "https://www.104.com.tw/job/" . $jobId,
        ];
    }

    /**
     * 將新 API 的公司欄位對應到現有 company DB 欄位
     * @param array $data API 回傳的 JSON 資料
     * @return array company 資料表資料
     */
    private function _convertNewApiCompanyData($data)
    {
        $company = isset($data['data']['company']) ? $data['data']['company'] : [];

        // 公司代碼
        $cCode = null;
        if (isset($company['custNo'])) {
            $cCode = $company['custNo'];
        } elseif (isset($company['link'])) {
            // 從公司連結取得代碼，格式如 //www.104.com.tw/company/xxxxx
            if (preg_match('/\/company\/([a-zA-Z0-9_]+)/', $company['link'], $m)) {
                $cCode = $m[1];
            }
        }

        // 地址
        $address = isset($company['addr']) ? $company['addr'] : null;
        $addrNoDescript = isset($company['addrNoDesc']) ? $company['addrNoDesc'] : null;

        // 產業類別
        $indcat = isset($company['industry']) ? $company['industry'] : null;

        // 公司連結
        $link = isset($company['link']) ? $company['link'] : null;
        if ($link && strpos($link, 'http') !== 0) {
            $link = 'https:' . $link;
        }

        // 產品/服務
        $product = isset($company['product']) ? $company['product'] : null;

        // 公司簡介
        $profile = isset($company['profile']) ? $company['profile'] : null;

        // 福利
        $welfare = isset($company['welfare']) ? $company['welfare'] : null;
        // welfare 有時候在 data.welfare
        if (!$welfare && isset($data['data']['welfare'])) {
            $welfareData = $data['data']['welfare'];
            if (isset($welfareData['welfare']) && is_string($welfareData['welfare'])) {
                $welfare = $welfareData['welfare'];
            }
        }

        return [
            'name'             => isset($company['name']) ? $company['name'] : null,
            'c_code'           => $cCode,
            'addr_no_descript' => $addrNoDescript,
            'address'          => $address,
            'indcat'           => $indcat,
            'link'             => $link,
            'product'          => $product,
            'profile'          => $profile,
            'welfare'          => $welfare,
        ];
    }

    /**
     * 從 104 取得工作資料（兩階段：Selenium 取 ID + Curl 取詳情）
     * @param array|null $conditions 查詢條件
     * @return array|null 包含 data, total_count, page, total_page 等資訊的陣列
     */
    public function getJobs($conditions = NULL)
    {
        // 設定更新時的查詢條件
        if ($conditions) {
            $this->_set_update_condition($conditions);
        }

        $page = isset($conditions['page']) ? $conditions['page'] : 1;

        // 第一階段：用 Selenium 取得 Job ID 列表
        $searchResult = $this->_getJobIdsFromSearchPage($conditions, $page);

        if (empty($searchResult['job_ids'])) {
            return null;
        }

        // 第二階段：用 Curl 取得每筆職缺的詳細資料
        $jobsData = [];
        foreach ($searchResult['job_ids'] as $jobId) {
            $detail = $this->_getJobDetail($jobId);
            if ($detail) {
                $jobsData[] = [
                    'job_id' => $jobId,
                    'detail' => $detail,
                ];
            }
            // 避免請求太快被封鎖
            usleep(500000); // 0.5 秒
        }

        return [
            'data'        => $jobsData,
            'total_count' => $searchResult['total_count'],
            'page'        => $page,
            'total_page'  => $searchResult['total_page'],
            'search_url'  => $searchResult['search_url'],
        ];
    }

    /**
     * 從來源更新資料庫
     */
    public function update($conditions = NULL)
    {
        // 判斷是否為預灠模式
        $this->_preview_mode = isset($conditions['preview']) ? $conditions['preview'] : false;

        // 取得工作資料
        $json_data = $this->getJobs($conditions);

        // 取不到資料時
        if (!$json_data) {
            return [
                'source'              => self::class,
                'preview_mode'        => $this->_preview_mode,
                'record_count'        => 0,
                'finish_record_count' => 0,
                'page'                => 1,
                'total_page'          => 1,
                'finish_percent'      => '0.00',
                'error'               => '資料取得錯誤（無法從搜尋頁取得職缺 ID）',
            ];
        }

        $page = $json_data['page'];
        $totalPage = $json_data['total_page'];
        $totalCount = $json_data['total_count'];
        $pageSize = isset($conditions['pgsz']) ? $conditions['pgsz'] : 20;

        // 預灠模式：顯示搜尋結果資訊
        if ($this->_preview_mode) {
            $previewData = [];
            foreach ($json_data['data'] as $item) {
                $jobData = $this->_convertNewApiJobData($item['detail'], $item['job_id']);
                $companyData = $this->_convertNewApiCompanyData($item['detail']);
                $previewData[] = [
                    'job'     => $jobData,
                    'company' => $companyData,
                ];
            }

            return [
                'source'              => self::class,
                'preview_mode'        => true,
                'search_url'          => $json_data['search_url'],
                'record_count'        => $totalCount,
                'finish_record_count' => count($json_data['data']),
                'page'                => $page,
                'total_page'          => $totalPage,
                'finish_percent'      => '100.00',
                'preview_data'        => $previewData,
            ];
        }

        // 寫入資料
        foreach ($json_data['data'] as $item) {
            $jobId = $item['job_id'];
            $detail = $item['detail'];

            // 轉換並寫入 company 資料表
            $companyData = $this->_convertNewApiCompanyData($detail);
            if (!$companyData['c_code']) {
                echo "職缺 {$jobId} 無公司代碼，跳過\n";
                continue;
            }
            $companyID = Company::insert($companyData);

            // 轉換並寫入 job 資料表
            $jobData = $this->_convertNewApiJobData($detail, $jobId);
            $jobData['companyID'] = $companyID;
            $jobID = Job::insert($jobData);
        }

        // 計算完成度
        $finishRecordCount = $pageSize * $page;
        if ($page >= $totalPage) {
            $finishPercent = '100.00';
            $finishRecordCount = $totalCount;
        } else {
            $finishPercent = $totalCount > 0
                ? number_format(($finishRecordCount / $totalCount) * 100, 2)
                : '0.00';
        }

        return [
            'source'              => self::class,
            'preview_mode'        => $this->_preview_mode,
            'search_url'          => $json_data['search_url'],
            'record_count'        => $totalCount,
            'finish_record_count' => $finishRecordCount,
            'page'                => $page,
            'total_page'          => $totalPage,
            'finish_percent'      => $finishPercent,
        ];
    }

    public function info()
    {

    }

    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }

    /**
     * 104 json 檔案測試
     * @return Response
     */
    public function show_category()
    {
        $file = JSON_DIR . '104/category.json';
        $content = file_get_contents($file);
        return "<pre>" .  print_r(json_decode($content), TRUE). "</pre>";
    }
}
