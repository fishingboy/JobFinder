<?php

namespace Tests\Classes;

use App\Library\Lib;
use Tests\TestCase;

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
