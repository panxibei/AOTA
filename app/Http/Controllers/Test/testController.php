<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class testController extends Controller
{
    //
	public function test() {

		return view('test.test');
		
	}
	
	
	public function phpinfo() {

		return view('test.phpinfo');
		
	}


	
	

}
