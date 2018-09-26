<?php
// 中日程分解
namespace App\Http\Controllers\Bpjg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class zrcfxController extends Controller
{
    //
	public function zrcfxIndex () {

		return view('bpjg.zrcfx');
		
	}
	
	
	
	
	
    /**
     * zrcfjImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcfxImport(Request $request)
    {
		// if (! $request->ajax()) { return null; }
// dd($request->file('myfile'));


		$fileCharater = $request->file('myfile');
 // dd($fileCharater);
		if ($fileCharater->isValid()) { //括号里面的是必须加的哦
			//如果括号里面的不加上的话，下面的方法也无法调用的

			//获取文件的扩展名 
			$ext = $fileCharater->extension();
// dd($ext);
			//获取文件的绝对路径
			$path = $fileCharater->path();
// dd($path);
			//定义文件名
			$filename = date('Y-m-d-h-i-s').'.'.$ext;

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', 'import.xlsx');
		} else {
			return 0;
		}
		
		// dd($filename);
		// Storage::delete('excel/import.xlsx');
		// dd($filename);
		
		
		
		
		
		
		Excel::import(new zrcfxImport, 'excel/import.xlsx');
		
		Storage::delete('excel/import.xlsx');
		
		return 1;
		
	}
	
	
	
	
	
	
	
	
	
	
}
