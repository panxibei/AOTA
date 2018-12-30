<?php
// 中日程分析
namespace App\Http\Controllers\Bpjg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Bpjg\Bpjg_zhongricheng_zrc;
use App\Models\Bpjg\Bpjg_zhongricheng_zrcfx;
use App\Models\Bpjg\Bpjg_zhongricheng_relation;
use App\Models\Bpjg\Bpjg_zhongricheng_result;
use App\Models\Bpjg\Bpjg_zhongricheng_main;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Bpjg\zrcfx_zrcImport; //暂留
use App\Imports\Bpjg\zrcfx_zrcfxImport;
use App\Imports\Bpjg\zrcfx_mainImport;
use App\Exports\Bpjg\zrcfx_mainExport;

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
     * resultGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resultGets(Request $request)
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
		// $xianti_filter = $request->input('xianti_filter');
		// $jizhongming_filter = $request->input('jizhongming_filter');
		// $pinfan_filter = $request->input('pinfan_filter');
		// $pinming_filter = $request->input('pinming_filter');
		// $leibie_filter = $request->input('leibie_filter');
		
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
		} else {                              //如果cache里面没有        
			$result = Bpjg_zhongricheng_result::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('updated_at', $qcdate_filter);
				})
				// ->when($xianti_filter, function ($query) use ($xianti_filter) {
					// return $query->where('xianti', '=', $xianti_filter);
				// })
				// ->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					// return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				// })
				// ->when($pinfan_filter, function ($query) use ($pinfan_filter) {
					// return $query->where('pinfan', 'like', '%'.$pinfan_filter.'%');
				// })
				// ->when($pinming_filter, function ($query) use ($pinming_filter) {
					// return $query->where('pinming', 'like', '%'.$pinming_filter.'%');
				// })
				// ->when($leibie_filter, function ($query) use ($leibie_filter) {
					// return $query->where('leibie', '=', $leibie_filter);
				// })
				->limit(5000)
				->orderBy('updated_at', 'asc')
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
     * zrcImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcfxImport(Request $request)
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
			$filename = 'zrcfx_zrcfximport.'.$ext;
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
			$ret = Excel::import(new zrcfx_zrcfxImport, 'excel/'.$filename);
			// dd($ret);
			$result = 1;
		} catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 'Message: ' .$e->getMessage();
			// $result = 0;
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
     * zrcDownload 导入模板下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcDownload(Request $request)
    {
		return Storage::download('download/zrcfx_zrcimport.xlsx', 'MoBan_ZhongRiCheng.xlsx');
	}
	

    /**
     * mainDownload 导入模板下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainDownload(Request $request)
    {
		return Storage::download('download/zrcfx_mainimport.xlsx', 'MoBan_BuPin.xlsx');
	}
	
	
    /**
     * qcreportExport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mainExport(Request $request)
    {
		// if (! $request->ajax()) { return null; }
		
		$queryfilter_datefrom = $request->input('queryfilter_datefrom');
		$queryfilter_dateto = $request->input('queryfilter_dateto');
		// dd($queryfilter_datefrom);
		
		// 获取扩展名配置值
		// $config = Config::select('cfg_name', 'cfg_value')
			// ->pluck('cfg_value', 'cfg_name')->toArray();

		$EXPORTS_EXTENSION_TYPE = 'xlsx'; // $config['EXPORTS_EXTENSION_TYPE'];
		// $FILTERS_USER_NAME = $config['FILTERS_USER_NAME'];
		// $FILTERS_USER_EMAIL = $config['FILTERS_USER_EMAIL'];
		// $FILTERS_DATEFROM = ''; // $config['FILTERS_USER_LOGINTIME_DATEFROM'];
		// $FILTERS_DATETO = ''; // $config['FILTERS_USER_LOGINTIME_DATETO'];

        // 获取用户信息
		// Excel数据，最好转换成数组，以便传递过去
		// $queryfilter_name = $FILTERS_USER_NAME || '';
		// $queryfilter_email = $FILTERS_USER_EMAIL || '';

		// $queryfilter_datefrom = strtotime($queryfilter_datefrom) ? $queryfilter_datefrom : '1970-01-01';
		// $queryfilter_dateto = strtotime($queryfilter_dateto) ? $queryfilter_dateto : '9999-12-31';

		$Bpjg_zhongricheng_main = Bpjg_zhongricheng_main::select('id', 'riqi', 'xianti', 'qufen', 'jizhongming', 'pinfan', 'pinming', 'leibie', 'xuqiushuliang', 'zongshu', 'shuliang', 'created_at')
			->whereBetween('riqi', [$queryfilter_datefrom, $queryfilter_dateto])
			->get()->toArray();
		// dd($Bpjg_zhongricheng_main);

        // 示例数据，不能直接使用，只能把数组变成Exports类导出后才有数据
		// $cellData = [
            // ['学号','姓名','成绩'],
            // ['10001','AAAAA','199'],
            // ['10002','BBBBB','192'],
            // ['10003','CCCCC','195'],
            // ['10004','DDDDD','189'],
            // ['10005','EEEEE','196'],
        // ];

		// Excel标题第一行，可修改为任意名字，包括中文
		$title[] = ['id', '日期', '线体', '区分', '机种名', '品番', '品名', '类别', '需求数量', '总数', '数量', '创建日期'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $Bpjg_zhongricheng_main);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new zrcfx_mainExport($data), 'bpjg_zrcfx_main_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
	}	
	
	
    /**
     * zrcfxFunction 中日程分析程序
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcfxFunction(Request $request)
    {
		if (! $request->ajax()) return null;
		
		$qcdate_filter = $request->input('qcdate_filter');

		
		// 1.读取 bpjg_zhongricheng_zrcfxs 表
		$res_zrcfx = Bpjg_zhongricheng_zrcfx::select('jizhongming',
			'd1', 'd2', 'd3', 'd4', 'd5', 'd6', 'd7', 'd8', 'd9', 'd10',
			'd11', 'd12', 'd13', 'd14', 'd15', 'd16', 'd17', 'd18', 'd19', 'd20',
			'd21', 'd22', 'd23', 'd24', 'd25', 'd26', 'd27', 'd28', 'd29', 'd30', 'd31')
			->get();
		
		// 2.读取 bpjg_zhongricheng_relations 表
		$res_relation = Bpjg_zhongricheng_relation::select('jizhongming', 'pinfan', 'pinming', 'xuqiushuliang')
			->get();
		
		// 3.计算每天的数量，相同部品计算“每天合计”及同时计算“一个月内的总数”。
		$res = [];
		
		foreach ($res_zrcfx as $key1 => $value1) {
			if (is_null($value1['d1'])) $value1['d1'] = 0;
			if (is_null($value1['d2'])) $value1['d2'] = 0;
			if (is_null($value1['d3'])) $value1['d3'] = 0;
			if (is_null($value1['d4'])) $value1['d4'] = 0;
			if (is_null($value1['d5'])) $value1['d5'] = 0;
			if (is_null($value1['d6'])) $value1['d6'] = 0;
			if (is_null($value1['d7'])) $value1['d7'] = 0;
			if (is_null($value1['d8'])) $value1['d8'] = 0;
			if (is_null($value1['d9'])) $value1['d9'] = 0;
			if (is_null($value1['d10'])) $value1['d10'] = 0;
			if (is_null($value1['d11'])) $value1['d11'] = 0;
			if (is_null($value1['d12'])) $value1['d12'] = 0;
			if (is_null($value1['d13'])) $value1['d13'] = 0;
			if (is_null($value1['d14'])) $value1['d14'] = 0;
			if (is_null($value1['d15'])) $value1['d15'] = 0;
			if (is_null($value1['d16'])) $value1['d16'] = 0;
			if (is_null($value1['d17'])) $value1['d17'] = 0;
			if (is_null($value1['d18'])) $value1['d18'] = 0;
			if (is_null($value1['d19'])) $value1['d19'] = 0;
			if (is_null($value1['d20'])) $value1['d20'] = 0;
			if (is_null($value1['d21'])) $value1['d21'] = 0;
			if (is_null($value1['d22'])) $value1['d22'] = 0;
			if (is_null($value1['d23'])) $value1['d23'] = 0;
			if (is_null($value1['d24'])) $value1['d24'] = 0;
			if (is_null($value1['d25'])) $value1['d25'] = 0;
			if (is_null($value1['d26'])) $value1['d26'] = 0;
			if (is_null($value1['d27'])) $value1['d27'] = 0;
			if (is_null($value1['d28'])) $value1['d28'] = 0;
			if (is_null($value1['d29'])) $value1['d29'] = 0;
			if (is_null($value1['d30'])) $value1['d30'] = 0;
			
			foreach ($res_relation as $key2 => $value2) {
				if ($value2['jizhongming'] == $value1['jizhongming']) {
					$zhongshu = 0;
					
					$res[$key2]['pinfan'] = $value2['pinfan'];
					$res[$key2]['pinming'] = $value2['pinming'];


					!isset($res[$key2]['d1']) ? $res[$key2]['d1'] = $value1['d1'] * $value2['xuqiushuliang'] : $res[$key2]['d1'] += $value1['d1'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d1'];
					!isset($res[$key2]['d2']) ? $res[$key2]['d2'] = $value1['d2'] * $value2['xuqiushuliang'] : $res[$key2]['d2'] += $value1['d2'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d2'];
					!isset($res[$key2]['d3']) ? $res[$key2]['d3'] = $value1['d3'] * $value2['xuqiushuliang'] : $res[$key2]['d3'] += $value1['d3'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d3'];
					!isset($res[$key2]['d4']) ? $res[$key2]['d4'] = $value1['d4'] * $value2['xuqiushuliang'] : $res[$key2]['d4'] += $value1['d4'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d4'];
					!isset($res[$key2]['d5']) ? $res[$key2]['d5'] = $value1['d5'] * $value2['xuqiushuliang'] : $res[$key2]['d5'] += $value1['d5'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d5'];
					!isset($res[$key2]['d6']) ? $res[$key2]['d6'] = $value1['d6'] * $value2['xuqiushuliang'] : $res[$key2]['d6'] += $value1['d6'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d6'];
					!isset($res[$key2]['d7']) ? $res[$key2]['d7'] = $value1['d7'] * $value2['xuqiushuliang'] : $res[$key2]['d7'] += $value1['d7'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d7'];
					!isset($res[$key2]['d8']) ? $res[$key2]['d8'] = $value1['d8'] * $value2['xuqiushuliang'] : $res[$key2]['d8'] += $value1['d8'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d8'];
					!isset($res[$key2]['d9']) ? $res[$key2]['d9'] = $value1['d9'] * $value2['xuqiushuliang'] : $res[$key2]['d9'] += $value1['d9'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d9'];
					!isset($res[$key2]['d10']) ? $res[$key2]['d10'] = $value1['d10'] * $value2['xuqiushuliang'] : $res[$key2]['d10'] += $value1['d10'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d10'];

					!isset($res[$key2]['d11']) ? $res[$key2]['d11'] = $value1['d11'] * $value2['xuqiushuliang'] : $res[$key2]['d11'] += $value1['d11'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d11'];
					!isset($res[$key2]['d12']) ? $res[$key2]['d12'] = $value1['d12'] * $value2['xuqiushuliang'] : $res[$key2]['d12'] += $value1['d12'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d12'];
					!isset($res[$key2]['d13']) ? $res[$key2]['d13'] = $value1['d13'] * $value2['xuqiushuliang'] : $res[$key2]['d13'] += $value1['d13'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d13'];
					!isset($res[$key2]['d14']) ? $res[$key2]['d14'] = $value1['d14'] * $value2['xuqiushuliang'] : $res[$key2]['d14'] += $value1['d14'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d14'];
					!isset($res[$key2]['d15']) ? $res[$key2]['d15'] = $value1['d15'] * $value2['xuqiushuliang'] : $res[$key2]['d15'] += $value1['d15'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d15'];
					!isset($res[$key2]['d16']) ? $res[$key2]['d16'] = $value1['d16'] * $value2['xuqiushuliang'] : $res[$key2]['d16'] += $value1['d16'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d16'];
					!isset($res[$key2]['d17']) ? $res[$key2]['d17'] = $value1['d17'] * $value2['xuqiushuliang'] : $res[$key2]['d17'] += $value1['d17'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d17'];
					!isset($res[$key2]['d18']) ? $res[$key2]['d18'] = $value1['d18'] * $value2['xuqiushuliang'] : $res[$key2]['d18'] += $value1['d18'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d18'];
					!isset($res[$key2]['d19']) ? $res[$key2]['d19'] = $value1['d19'] * $value2['xuqiushuliang'] : $res[$key2]['d19'] += $value1['d19'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d19'];
					!isset($res[$key2]['d20']) ? $res[$key2]['d20'] = $value1['d20'] * $value2['xuqiushuliang'] : $res[$key2]['d20'] += $value1['d20'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d20'];

					!isset($res[$key2]['d21']) ? $res[$key2]['d21'] = $value1['d21'] * $value2['xuqiushuliang'] : $res[$key2]['d21'] += $value1['d21'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d21'];
					!isset($res[$key2]['d22']) ? $res[$key2]['d22'] = $value1['d22'] * $value2['xuqiushuliang'] : $res[$key2]['d22'] += $value1['d22'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d22'];
					!isset($res[$key2]['d23']) ? $res[$key2]['d23'] = $value1['d23'] * $value2['xuqiushuliang'] : $res[$key2]['d23'] += $value1['d23'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d23'];
					!isset($res[$key2]['d24']) ? $res[$key2]['d24'] = $value1['d24'] * $value2['xuqiushuliang'] : $res[$key2]['d24'] += $value1['d24'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d24'];
					!isset($res[$key2]['d25']) ? $res[$key2]['d25'] = $value1['d25'] * $value2['xuqiushuliang'] : $res[$key2]['d25'] += $value1['d25'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d25'];
					!isset($res[$key2]['d26']) ? $res[$key2]['d26'] = $value1['d26'] * $value2['xuqiushuliang'] : $res[$key2]['d26'] += $value1['d26'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d26'];
					!isset($res[$key2]['d27']) ? $res[$key2]['d27'] = $value1['d27'] * $value2['xuqiushuliang'] : $res[$key2]['d27'] += $value1['d27'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d27'];
					!isset($res[$key2]['d28']) ? $res[$key2]['d28'] = $value1['d28'] * $value2['xuqiushuliang'] : $res[$key2]['d28'] += $value1['d28'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d28'];
					!isset($res[$key2]['d29']) ? $res[$key2]['d29'] = $value1['d29'] * $value2['xuqiushuliang'] : $res[$key2]['d29'] += $value1['d29'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d29'];
					!isset($res[$key2]['d30']) ? $res[$key2]['d30'] = $value1['d30'] * $value2['xuqiushuliang'] : $res[$key2]['d30'] += $value1['d30'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d30'];
					!isset($res[$key2]['d31']) ? $res[$key2]['d31'] = $value1['d31'] * $value2['xuqiushuliang'] : $res[$key2]['d31'] += $value1['d31'] * $value2['xuqiushuliang'];
					$zhongshu += $res[$key2]['d31'];

					
					$res[$key2]['zongshu'] = $zhongshu;
					
				}
				
			}
			
		}
		// dump($res);
		
		// 4.合并数组中相同部品项
		$tmp_arr_in = [];
		$tmp_arr_out = [];

		foreach ($res as $k => $v) {
			if (in_array($v['pinfan'], $tmp_arr_in)) { //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
				$tmp_arr_out[] = $res[$k];
				unset($res[$k]);
			} else {
				$tmp_arr_in[] = $v['pinfan'];
			}
		}
		// dump($tmp_arr_out);
		
		foreach ($res as $key => $value) {
			foreach ($tmp_arr_out as $v) {
				if ($v['pinfan'] == $value['pinfan']) {
					$res[$key]['d1'] += $v['d1'];$res[$key]['d2'] += $v['d2'];$res[$key]['d3'] += $v['d3'];
					$res[$key]['d4'] += $v['d4'];$res[$key]['d5'] += $v['d5'];$res[$key]['d6'] += $v['d6'];
					$res[$key]['d7'] += $v['d7'];$res[$key]['d8'] += $v['d8'];$res[$key]['d9'] += $v['d9'];
					$res[$key]['d10'] += $v['d10'];$res[$key]['d11'] += $v['d11'];$res[$key]['d12'] += $v['d12'];
					$res[$key]['d13'] += $v['d13'];$res[$key]['d14'] += $v['d14'];$res[$key]['d15'] += $v['d15'];
					$res[$key]['d16'] += $v['d16'];$res[$key]['d17'] += $v['d17'];$res[$key]['d18'] += $v['d18'];
					$res[$key]['d19'] += $v['d19'];$res[$key]['d20'] += $v['d20'];$res[$key]['d21'] += $v['d21'];
					$res[$key]['d22'] += $v['d22'];$res[$key]['d23'] += $v['d23'];$res[$key]['d24'] += $v['d24'];
					$res[$key]['d25'] += $v['d25'];$res[$key]['d26'] += $v['d26'];$res[$key]['d27'] += $v['d27'];
					$res[$key]['d28'] += $v['d28'];$res[$key]['d29'] += $v['d29'];$res[$key]['d30'] += $v['d30'];
					$res[$key]['d31'] += $v['d31'];
				}
			}
		}
		// return $res;
		
		// 5.导入结果表 bpjg_zhongricheng_results
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			$result = Bpjg_zhongricheng_result::whereBetween('updated_at', $qcdate_filter)->delete();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			foreach ($res as $value) {
				Bpjg_zhongricheng_result::create($value);
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
		
		// return $res;
		Cache::flush();
		return $result;
	
	}	
	
	
	
	
	
	
	
	
}
