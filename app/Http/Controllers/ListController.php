<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use DB;

class ListController extends Controller
{
	public function listJob()
	{
		return view('lists/job');
	}

	public function listCompany()
	{
		return view('lists/company');
	}
}
