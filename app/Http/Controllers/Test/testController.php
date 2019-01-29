<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Adldap\AdldapInterface;

class testController extends Controller
{
    // test界面
	public function test() {

		return view('test.test');
		
	}
	
	
	// phpinfo
	public function phpinfo() {

		return view('test.phpinfo');
		
	}


	// ldap
	public function ldap(AdldapInterface $ldap)
    {

		dd($ldap->search()->users()->get());

		return view('test.ldap', [
            $users = $ldap->search()->users()->get()
        ]);
    }
	
	

}
