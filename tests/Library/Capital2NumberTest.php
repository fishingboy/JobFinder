<?php

namespace Tests\Library;

use App\Library\Lib;
use PHPUnit\Framework\TestCase;

class Capital2NumberTest extends TestCase
{
    public function test_1億應該回傳100000000()
    {
        $chinese = "1億";
        $response = Lib::capital2number($chinese);
        $this->assertEquals(100000000, $response);
    }

    public function test_1億3000萬應該回傳130000000()
    {
        $chinese = "1億3000萬";
        $response = Lib::capital2number($chinese);
        $this->assertEquals(130000000, $response);
    }

    public function test_50萬應該回傳500000()
    {
        $chinese = "50萬";
        $response = Lib::capital2number($chinese);
        $this->assertEquals(500000, $response);
    }

    public function test_暫不提供應該回傳0()
    {
        $chinese = "暫不提供";
        $response = Lib::capital2number($chinese);
        $this->assertEquals(0, $response);
    }
}
