<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Main\Smt_config;

class mainController extends Controller
{
    //
	public function mainPortal () {

		return view('main.portal');
		
	}

	public function mainConfig () {

		return view('main.config');
		
	}
	
	public function configGets (Request $request) {

		if (! $request->ajax()) return null;

		// $configgets = Smt_config::pluck('value', 'name');
		$configgets = Smt_config::select('title', 'name', 'value')->get();
			
		
		return $configgets;		
	}

	public function configCreate (Request $request) {

		if (! $request->ajax()) return null;
		
		$position = $request->input('position');
		$name = $request->input('name');
		$value = $request->input('value');
		// dd($value);
		
		try	{
			DB::beginTransaction();
			
			$data_old = Smt_config::select('value')
				->where('name', $name)
				->first();
			// dd($data_old['value']);
			
			$arr = explode('---', $data_old['value']);
			array_splice($arr, $position, 0, $value);
			// dd($arr);
			$data_new = implode('---', $arr);
			// dd($data_new);
			
			 Smt_config::where('name', $name)
			 ->update(['value' => $data_new]);

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		return $result;				
		
		
		
	}

	public function configUpdate (Request $request) {

		if (! $request->ajax()) return null;
		
		$name = $request->input('name');
		$value = $request->input('value');
		// dd($value);
		
		try	{
			DB::beginTransaction();
			
			 Smt_config::where('name', $name)
				 ->update(['value' => $value]);

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		return $result;				
		
		
		
	}
	
	

}
