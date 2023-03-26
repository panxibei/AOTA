<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use Adldap\AdldapInterface;
use Adldap\Laravel\Facades\Adldap;

//use Milon\Barcode\Facades\DNS1DFacade;
//use \Milon\Barcode\DNS1D;

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

		// dd($ldap->search()->users()->get());
		$data = $ldap->search()->users()->get()->toArray();
// dd($data);

		$username = 'ca071215958';
		$password = 'Aota12345678';

		try {
			$ldap = Adldap::auth()->attempt(
				// $user['name'] . env('LDAP_ACCOUNT_SUFFIX'),
				$username,
				$password
				);
				
			// 获取用户email
			$user_tmp = Adldap::search()->users()->find($username);		
			$email = $user_tmp['mail'][0];
		}
		// catch (Exception $e) {
		catch (\Adldap\Auth\BindException $e) { //捕获异常
			echo 'Message: ' .$e->getMessage();
			$ldap = false;
		}
dd($email);

		// return view('test.ldap', [
            // 'users' => $ldap->search()->users()->get()
        // ]);
		return view('test.ldap', $data);
    }
	
	
    // scroll界面
	public function scroll() {

		return view('test.scroll');
		
	}
	

	// barcode
	public function barcode() {

		// =============================================================
		// generator in html, png embedded base64 code and SVG canvas
		// 
		// echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T');
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T');
		// echo '<img src="data:image/png,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
		// echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T');
		// echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
		// 
		// echo DNS1D::getBarcodeSVG('4445645656', 'C39');
		// echo DNS2D::getBarcodeHTML('4445645656', 'QRCODE');
		// echo DNS2D::getBarcodePNGPath('4445645656', 'PDF417');
		// echo DNS2D::getBarcodeSVG('4445645656', 'DATAMATRIX');
		// echo '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG('4', 'PDF417') . '" alt="barcode"   />';
		// 
		// --------------------------------------------------------------
		// 
		// Width and Height example
		// 
		// echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T',3,33);
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T',3,33);
		// echo '<img src="' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';
		// echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T',3,33);
		// echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';
		// 
		// --------------------------------------------------------------
		// 
		// Color
		// 
		// echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T',3,33,'green');
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T',3,33,'green');
		// echo '<img src="' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1)) . '" alt="barcode"   />';
		// echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T',3,33,array(255,255,0));
		// echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1)) . '" alt="barcode"   />';
		// 
		// --------------------------------------------------------------
		// 
		// Show Text
		// 
		// echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T',3,33,'green', true);
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T',3,33,'green', true);
		// echo '<img src="' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1), true) . '" alt="barcode"   />';
		// echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T',3,33,array(255,255,0), true);
		// echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1), true) . '" alt="barcode"   />';
		// 
		// --------------------------------------------------------------
		// 
		// 2D Barcodes
		// 
		// echo DNS2D::getBarcodeHTML('4445645656', 'QRCODE');
		// echo DNS2D::getBarcodePNGPath('4445645656', 'PDF417');
		// echo DNS2D::getBarcodeSVG('4445645656', 'DATAMATRIX');
		// 
		// --------------------------------------------------------------
		// 
		// 1D Barcodes
		// 
		// echo DNS1D::getBarcodeHTML('4445645656', 'C39');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C39+');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C39E');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C39E+');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C93');
		// echo DNS1D::getBarcodeHTML('4445645656', 'S25');
		// echo DNS1D::getBarcodeHTML('4445645656', 'S25+');
		// echo DNS1D::getBarcodeHTML('4445645656', 'I25');
		// echo DNS1D::getBarcodeHTML('4445645656', 'I25+');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C128');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C128A');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C128B');
		// echo DNS1D::getBarcodeHTML('4445645656', 'C128C');
		// echo DNS1D::getBarcodeHTML('44455656', 'EAN2');
		// echo DNS1D::getBarcodeHTML('4445656', 'EAN5');
		// echo DNS1D::getBarcodeHTML('4445', 'EAN8');
		// echo DNS1D::getBarcodeHTML('4445', 'EAN13');
		// echo DNS1D::getBarcodeHTML('4445645656', 'UPCA');
		// echo DNS1D::getBarcodeHTML('4445645656', 'UPCE');
		// echo DNS1D::getBarcodeHTML('4445645656', 'MSI');
		// echo DNS1D::getBarcodeHTML('4445645656', 'MSI+');
		// echo DNS1D::getBarcodeHTML('4445645656', 'POSTNET');
		// echo DNS1D::getBarcodeHTML('4445645656', 'PLANET');
		// echo DNS1D::getBarcodeHTML('4445645656', 'RMS4CC');
		// echo DNS1D::getBarcodeHTML('4445645656', 'KIX');
		// echo DNS1D::getBarcodeHTML('4445645656', 'IMB');
		// echo DNS1D::getBarcodeHTML('4445645656', 'CODABAR');
		// echo DNS1D::getBarcodeHTML('4445645656', 'CODE11');
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA');
		// echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T');
		// 
		// --------------------------------------------------------------
		// 
		// Running without Laravel
		// 
		// You can use this library without using Laravel.
		// 
		// Example:
		// 
		// use \Milon\Barcode\DNS1D;
		// 
		// $d = new DNS1D();
		// $d->setStorPath(__DIR__.'/cache/');
		// echo $d->getBarcodeHTML('9780691147727', 'EAN13');
		// 
		// =============================================================

/*
 		echo \DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T');
		echo '<br><br>';
		echo \DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T');
		echo '<br><br>';
		//echo '<img src="data:image/png,' . \DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
		//echo '<br><br>';
		echo \DNS1D::getBarcodePNGPath('1234567890', 'PHARMA2T');
		echo '<br><br>';
		echo "\DNS1D::getBarcodePNG('4', 'C39+');<br>";
		echo '<img src="data:image/png;base64,' . \DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
		echo '<br><br>';

		echo "\DNS1D::getBarcodeSVG('4445645656', 'C39');<br>";
		echo \DNS1D::getBarcodeSVG('4445645656', 'C39');
		echo '<br><br>';
		echo 'data:image/png;base64<br>';
		echo \DNS2D::getBarcodeHTML('4445645656', 'QRCODE');
		echo '<br><br>';
		echo \DNS2D::getBarcodePNGPath('4445645656', 'PDF417');
		echo '<br><br>';
		echo \DNS2D::getBarcodeSVG('4445645656', 'DATAMATRIX');
		echo '<br><br>';
		echo '<img src="data:image/png;base64,' . \DNS2D::getBarcodePNG('4', 'PDF417') . '" alt="barcode"   />';
		echo '<br><br>';
 */

	//dd('aaaaaaaaaaa');
		//$data = ['key' => '<img src="data:image/png;base64,' . \DNS1D::getBarcodePNG('C91S001230306T4200', 'C39+') . '" alt="barcode" />'];
		$data = ['key' => \DNS1D::getBarcodeHTML('84-38011Z01-RA', 'C128', 1, 60, 'black', true)];

		//$data = ['key' => 'abcvvvvvvvvvvvvvvvvvvvvvvvvv'];

	//dd($data);
		return view('test.barcode', $data);
		//return view('test.barcode')->with('key','gamacode.com');
		
	}


}
