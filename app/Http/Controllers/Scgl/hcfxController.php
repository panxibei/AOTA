<?php
// 耗材分析
namespace App\Http\Controllers\Scgl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Scgl\Scgl_hcfx_tuopan;
use App\Models\Scgl\Scgl_hcfx_relation;
use App\Models\Scgl\Scgl_hcfx_zrcfx;
use App\Models\Scgl\Scgl_hcfx_result1;
use App\Models\Scgl\Scgl_hcfx_result2;


use App\Models\Bpjg\Bpjg_zhongricheng_zrcfx;
use App\Models\Bpjg\Bpjg_zhongricheng_result;
use App\Models\Admin\Config;
// use App\Models\Admin\User;
// use Cookie;
use DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Scgl\hcfx_relationImport;
use App\Exports\Scgl\hcfx_relationExport;
use App\Imports\Scgl\hcfx_zrcfxImport;
use App\Exports\Scgl\hcfx_resultExport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class hcfxController extends Controller
{
    //
	public function hcfxIndex () {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
		return view('scgl.hcfx', $share);
		
	}
	
	
    /**
     * guigeGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function guigeGets(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$perPage = 10000;
		$page = 1;

		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$result = Scgl_hcfx_tuopan::limit(5000)
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		
		return $result;
    }	
	
    /**
     * relationGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationGets(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$tuopanxinghao_filter = $request->input('tuopanxinghao_filter');
		
		// $usecache = $request->input('usecache');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
	
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
			$result = Scgl_hcfx_relation::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('updated_at', $qcdate_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($tuopanxinghao_filter, function ($query) use ($tuopanxinghao_filter) {
					return $query->where('tuopanxinghao', '=', $tuopanxinghao_filter);
				})
				->limit(5000)
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		
		return $result;
    }	
	
	
    /**
     * resultGets1
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resultGets1(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;

		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
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
			$result = Scgl_hcfx_result1::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					// return $query->whereBetween('updated_at', $qcdate_filter);
					return $query->where('suoshuriqi', $qcdate_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				// ->when($pinfan_filter, function ($query) use ($pinfan_filter) {
				// 	return $query->where('pinfan', 'like', '%'.$pinfan_filter.'%');
				// })
				// ->when($pinming_filter, function ($query) use ($pinming_filter) {
				// 	return $query->where('pinming', 'like', '%'.$pinming_filter.'%');
				// })
				// ->when($leibie_filter, function ($query) use ($leibie_filter) {
				// 	return $query->where('leibie', '=', $leibie_filter);
				// })
				->limit(5000)
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		
		return $result;
    }
	
    /**
     * resultGets2
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resultGets2(Request $request)
    {
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;

		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
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
			$result = Scgl_hcfx_result2::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					// return $query->whereBetween('updated_at', $qcdate_filter);
					return $query->where('suoshuriqi', $qcdate_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				// ->when($pinfan_filter, function ($query) use ($pinfan_filter) {
				// 	return $query->where('pinfan', 'like', '%'.$pinfan_filter.'%');
				// })
				// ->when($pinming_filter, function ($query) use ($pinming_filter) {
				// 	return $query->where('pinming', 'like', '%'.$pinming_filter.'%');
				// })
				// ->when($leibie_filter, function ($query) use ($leibie_filter) {
				// 	return $query->where('leibie', '=', $leibie_filter);
				// })
				->limit(5000)
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		
		return $result;
    }

	
    /**
     * guigeCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function guigeCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$pinming = $request->input('pinming');
		$daima = $request->input('daima');
		$guige = $request->input('guige');
		$created_at = date('Y-m-d H:i:s');
		$updated_at = date('Y-m-d H:i:s');

		$s[0]['pinming'] = $pinming;
		$s[0]['daima'] = $daima;
		$s[0]['guige'] = $guige;
		$s[0]['created_at'] = $created_at;
		$s[0]['updated_at'] = $updated_at;
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($s as $value) {
				// Bpjg_zhongricheng_main::create($value);
			// }
			Scgl_hcfx_tuopan::insert($s);

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
     * relationCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$piliangluru = $request->input('piliangluru');
		$created_at = date('Y-m-d H:i:s');
		$updated_at = date('Y-m-d H:i:s');

		foreach ($piliangluru as $key => $value) {
			// $s[$key]['xianti'] = $xianti;
			// $s[$key]['qufen'] = $qufen;
			$s[$key]['created_at'] = $created_at;
			$s[$key]['updated_at'] = $updated_at;

			$s[$key]['jizhongming'] = $value['jizhongming'];
			$s[$key]['tuopanxinghao'] = $value['tuopanxinghao'];
			$s[$key]['tai_per_tuo'] = $value['tai_per_tuo'];
		}
		// dd($s);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($s as $value) {
				// Bpjg_zhongricheng_main::create($value);
			// }
			Scgl_hcfx_relation::insert($s);

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
     * relationUpdate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$jizhongming = $request->input('jizhongming');
		$tuopanxinghao = $request->input('tuopanxinghao');
		$tai_per_tuo = $request->input('tai_per_tuo');
		// $created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		
		// dd($updated_at);
		
		// 判断如果不是最新的记录，不可被编辑
		// 因为可能有其他人在你当前表格未刷新的情况下已经更新过了
		$res = Scgl_hcfx_relation::select('updated_at')
			->where('id', $id)
			->first();
		$res_updated_at = date('Y-m-d H:i:s', strtotime($res['updated_at']));

		if ($updated_at != $res_updated_at) {
			return 0;
		}
		
		// 尝试更新
		try	{
			DB::beginTransaction();
			$result = Scgl_hcfx_relation::where('id', $id)
				->update([
					'jizhongming'		=> $jizhongming,
					'tuopanxinghao'		=> $tuopanxinghao,
					'tai_per_tuo'			=> $tai_per_tuo,
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
     * relationDelete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationDelete (Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('tableselect_relation');

		try	{
			$result = Scgl_hcfx_relation::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		Cache::flush();
		return $result;
	}	
	
	
	
    /**
     * zrcfxImport
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
			// 先清空表
			Scgl_hcfx_zrcfx::truncate();
			
			$ret = Excel::import(new hcfx_zrcfxImport, 'excel/'.$filename);
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
     * relationImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationImport(Request $request)
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
			$filename = 'scgl_hcfx_relationimport.'.$ext;
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
			// 先清空表
			Scgl_hcfx_relation::truncate();
			
			$ret = Excel::import(new hcfx_relationimport, 'excel/'.$filename);
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
     * zrcDownload 导入模板下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function zrcDownload(Request $request)
    {
		return Storage::download('download/scgl_hcfx_zrcimport.xlsx', 'MoBan_ScglHcfxZhongRiCheng.xlsx');
	}
	

    /**
     * relationDownload 导入模板下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationDownload(Request $request)
    {
		return Storage::download('download/scgl_hcfx_relationimport.xlsx', 'MoBan_ScglHcfxRelation.xlsx');
	}
	
	
    /**
     * relationExport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relationExport(Request $request)
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

		$Scgl_hcfx_relation = Scgl_hcfx_relation::select('id', 'jizhongming', 'tuopanxinghao', 'tai_per_tuo', 'updated_at')
			->whereBetween('updated_at', [$queryfilter_datefrom, $queryfilter_dateto])
			->get()->toArray();
		// dd($Scgl_hcfx_relation);

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
		$title[] = ['id', '机种', '托盘型号', '台/托', '更新日期'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $Scgl_hcfx_relation);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new hcfx_relationExport($data), 'scgl_hcfx_relation_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
	}


    /**
     * qcreportExport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resultExport(Request $request)
    {
		// if (! $request->ajax()) { return null; }
		
		$queryfilter = $request->input('queryfilter');
		// dd($queryfilter);
		
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

		$Scgl_hcfx_result1 = Scgl_hcfx_result1::select('suoshuriqi as suoshuriqi1', 'jizhongming as jizhongming1', 'chanliang as chanliang1', 'tuopanxinghao as tuopanxinghao1', 'tai_per_tuo as tai_per_tuo1', 'lilun_tuo as lilun_tuo1', 'shiji_tuo as shiji_tuo1')
			->where('suoshuriqi', $queryfilter)
			->get()->toArray();
		// dd($Scgl_hcfx_result1);

		$Scgl_hcfx_result2 = Scgl_hcfx_result2::select('suoshuriqi as suoshuriqi2', 'jizhongming as jizhongming2', 'chanliang as chanliang2', 'tuopanxinghao as tuopanxinghao2', 'tai_per_tuo as tai_per_tuo2', 'lilun_tuo as lilun_tuo2', 'shiji_tuo as shiji_tuo2')
			->where('suoshuriqi', $queryfilter)
			->get()->toArray();
		// dd($Scgl_hcfx_result2);


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
		// $title1[] = ['所属日期', '机种', '1号-20号产量（计划）', '台/托', '理论（托）', '实际（托）'];

		// 合并Excel的标题和数据为一个整体
		// $data1 = array_merge($title1, $Scgl_hcfx_result1);

		// $title2[] = ['所属日期', '机种', '21号-31号产量（计划）', '台/托', '理论（托）', '实际（托）'];

		// $dash[] = ['', '', '', '', '', ''];

		// $data = array_merge($title1, $Scgl_hcfx_result1, $dash, $title2, $Scgl_hcfx_result2);


		$title[] = ['所属日期', '机种', '1号-20号产量（计划）', '托盘型号', '台/托', '理论（托）', '实际（托）', '', '', '所属日期', '机种', '21号-31号产量（计划）', '托盘型号', '台/托', '理论（托）', '实际（托）'];

		$s = [];
		foreach ($Scgl_hcfx_result1 as $key => $value) {
			$s[$key] = array_merge($value, ['', ''], $Scgl_hcfx_result2[$key]);
		}
		// dd($s);
		$data = array_merge($title, $s);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new hcfx_resultExport($data, $queryfilter), 'scgl_hcfx_result_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
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
		
		$suoshuriqi = $request->input('suoshuriqi_filter');
		
		$created_at = date('Y-m-d H:i:s');
		$updated_at = date('Y-m-d H:i:s');

		$res1 = DB::select('
			SELECT "' . $suoshuriqi . '" AS suoshuriqi,
				LEFT(A.jizhongming, 7) AS jizhongming,
				B.tuopanxinghao,
				B.tai_per_tuo,
				SUM(A.d1+A.d2+A.d3+A.d4+A.d5+A.d6+A.d7+A.d8+A.d9+A.d10+A.d11+A.d12+A.d13+A.d14+A.d15+A.d16+A.d17+A.d18+A.d19+A.d20) AS chanliang,
				SUM(A.d1+A.d2+A.d3+A.d4+A.d5+A.d6+A.d7+A.d8+A.d9+A.d10+A.d11+A.d12+A.d13+A.d14+A.d15+A.d16+A.d17+A.d18+A.d19+A.d20)/B.tai_per_tuo AS lilun_tuo,
				CEILING(SUM(A.d1+A.d2+A.d3+A.d4+A.d5+A.d6+A.d7+A.d8+A.d9+A.d10+A.d11+A.d12+A.d13+A.d14+A.d15+A.d16+A.d17+A.d18+A.d19+A.d20)/B.tai_per_tuo) AS shiji_tuo,
				"' . $created_at . '" AS created_at, "' . $updated_at . '" AS updated_at 
			FROM scgl_hcfx_zrcfxs AS A LEFT JOIN scgl_hcfx_relations AS B
			ON A.jizhongming=B.jizhongming
			GROUP BY LEFT(A.jizhongming, 7)
		');
		// dd($res1);

		$res2 = DB::select('
			SELECT "' . $suoshuriqi . '" AS suoshuriqi,
				LEFT(A.jizhongming, 7) AS jizhongming,
				B.tuopanxinghao,
				B.tai_per_tuo,
				SUM(A.d21+A.d22+A.d23+A.d24+A.d25+A.d26+A.d27+A.d28+A.d29+A.d30+A.d31) AS chanliang,
				SUM(A.d21+A.d22+A.d23+A.d24+A.d25+A.d26+A.d27+A.d28+A.d29+A.d30+A.d31)/B.tai_per_tuo AS lilun_tuo,
				CEILING(SUM(A.d21+A.d22+A.d23+A.d24+A.d25+A.d26+A.d27+A.d28+A.d29+A.d30+A.d31)/B.tai_per_tuo) AS shiji_tuo,
				"' . $created_at . '" AS created_at, "' . $updated_at . '" AS updated_at 
			FROM scgl_hcfx_zrcfxs AS A LEFT JOIN scgl_hcfx_relations AS B
			ON A.jizhongming=B.jizhongming
			GROUP BY LEFT(A.jizhongming, 7)
		');
		// dd($res2);
		
		$res_2_array1 = object_to_array($res1);
		$res_2_array2 = object_to_array($res2);
		// dd($res_2_array1);
		// return $res_2_array1;
		// return gettype($res_2_array1[0]);

		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// $result = Bpjg_zhongricheng_result::whereBetween('suoshuriqi', $suoshuriqi)->delete();
			Scgl_hcfx_result1::where('suoshuriqi', $suoshuriqi)->delete();
			Scgl_hcfx_result2::where('suoshuriqi', $suoshuriqi)->delete();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($res_2_array as $value) {
				// dump($value);
				// Bpjg_zhongricheng_result::create($value);
			// }
			Scgl_hcfx_result1::insert($res_2_array1);
			Scgl_hcfx_result2::insert($res_2_array2);

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
		
		
		
		
		// --------------------------------------------------------------------------------------
		// 以上用SQL解决问题，以下用PHP数组计算，暂未用，保留。
		
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
					$res[$key2]['suoshuriqi'] = $suoshuriqi;

					
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
			
			// $result = Bpjg_zhongricheng_result::whereBetween('suoshuriqi', $suoshuriqi)->delete();
			$result = Bpjg_zhongricheng_result::where('suoshuriqi', $suoshuriqi)->delete();
			
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
