<?php

namespace App\Console\Commands;

use App\Classes\Crawler104;
use App\Library\Lib;
use App\Models\Company;
use Exception;
use Illuminate\Console\Command;

class UpdateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:companies {--preview}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新公司資訊';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $preview = $this->option('preview');

        $this->line("[" . date("Y-m-d H:i:s") . "] 開始同步公司...");


        $count = 0;
        $total_count = 0;
        while (true) {
            $count++;

            $company_data = Company::get_null_employees();
            if ($company_data['null_count'] == 0)
            {
                $this->line('更新完畢，沒有需要更新的公司！');
                break;
            }

            if ( ! $total_count) {
                $total_count = $company_data['null_count'];
            }

            $companyID = $company_data['row']->companyID;
            $c_code    = $company_data['row']->c_code;

            // 爬 104 網頁取得資訊
            try {
                $data = Crawler104::get_company($c_code);
            } catch (Exception $e) {
                $this->error("[$c_code] Error:" . $e->getMessage());
                exit;
            }

            if ($data)
            {
                // 更新資料庫
                Company::where('companyID', $companyID)->update($data);
            }
            else
            {
                $this->error("[$c_code] Error: 爬蟲爬不到資料: {$company_data['row']->name}！");
                exit;
            }

            $this->line("更新完成 $count / $total_count 筆公司資料: {$company_data['row']->name}");

            // 判斷更新是否要自動跳轉下一頁
            if ($preview || $company_data['null_count'] <= 0) {
                break;
            }

            sleep(3);
        }

        $this->line("[" . date("Y-m-d H:i:s") . "] 同步公司成功 !");
    }
}
