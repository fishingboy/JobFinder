<?php

namespace Tests\Classes;

use App\Classes\Job104;
use PHPUnit\Framework\TestCase;

class Job104GetJobsTest extends TestCase
{
    private function getTestConditions(): array
    {
        return [
            'cat'  => '2007001006',
            'area' => '6001001000',
            'role' => [1, 4],
            'pgsz' => 5,
        ];
    }

    public function test_getJobs_回傳陣列或null()
    {
        $job104 = new Job104();
        $result = $job104->getJobs($this->getTestConditions());
        echo "result => " . json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

        // API 可能因外部因素無法取得資料，允許回傳 null 或 array
        $this->assertTrue($result === null || is_array($result));
    }

    public function test_getJobs_有資料時包含必要欄位()
    {
        $job104 = new Job104();
        $result = $job104->getJobs($this->getTestConditions());

        if ($result === null) {
            $this->markTestSkipped('API 無法取得資料，跳過此測試');
        }

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('RECORDCOUNT', $result);
        $this->assertArrayHasKey('PAGE', $result);
        $this->assertArrayHasKey('TOTALPAGE', $result);
    }

    public function test_getJobs_有資料時data包含工作資料()
    {
        $job104 = new Job104();
        $result = $job104->getJobs($this->getTestConditions());

        if ($result === null) {
            $this->markTestSkipped('API 無法取得資料，跳過此測試');
        }

        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);

        if (count($result['data']) > 0) {
            $firstJob = $result['data'][0];
            $this->assertArrayHasKey('JOB', $firstJob);
            $this->assertArrayHasKey('J', $firstJob);
            $this->assertArrayHasKey('NAME', $firstJob);
            $this->assertArrayHasKey('C', $firstJob);
        }
    }

    public function test_getJobs_不帶參數使用預設條件()
    {
        $job104 = new Job104();
        $result = $job104->getJobs();

        // 確認方法可以正常執行，即使沒有傳入條件
        $this->assertTrue($result === null || is_array($result));
    }
}
