<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class mainController extends Controller
{
    //
	public function mainIndex () {

		return view('main.portal');
		
	}

}
