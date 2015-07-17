<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use FirePHP;

class PluginController extends Controller
{
    public function firephp()
    {
        $fb = new FirePHP();
        $fb->info(['a' => '1', 'b' => 2], "info");
        return "firephp test!!";
    }
}
