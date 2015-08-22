<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Form;

class helperController extends Controller
{
    public function index()
    {
        return Form::selectMonth('month');
    }
}
