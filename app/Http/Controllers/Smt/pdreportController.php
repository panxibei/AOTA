<?php

namespace App\Http\Controllers\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Smt\Smt_mpoint;
use App\Models\Smt\Smt_pdreport;
use DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Smt\mpointExport;
use App\Exports\Smt\pdreportExport;
use App\Imports\Smt\mpointImport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class pdreportController extends Controller
{
    //
	public function pdreportIndex ()
	{
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
		return view('smt.pdreport', $share);
		
	}

    //
	public function mpoint ()
	{
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
		return view('smt.mpoint', $share);
		
	}
	
	/**
	 * mpointGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointGets(Request $request)
	{
		if (! $request->ajax()) return null;

		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;

		$dailydate_filter = $request->input('dailydate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		
		$mpoint = Smt_mpoint::when($dailydate_filter, function ($query) use ($dailydate_filter) {
				return $query->whereBetween('created_at', $dailydate_filter);
			})
			->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
			})
			->orderBy('created_at', 'desc')
			->paginate($perPage, ['*'], 'page', $page);

		return $mpoint;
	}
	
	/**
	 * mpointCreate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointCreate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$mpoint = $request->only(
			'jizhongming',
			'pinming',
			'gongxu',
			'diantai',
			'pinban'
			// 'created_at'
		);
		// dd($mpoint);

		// 写入数据库
		try	{
			// $result = DB::table('mpoints')->insert([
			$result = Smt_mpoint::create([
				'jizhongming'	=> $mpoint['jizhongming'],
				'pinming'		=> $mpoint['pinming'],
				'gongxu'			=> $mpoint['gongxu'],
				'diantai'		=> $mpoint['diantai'],
				'pinban'		=> $mpoint['pinban']
				// 'created_at'	=> date("Y-m-d H:i:s",time())
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}

	/**
	 * mpointUpdate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointUpdate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$mpoint = $request->only(
			'jizhongming',
			'pinming',
			'gongxu',
			'diantai',
			'pinban',
			'id'
		);
		// dd($mpoint);

		// 写入数据库
		try	{
			// $result = DB::table('mpoints')
			$result = Smt_mpoint::where('id', $mpoint['id'])
				->update([
					'jizhongming'	=> $mpoint['jizhongming'],
					'pinming'		=> $mpoint['pinming'],
					'gongxu'			=> $mpoint['gongxu'],
					'diantai'		=> $mpoint['diantai'],
					'pinban'		=> $mpoint['pinban'],
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}
	
	
	/**
	 * mpointDelete
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointDelete(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->only('tableselect');

		// $result = DB::table('mpoints')->whereIn('id', $id)->delete();
		$result = Smt_mpoint::whereIn('id', $id)->delete();
		return $result;

	}
	
	
	/**
	 * mpointDownload 导入模板下载
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointDownload(Request $request)
	{
		return Storage::download('download/smt_mpointimport.xlsx', 'MoBan_Mpoint.xlsx');
	}
	
	
	/**
	 * getJizhongming
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getJizhongming(Request $request)
	{
		if (! $request->ajax()) return null;

		$jizhongming = $request->input('jizhongming');

		$result = Smt_mpoint::where('jizhongming', $jizhongming)
			->get();

		return $result;

	}
	
	
	/**
	 * dailyreportCreate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dailyreportCreate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$dailyreport = $request->only(
			'xianti',
			'banci',
			'jizhongming',
			'spno',
			'pinming',
			'lotshu',
			'meimiao',
			'meishu',
			'gongxu'
		);
		// dd($dailyreport['banci']);
		
		//读取点/枚
		$t = Smt_mpoint::select('diantai', 'pinban')
			->where('jizhongming', $dailyreport['jizhongming'])
			->where('pinming', $dailyreport['pinming'])
			->where('gongxu', $dailyreport['gongxu'])
			->first();
		// dd($t);
		
		if ($t == null) return 0;
		
		$dianmei = $t->diantai * $t->pinban;
		$taishu = $dailyreport['meishu'] * $t->pinban;
		$chajiandianshu = $t->diantai * $dailyreport['meishu'];
		$jiadonglv = $dailyreport['meishu'] * $dailyreport['meimiao'] / 43200;

		// 写入数据库
		try	{
			// $result = DB::table('dailyreports')->insert([
			$result = Smt_pdreport::create([
				'xianti'		=> $dailyreport['xianti'],
				'banci'			=> $dailyreport['banci'],
				'jizhongming'	=> $dailyreport['jizhongming'],
				'spno'			=> $dailyreport['spno'],
				'pinming'		=> $dailyreport['pinming'],
				'lotshu'		=> $dailyreport['lotshu'],
				'meimiao'		=> $dailyreport['meimiao'],
				'meishu'		=> $dailyreport['meishu'],
				'gongxu'		=> $dailyreport['gongxu'],
				'dianmei'		=> $dianmei,
				'meimiao'		=> $dailyreport['meimiao'],
				'meishu'		=> $dailyreport['meishu'],
				'taishu'		=> $taishu,
				'lotcan'		=> 0,
				'chajiandianshu'=> $chajiandianshu,
				'jiadonglv'		=> $jiadonglv
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}
	
	
	/**
	 * dailyreportGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dailyreportGets(Request $request)
	{
		if (! $request->ajax()) return null;
		
		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		$dailydate_filter = $request->input('dailydate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		
		//对查询参数按照键名排序
		ksort($queryParams);
		
		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$result = Smt_pdreport::when($dailydate_filter, function ($query) use ($dailydate_filter) {
					return $query->whereBetween('created_at', $dailydate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', 'like', '%'.$xianti_filter.'%');
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', 'like', '%'.$banci_filter.'%');
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
	 * dailyreportDelete
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dailyreportDelete(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->only('tableselect');

		$result = Smt_pdreport::whereIn('id', $id)->delete();
		return $result;

	}
	
	/**
	 * dandangzheChange
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dandangzheChange(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$dailyreport = $request->only('id', 'dandangzhe');
		// dd($dailyreport['id']);
		foreach ($dailyreport['id'] as $key => $value) {
			$data[$key]['id'] = $value;
			$data[$key]['dandangzhe'] = $dailyreport['dandangzhe'];
		}
		// dd($data);

		try	{
			// $result = Smt_pdreport::where('id', $dailyreport['id'])
				// ->update([
					// 'dandangzhe'	=> $dailyreport['dandangzhe']
				// ]);
				
			// 批量更新
			app(Smt_pdreport::class)->updateBatch($data);
			
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}

	/**
	 * querenzheChange
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function querenzheChange(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$dailyreport = $request->only('id', 'querenzhe');
		// dd($dailyreport['id']);
		foreach ($dailyreport['id'] as $key => $value) {
			$data[$key]['id'] = $value;
			$data[$key]['querenzhe'] = $dailyreport['querenzhe'];
		}
		
		try	{
			// $result = Smt_pdreport::where('id', $dailyreport['id'])
				// ->update([
					// 'querenzhe'	=> $dailyreport['querenzhe']
				// ]);

			// 批量更新
			app(Smt_pdreport::class)->updateBatch($data);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}
	
	/**
	 * mpointImport
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mpointImport(Request $request)
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
			$filename = 'importmpoint.'.$ext;
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
			Smt_mpoint::truncate();
			
			$ret = Excel::import(new mpointImport, 'excel/'.$filename);
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
	 * pdreportExport
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdreportExport(Request $request)
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

		$smt_pdreport = Smt_pdreport::select('created_at', 'xianti', 'banci', 'jizhongming', 'spno', 'pinming',
			'lotshu', 'gongxu', 'dianmei', 'meimiao', 'meishu', 'taishu', 'lotcan', 'chajiandianshu',
			'jiadonglv', 'xinchan', 'liangchan', 'dengdaibupin', 'wujihua', 'qianhougongchengdengdai',
			'wubupin', 'bupinanpaidengdai', 'dingqidianjian', 'guzhang', 'bupinbuchong', 'shizuo',
			'jizaishixiang', 'dandangzhe', 'querenzhe')
			->whereBetween('created_at', [$queryfilter_datefrom, $queryfilter_dateto])
			->get()->toArray();
		// dd($smt_pdreport);

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
		$title[] = ['生产日期', '线体', '班次', '机种名', 'SP NO.', '品名',
			'LOT数', '工序', '点/枚', '枚/秒', '枚数', '台数', 'LOT残', '插件点数',
			'稼动率', '新产', '量产', '等待部品', '无计划', '前后工程等待',
			'无部品', '部品安排等待', '定期点检', '故障', '部品补充', '试作',
			'记载事项', '担当者', '确认者'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $smt_pdreport);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new pdreportExport($data), 'smt_pdreport_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
	}

	
}
