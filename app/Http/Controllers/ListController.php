<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use DB;

class ListController extends Controller
{
	public function listJob($args = '104')
	{
		if ($args == '104')
		{
			return view('lists/job');
		
		}
		else 
		{
			return view('lists/job_ptt');
		}
	}

	public function listCompany()
	{
		return view('lists/company');
	}
}
