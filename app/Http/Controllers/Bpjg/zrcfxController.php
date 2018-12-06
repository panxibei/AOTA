<?php
// 中日程分解
namespace App\Http\Controllers\Bpjg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Bpjg\Bpjg_zhongricheng_zrc;
use App\Models\Bpjg\Bpjg_zhongricheng_main;
use DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class zrcfxController extends Controller
{
    //
	public function zrcfxIndex () {

		return view('bpjg.zrcfx');
		
	}
	
	
    /**
     * zrcGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcGets(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();
		
		if (isset($queryParams['perPage'])) {
			$perPage = $queryParams['perPage'] ?: 10000;
		} else {
			$perPage = 10000;
		}
		
		if (isset($queryParams['page'])) {
			$page = $queryParams['page'] ?: 1;
		} else {
			$page = 1;
		}
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		// dd($fullUrl);
		// dd($queryParams);
		// dd($qcdate_filter);
		
		// 注意$usecache变量的类型
		// if ($usecache == "false") {
			// Cache::forget($fullUrl);
			// Cache::flush();
		// }
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$result = Bpjg_zhongricheng_zrc::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('riqi', $qcdate_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(30));
		}
		
		return $result;
    }
	
	
    /**
     * mainGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainGets(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();
		
		if (isset($queryParams['perPage'])) {
			$perPage = $queryParams['perPage'] ?: 10000;
		} else {
			$perPage = 10000;
		}
		
		if (isset($queryParams['page'])) {
			$page = $queryParams['page'] ?: 1;
		} else {
			$page = 1;
		}
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$pinfan_filter = $request->input('pinfan_filter');
		$pinming_filter = $request->input('pinming_filter');
		$leibie_filter = $request->input('leibie_filter');
		
		// $usecache = $request->input('usecache');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		// dd($fullUrl);
		// dd($queryParams);
		// dd($qcdate_filter);
		
		// 注意$usecache变量的类型
		// if ($usecache == "false") {
			// Cache::forget($fullUrl);
			// Cache::flush();
		// }
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$result = Bpjg_zhongricheng_main::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('riqi', $qcdate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($pinfan_filter, function ($query) use ($pinfan_filter) {
					return $query->where('pinfan', 'like', '%'.$pinfan_filter.'%');
				})
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', 'like', '%'.$pinming_filter.'%');
				})
				->when($leibie_filter, function ($query) use ($leibie_filter) {
					return $query->where('leibie', '=', $leibie_filter);
				})
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(30));
		}
		
		return $result;
    }	
	
    /**
     * zrcCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$piliangluru = $request->input('piliangluru');

		// dd($piliangluru);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			foreach ($piliangluru as $value) {
				Bpjg_zhongricheng_zrc::create($value);
			}

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
    }	
	
	
    /**
     * mainCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$xianti = $request->input('xianti');
		$qufen = $request->input('qufen');
		$piliangluru = $request->input('piliangluru');

		foreach ($piliangluru as $key => $value) {
			$s[$key]['xianti'] = $xianti;
			$s[$key]['qufen'] = $qufen;

			$s[$key]['jizhongming'] = $value['jizhongming'];
			$s[$key]['pinfan'] = $value['pinfan'];
			$s[$key]['pinming'] = $value['pinming'];
			$s[$key]['xuqiushuliang'] = $value['xuqiushuliang'];
			$s[$key]['leibie'] = $value['leibie'];
		}

		// dd($s);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			foreach ($s as $value) {
				Bpjg_zhongricheng_main::create($value);
			}

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
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
