<?php
// 中日程分解
namespace App\Http\Controllers\Bpjg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Bpjg\Bpjg_zhongricheng_zrc;
use App\Models\Bpjg\Bpjg_zhongricheng_main;
use DB;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\Smt\qcreportExport;
use App\Imports\Bpjg\zrcfx_zrcImport;
use App\Imports\Bpjg\zrcfx_mainImport;

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
				->limit(5000)
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
				->limit(5000)
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
     * zrcUpdate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$riqi = $request->input('riqi');
		$jizhongming = $request->input('jizhongming');
		$shuliang = $request->input('shuliang');
		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');

		// dd($id);
		// dd($updated_at);
		
		// 判断如果不是最新的记录，不可被编辑
		// 因为可能有其他人在你当前表格未刷新的情况下已经更新过了
		$res = Bpjg_zhongricheng_zrc::select('updated_at')
			->where('id', $id)
			->first();
		$res_updated_at = date('Y-m-d H:i:s', strtotime($res['updated_at']));

		// dd($updated_at . ' | ' . $res_updated_at);
		// dd(gettype($updated_at) . ' | ' . gettype($res_updated_at));
		// dd($updated_at != $res_updated_at);
		
		if ($updated_at != $res_updated_at) {
			return 0;
		}
		
		// 尝试更新
		try	{
			DB::beginTransaction();
			$result = Bpjg_zhongricheng_zrc::where('id', $id)
				->update([
					'riqi'			=> $riqi,
					'jizhongming'	=> $jizhongming,
					'shuliang'		=> $shuliang,
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			DB::rollBack();
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;

	}


    /**
     * mainUpdate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$xianti = $request->input('xianti');
		$qufen = $request->input('qufen');
		$jizhongming = $request->input('jizhongming');
		$pinfan = $request->input('pinfan');
		$pinming = $request->input('pinming');
		$xuqiushuliang = $request->input('xuqiushuliang');
		$leibie = $request->input('leibie');
		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		
		// dd($updated_at);
		
		// 判断如果不是最新的记录，不可被编辑
		// 因为可能有其他人在你当前表格未刷新的情况下已经更新过了
		$res = Bpjg_zhongricheng_main::select('updated_at')
			->where('id', $id)
			->first();
		$res_updated_at = date('Y-m-d H:i:s', strtotime($res['updated_at']));

		if ($updated_at != $res_updated_at) {
			return 0;
		}
		
		// 尝试更新
		try	{
			DB::beginTransaction();
			$result = Bpjg_zhongricheng_main::where('id', $id)
				->update([
					'xianti'			=> $xianti,
					'qufen'				=> $qufen,
					'jizhongming'		=> $jizhongming,
					'pinfan' 			=> $pinfan,
					'pinming'			=> $pinming,
					'xuqiushuliang'		=> $xuqiushuliang,
					'leibie'			=> $leibie,
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			DB::rollBack();
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;

	}	


    /**
     * zrcDelete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcDelete(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('tableselect1');

		try	{
			$result = Bpjg_zhongricheng_zrc::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		Cache::flush();
		return $result;
	}


    /**
     * mainDelete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainDelete(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('tableselect2');

		try	{
			$result = Bpjg_zhongricheng_main::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		Cache::flush();
		return $result;
	}	
	
	
    /**
     * zrcImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcImport(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 接收文件
		$fileCharater = $request->file('myfile');
		// dd($fileCharater);
 
		if ($fileCharater->isValid()) { //括号里面的是必须加的哦
			//如果括号里面的不加上的话，下面的方法也无法调用的

			//获取文件的扩展名 
			$ext = $fileCharater->extension();
			// dd($ext);
			if ($ext != 'xls' && $ext != 'xlsx') {
				return 0;
			}

			//获取文件的绝对路径
			// $path = $fileCharater->path();
			// dd($path);

			//定义文件名
			// $filename = date('Y-m-d-h-i-s').'.'.$ext;
			$filename = 'zrcfx_zrcimport.'.$ext;
			// dd($filename);

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', $filename);
			// dd($filename);
		} else {
			return 0;
		}
		
		// 导入excel文件内容
		try {
			$ret = Excel::import(new zrcfx_zrcImport, 'excel/'.$filename);
			// dd($ret);
			$result = 1;
		} catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		} finally {
			Storage::delete('excel/'.$filename);
		}
		
		return $result;
	}
	
	
    /**
     * mainImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainImport(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 接收文件
		$fileCharater = $request->file('myfile');
		// dd($fileCharater);
 
		if ($fileCharater->isValid()) { //括号里面的是必须加的哦
			//如果括号里面的不加上的话，下面的方法也无法调用的

			//获取文件的扩展名 
			$ext = $fileCharater->extension();
			// dd($ext);
			if ($ext != 'xls' && $ext != 'xlsx') {
				return 0;
			}

			//获取文件的绝对路径
			// $path = $fileCharater->path();
			// dd($path);

			//定义文件名
			// $filename = date('Y-m-d-h-i-s').'.'.$ext;
			$filename = 'zrcfx_mainimport.'.$ext;
			// dd($filename);

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', $filename);
			// dd($filename);
		} else {
			return 0;
		}
		
		// 导入excel文件内容
		try {
			// $ret = Excel::import(new zrcfx_mainImport, 'excel/'.$filename);
			$ret = Excel::import(new zrcfx_mainImport, 'excel/'.$filename);
			// dd($ret);
			$result = 1;
		} catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			// $result = 'Message: ' .$e->getMessage();
			$result = 0;
		} finally {
			Storage::delete('excel/'.$filename);
		}
		
		return $result;
	}
	
	
    /**
     * zrcDownload
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcDownload(Request $request)
    {
		return Storage::download('download/zrcfx_zrcimport.xlsx');
	}
	

    /**
     * mainDownload
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainDownload(Request $request)
    {
		return Storage::download('download/zrcfx_mainimport.xlsx');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
