<?php
namespace App\Http\Controllers;

use App\Services\MrtService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Curl;
use App\Library\Lib;
use App\Models\Company;
use App\Classes\Crawler104;

/**
 * 爬蟲
 */
class CrawlerController extends Controller
{
    /**
     * 取得公司資訊(員工人數、資本額、網址)
     * @param  Integer $companyID 公司流水號
     * @return Response
     */
    public function get_company($companyID = '1')
    {
        // 取得 Company 資料
        $row = Company::get($companyID);
        if ($row)
        {
            echo "<pre>row = " . print_r($row, TRUE). "</pre>";
            echo Lib::number2capital($row->capital);

            $c_code = $row->c_code;

            // 爬 104 網頁取得資訊
            $data = Crawler104::get_company($c_code);
            if ($data)
            {
                echo "<pre>data = " . print_r($data, TRUE). "</pre>";
                Company::where('companyID', $companyID)->update($data);
            }
            else
            {
                return '[$c_code] Crawler No Response!';
            }
        }
        else
        {
            return 'No Company Data.';
        }
    }

    /**
     * 更新公司資料(員工人數、資本額)
     */
    public function update_company()
    {
        $company_data = Company::get_null_employees();
        if ($company_data['null_count'] == 0)
        {
            return '更新完畢，沒有需要更新的公司！';
        }

        $companyID = $company_data['row']->companyID;
        $c_code    = $company_data['row']->c_code;

        // 爬 104 網頁取得資訊
        $data = Crawler104::get_company($c_code);
        if ($data)
        {
            // 更新資料庫
            Company::where('companyID', $companyID)->update($data);

            // 完成筆數加 1
            $company_data['null_count']--;

            // 計算完成度
            $company_data['finish_percent'] = number_format(($company_data['count'] - $company_data['null_count']) / $company_data['count'] * 100, 2);

            // 取得爬蟲資訊
            $company_data['employees'] = $data['employees'];
            $company_data['capital']   = Lib::number2capital($data['capital']);
            $company_data['url']       = $data['url'];
        }
        else
        {
            return 'Crawler No Response!';
        }

        $view_data = $company_data;

        // 判斷更新是否要自動跳轉下一頁
        $view_data['go_next_page_js'] = '';
        if ($company_data['null_count'] > 0)
        {
            $next_url = "/crawler/company/update";
            $view_data['go_next_page_js'] = "<script>window.location.href = '{$next_url}';</script>";
        }

        return view('update_company_report', $view_data);
    }

    public function getMrt(MrtService $mrtService)
    {
        $mrtService->getLocationWithGeo();
    }

    public function getMrtDummyData()
    {
        $data = array(0 => array('id' => '019', 'name' => '動物園', 'address' => '11656臺北市文山區新光路2段32號',), 1 => array('id' => '018', 'name' => '木柵', 'address' => '11656臺北市文山區木柵路4段135號',), 2 => array('id' => '017', 'name' => '萬芳社區', 'address' => '11653臺北市文山區萬芳路60號',), 3 => array('id' => '016', 'name' => '萬芳醫院', 'address' => '11696臺北市文山區興隆路3段113號',), 4 => array('id' => '015', 'name' => '辛亥', 'address' => '11694臺北市文山區辛亥路4段128號',), 5 => array('id' => '014', 'name' => '麟光', 'address' => '10676臺北市大安區和平東路3段410號',), 6 => array('id' => '013', 'name' => '六張犁', 'address' => '10674臺北市大安區和平東路3段168號',), 7 => array('id' => '012', 'name' => '科技大樓', 'address' => '10667臺北市大安區復興南路2段235號',), 8 => array('id' => '011', 'name' => '大安', 'address' => '10683臺北市大安區信義路4段2號',), 9 => array('id' => '010', 'name' => '忠孝復興', 'address' => '10654臺北市大安區忠孝東路3段302號',), 10 => array('id' => '009', 'name' => '南京復興', 'address' => '10550臺北市松山區南京東路3段253號',), 11 => array('id' => '008', 'name' => '中山國中', 'address' => '10476臺北市中山區復興北路376號',), 12 => array('id' => '007', 'name' => '松山機場', 'address' => '10576臺北市松山區敦化北路338號',), 13 => array('id' => '021', 'name' => '大直', 'address' => '10465臺北市中山區北安路534之1號',), 14 => array('id' => '022', 'name' => '劍南路', 'address' => '10464臺北市中山區北安路798號',), 15 => array('id' => '023', 'name' => '西湖', 'address' => '11493臺北市內湖區內湖路1段256號',), 16 => array('id' => '024', 'name' => '港墘', 'address' => '11446臺北市內湖區內湖路1段663號',), 17 => array('id' => '025', 'name' => '文德', 'address' => '11475臺北市內湖區文德路214號',), 18 => array('id' => '026', 'name' => '內湖', 'address' => '11489臺北市內湖區成功路4段186號',), 19 => array('id' => '027', 'name' => '大湖公園', 'address' => '11477臺北市內湖區成功路5段11號',), 20 => array('id' => '028', 'name' => '葫洲', 'address' => '11486臺北市內湖區康寧路3段16號',), 21 => array('id' => '029', 'name' => '東湖', 'address' => '11486臺北市內湖區康寧路3段235號',), 22 => array('id' => '030', 'name' => '南港軟體園區', 'address' => '11568臺北市南港區經貿二路183號',), 23 => array('id' => '031', 'name' => '南港展覽館', 'address' => '11568臺北市南港區南港路1段32號',), 24 => array('id' => '071', 'name' => '淡水', 'address' => '25158新北市淡水區中正路1號',), 25 => array('id' => '070', 'name' => '紅樹林', 'address' => '25173新北市淡水區中正東路2段68號',), 26 => array('id' => '069', 'name' => '竹圍', 'address' => '25173新北市淡水區民權路50號',), 27 => array('id' => '068', 'name' => '關渡', 'address' => '11259臺北市北投區大度路3段296巷51號',), 28 => array('id' => '067', 'name' => '忠義', 'address' => '11257臺北市北投區中央北路4段301號',), 29 => array('id' => '066', 'name' => '復興崗', 'address' => '11257臺北市北投區中央北路3段53巷10號',), 30 => array('id' => '065', 'name' => '新北投', 'address' => '11268臺北市北投區大業路700號',), 31 => array('id' => '064', 'name' => '北投', 'address' => '11246臺北市北投區光明路1號',), 32 => array('id' => '063', 'name' => '奇岩', 'address' => '11270臺北市北投區三合街2段489號',), 33 => array('id' => '062', 'name' => '唭哩岸', 'address' => '11265臺北市北投區東華街2段301號',), 34 => array('id' => '061', 'name' => '石牌', 'address' => '11271臺北市北投區石牌路1段200號',), 35 => array('id' => '060', 'name' => '明德', 'address' => '11280臺北市北投區明德路95號',), 36 => array('id' => '059', 'name' => '芝山', 'address' => '11158臺北市士林區福國路70號',), 37 => array('id' => '058', 'name' => '士林', 'address' => '11163臺北市士林區福德路1號',), 38 => array('id' => '057', 'name' => '劍潭', 'address' => '11163臺北市士林區中山北路5段65號',), 39 => array('id' => '056', 'name' => '圓山', 'address' => '10367臺北市大同區酒泉街9之1號',), 40 => array('id' => '055', 'name' => '民權西路', 'address' => '10365臺北市大同區民權西路72號',), 41 => array('id' => '054', 'name' => '雙連', 'address' => '10354臺北市大同區民生西路47號',), 42 => array('id' => '053', 'name' => '中山', 'address' => '10444臺北市中山區南京西路16號',), 43 => array('id' => '051', 'name' => '台北車站', 'address' => '10041臺北市中正區忠孝西路1段49號',), 44 => array('id' => '050', 'name' => '台大醫院', 'address' => '10048臺北市中正區公園路52號B1',), 45 => array('id' => '042', 'name' => '中正紀念堂', 'address' => '10074臺北市中正區羅斯福路1段8之1號B1 ',), 46 => array('id' => '134', 'name' => '東門', 'address' => '10650臺北市大安區信義路2段166號B1',), 47 => array('id' => '103', 'name' => '大安森林公園', 'address' => '10656臺北市大安區信義路3段100號B1',), 48 => array('id' => '011', 'name' => '大安', 'address' => '10683臺北市大安區信義路4段2號',), 49 => array('id' => '101', 'name' => '信義安和', 'address' => '10681臺北市大安區信義路4段212之1號B1',), 50 => array('id' => '100', 'name' => '台北101/世貿', 'address' => '11049臺北市信義區信義路5段20號B1',), 51 => array('id' => '099', 'name' => '象山', 'address' => '11080臺北市信義區信義路5段152號B1',), 52 => array('id' => '111', 'name' => '松山', 'address' => '10567臺北市松山區八德路4段742號',), 53 => array('id' => '110', 'name' => '南京三民', 'address' => '10569臺北市松山區南京東路5段237號',), 54 => array('id' => '109', 'name' => '台北小巨蛋', 'address' => '10553臺北市松山區南京東路4段10-1號',), 55 => array('id' => '009', 'name' => '南京復興', 'address' => '10550臺北市松山區南京東路3段253號',), 56 => array('id' => '132', 'name' => '松江南京', 'address' => '10457臺北市中山區松江路126號B1',), 57 => array('id' => '053', 'name' => '中山', 'address' => '10444臺北市中山區南京西路16號',), 58 => array('id' => '105', 'name' => '北門', 'address' => '10341臺北市大同區塔城街10號',), 59 => array('id' => '086', 'name' => '西門', 'address' => '10042臺北市中正區寶慶路32之1號B1',), 60 => array('id' => '043', 'name' => '小南門', 'address' => '10066臺北市中正區愛國西路22號B1',), 61 => array('id' => '042', 'name' => '中正紀念堂', 'address' => '10074臺北市中正區羅斯福路1段8之1號B1 ',), 62 => array('id' => '041', 'name' => '古亭', 'address' => '10084臺北市中正區羅斯福路2段164之1號B1 ',), 63 => array('id' => '040', 'name' => '台電大樓', 'address' => '10089臺北市中正區羅斯福路3段126之5號B1',), 64 => array('id' => '039', 'name' => '公館', 'address' => '10091臺北市中正區羅斯福路4段74號B1',), 65 => array('id' => '038', 'name' => '萬隆', 'address' => '11678臺北市文山區羅斯福路5段214號',), 66 => array('id' => '037', 'name' => '景美', 'address' => '11674臺北市文山區羅斯福路6段393號',), 67 => array('id' => '036', 'name' => '大坪林', 'address' => '23143新北市新店區北新路3段190號',), 68 => array('id' => '035', 'name' => '七張', 'address' => '23143新北市新店區北新路2段150號',), 69 => array('id' => '032', 'name' => '小碧潭', 'address' => '23150新北市新店區中央路151號4樓',), 70 => array('id' => '034', 'name' => '新店區公所', 'address' => '23147新北市新店區北新路1段295號',), 71 => array('id' => '033', 'name' => '新店', 'address' => '23152新北市新店區北宜路1段2號',), 72 => array('id' => '048', 'name' => '南勢角', 'address' => '23566新北市中和區捷運路6號 ',), 73 => array('id' => '047', 'name' => '景安', 'address' => '23582新北市中和區景平路486號',), 74 => array('id' => '046', 'name' => '永安市場', 'address' => '23574新北市中和區中和路388號',), 75 => array('id' => '045', 'name' => '頂溪', 'address' => '23445新北市永和區永和路2段168號B1 ',), 76 => array('id' => '041', 'name' => '古亭', 'address' => '10084臺北市中正區羅斯福路2段164之1號B1 ',), 77 => array('id' => '134', 'name' => '東門', 'address' => '10650臺北市大安區信義路2段166號B1',), 78 => array('id' => '089', 'name' => '忠孝新生', 'address' => '10652臺北市大安區新生南路1段67號',), 79 => array('id' => '132', 'name' => '松江南京', 'address' => '10457臺北市中山區松江路126號B1',), 80 => array('id' => '131', 'name' => '行天宮', 'address' => '10468臺北市中山區松江路316號B1',), 81 => array('id' => '130', 'name' => '中山國小', 'address' => '10452臺北市中山區民權東路1段71號B1',), 82 => array('id' => '055', 'name' => '民權西路', 'address' => '10365臺北市大同區民權西路72號',), 83 => array('id' => '128', 'name' => '大橋頭', 'address' => '10357臺北市大同區民權西路223號B1',), 84 => array('id' => '127', 'name' => '台北橋', 'address' => '24142新北市三重區重新路1段108號B1',), 85 => array('id' => '126', 'name' => '菜寮', 'address' => '24143新北市三重區重新路3段150號B1 ',), 86 => array('id' => '125', 'name' => '三重', 'address' => '24161新北市三重區捷運路36號B1',), 87 => array('id' => '124', 'name' => '先嗇宮', 'address' => '24158新北市三重區重新路5段515號B1',), 88 => array('id' => '123', 'name' => '頭前庄', 'address' => '24251新北市新莊區思源路18號B1',), 89 => array('id' => '122', 'name' => '新莊', 'address' => '24243新北市新莊區中正路138號B1',), 90 => array('id' => '121', 'name' => '輔大', 'address' => '24205新北市新莊區中正路510之1號B1',), 91 => array('id' => '180', 'name' => '丹鳳', 'address' => '24256新北市新莊區中正路624之1號B1',), 92 => array('id' => '179', 'name' => '迴龍', 'address' => '24257新北市新莊區中正路758號B1',), 93 => array('id' => '178', 'name' => '三重國小', 'address' => '24149新北市三重區三和路3段5號B1',), 94 => array('id' => '177', 'name' => '三和國中', 'address' => '24152新北市三重區三和路4段107號B1',), 95 => array('id' => '176', 'name' => '徐匯中學', 'address' => '24753新北市蘆洲區中山一路3號B1',), 96 => array('id' => '175', 'name' => '三民高中', 'address' => '24760新北市蘆洲區三民路105號B1',), 97 => array('id' => '174', 'name' => '蘆洲', 'address' => '24760新北市蘆洲區三民路386號B1',), 98 => array('id' => '076', 'name' => '頂埔', 'address' => '23671新北市土城區中央路4段51-6號B3',), 99 => array('id' => '077', 'name' => '永寧', 'address' => '23671新北市土城區中央路3段105號B1',), 100 => array('id' => '078', 'name' => '土城', 'address' => '23671新北市土城區金城路1段105號B1',), 101 => array('id' => '079', 'name' => '海山', 'address' => '23660新北市土城區海山路39號B2',), 102 => array('id' => '080', 'name' => '亞東醫院', 'address' => '22060新北市板橋區南雅南路2段17號B1',), 103 => array('id' => '081', 'name' => '府中', 'address' => '22055新北市板橋區縣民大道1段193號B1',), 104 => array('id' => '082', 'name' => '板橋', 'address' => '22041新北市板橋區站前路5號B1',), 105 => array('id' => '083', 'name' => '新埔', 'address' => '22047新北市板橋區民生路3段2號B1',), 106 => array('id' => '084', 'name' => '江子翠', 'address' => '22044新北市板橋區文化路2段296號B1',), 107 => array('id' => '085', 'name' => '龍山寺', 'address' => '10855臺北市萬華區西園路1段153號',), 108 => array('id' => '086', 'name' => '西門', 'address' => '10042臺北市中正區寶慶路32之1號B1',), 109 => array('id' => '051', 'name' => '台北車站', 'address' => '10041臺北市中正區忠孝西路1段49號',), 110 => array('id' => '088', 'name' => '善導寺', 'address' => '10049臺北市中正區忠孝東路1段58號B1',), 111 => array('id' => '089', 'name' => '忠孝新生', 'address' => '10652臺北市大安區新生南路1段67號',), 112 => array('id' => '010', 'name' => '忠孝復興', 'address' => '10654臺北市大安區忠孝東路3段302號',), 113 => array('id' => '091', 'name' => '忠孝敦化', 'address' => '10686臺北市大安區忠孝東路4段182號',), 114 => array('id' => '092', 'name' => '國父紀念館', 'address' => '11072臺北市信義區忠孝東路4段400號',), 115 => array('id' => '093', 'name' => '市政府', 'address' => '11071臺北市信義區忠孝東路5段2號',), 116 => array('id' => '094', 'name' => '永春', 'address' => '11061臺北市信義區忠孝東路5段455號',), 117 => array('id' => '095', 'name' => '後山埤', 'address' => '11575臺北市南港區忠孝東路6段2號',), 118 => array('id' => '096', 'name' => '昆陽', 'address' => '11558臺北市南港區忠孝東路6段451號',), 119 => array('id' => '097', 'name' => '南港', 'address' => '11561臺北市南港區忠孝東路7段380號',), 120 => array('id' => '031', 'name' => '南港展覽館', 'address' => '11568臺北市南港區南港路1段32號',),);

        return $data;
    }

}
