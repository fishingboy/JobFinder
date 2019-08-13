<?php

namespace App\Console\Commands;

use App\Classes\Job104;
use App\Classes\JobPtt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:jobs {source} {--preview}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新職缺';

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
        $source = $this->argument('source');
        $preview = $this->option('preview');
        $this->line('把我顯示在畫面上' . $source . "::" . $preview);


        // 取得 job 實體
        $job = $this->_create_job($source);

        // 查詢條件預設值
        $conditions = [
            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
            'area' => ['6001001000', '6001002000'],
            'role' => [1, 4],
            'exp'  => 7,
            'kws'  => 'php python',
            'kwop' => 3,
        ];

        // 從 json 取得查詢條件
        $json_file = "../resources/json/condition.json";
        $condition_file = '';
        if (file_exists($json_file))
        {
            $json = file_get_contents($json_file);
            $data = json_decode($json, TRUE);

            if ($data)
            {
                $conditions = $data;
                $condition_file = $json_file;
            }
            else
            {
                exit("JSON 格式壞了！請檢查一下");
            }
        } else {
            $this->line("找不到設定檔。");
            exit;
        }


        $conditions['pgsz'] = 100;

        $page = 1;
        while (true) {
            // 取得分頁
            $conditions['page'] = $page;

            // 是否為預灠
            $conditions['preview'] = $preview;

            // 更新資料庫
            $job_data = $job->update($conditions);

            $this->line("update [$page/{$job_data['total_page']}] done !");

            // 判斷更新是否要自動跳轉下一頁
            $job_data['go_next_page_js'] = '';
            if ($preview || $page >= $job_data['total_page'])
            {
                break;
            }

            $page++;

            sleep(3);
        }

        // 清除過期工作
        if ( ! $preview) {
            $this->clear_expired_job();
        }

        $this->line("update [$source] 成功 !");
    }

    /**
     * 建立 job 實體
     * @param  string $source 來源
     * @return object         job 實體
     */
    private function _create_job($source = '104')
    {
        switch ($source)
        {
            case 'ptt':
                $job = new JobPtt();
                break;

            case '104':
            default:
                $job = new Job104();
                break;
        }
        return $job;
    }

    public function clear_expired_job()
    {
        $today = date("Y-m-d 00:00:00");
        DB::delete("delete from job WHERE updated_at < '$today'");
        echo "刪除過期工作記錄!!!";
    }
}
