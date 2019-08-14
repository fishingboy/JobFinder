<?php

use App\Classes\Crawler104;

class Get104CompanyTest extends TestCase
{
    public function test_取得104公司()
    {
        // 爬 104 網頁取得資訊
        $c_code = "4f39416a33353d662f313962373d3518628282828405b316795j02";
        $data = Crawler104::get_company($c_code);

        $this->assertIsArray($data);
        $this->assertArrayHasKey("employees", $data);
        $this->assertArrayHasKey("capital", $data);
        $this->assertArrayHasKey("url", $data);
    }

    public function test_取得104公司2()
    {
        // 爬 104 網頁取得資訊
        $c_code = "623a426b34363e6730323a63383e361972929296e41364a6785j50";
        $data = Crawler104::get_company($c_code);

        $this->assertIsArray($data);
        $this->assertArrayHasKey("employees", $data);
        $this->assertArrayHasKey("capital", $data);
        $this->assertArrayHasKey("url", $data);
    }
}
