<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Library\Lib;

class GetConditionsTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_取得設定檔()
    {
        $data = Lib::get_conditions();
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";

        $this->assertIsArray($data);
        $this->assertArrayHasKey("cat", $data);
    }
}
