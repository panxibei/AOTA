<?php

namespace App\Http\Controllers\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Smt\Smt_mpoint;
use App\Models\Smt\Smt_pdreport;
use App\Models\Smt\Smt_pdplan;
use App\Models\Smt\Smt_pdplanresult;
use DB;
use App\Models\Smt\Smt_config;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Smt\mpointExport;
use App\Exports\Smt\pdreportExport;
use App\Imports\Smt\mpointImport;
use App\Imports\Smt\pdplanImport;

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
		$pinming_filter = $request->input('pinming_filter');
		$gongxu_filter = $request->input('gongxu_filter');
		
		$mpoint = Smt_mpoint::when($dailydate_filter, function ($query) use ($dailydate_filter) {
				return $query->whereBetween('created_at', $dailydate_filter);
			})
			->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
			})
			->when($pinming_filter, function ($query) use ($pinming_filter) {
				return $query->where('pinming', 'like', '%'.$pinming_filter.'%');
			})
			->when($gongxu_filter, function ($query) use ($gongxu_filter) {
				return $query->where('gongxu', 'like', '%'.$gongxu_filter.'%');
			})
			->orderBy('id', 'asc')
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
				// 'jizhongming'	=> substr($mpoint['jizhongming'], 0, 8),
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

		// 写入数据库
		try	{
			$result = Smt_mpoint::where('id', $mpoint['id'])
				->update([
					'jizhongming'	=> strtoupper(substr($mpoint['jizhongming'], 0 , 8)),
					'pinming'		=> strtoupper($mpoint['pinming']),
					'gongxu'		=> strtoupper($mpoint['gongxu']),
					'diantai'		=> $mpoint['diantai'],
					'pinban'		=> $mpoint['pinban'],
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			// dd('Message: ' .$e->getMessage());
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
		$id = $request->input('id');
		try	{
			$result = Smt_mpoint::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			$result = 0;
		}
		Cache::flush();
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
		return Storage::download('download/smt_mpointimport.xls', 'MoBan_Mpoint.xls');
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
			'shengchanriqi',
			'xianti',
			'banci',
			'jizhongming',
			'spno',
			'pinming',
			'lotshu',
			'meimiao',
			'taishu',
			'shoudongshengchanshijian',
			'gongxu',
			'xinchan',
			'liangchan',
			// 'qiehuancishu',
			'dengdaibupin',
			'wujihua',
			'qianhougongchengdengdai',
			'wubupin',
			'bupinanpaidengdai',
			'dingqidianjian',
			'guzhang',
			'shizuo',
			'jizaishixiang1',
			'jizaishixiang2',
			'jizaishixiang3',
			'jizaishixiang4',
			'jizaishixiang5',
			'jizaishixiang6',
			'jizaishixiang7',
			'jizaishixiang8',
			'jizaishixiang9',
			'jizaishixiang'
		);

		// 切换次数
		$qiehuancishu = 0;
		if (!empty($dailyreport['xinchan'])) $qiehuancishu++;
		if (!empty($dailyreport['liangchan'])) $qiehuancishu++;
		
		// dd($dailyreport);
		// dd($dailyreport['banci']);
		
		// 如果机种名等均为空，则判断为无计划
		if (empty($dailyreport['jizhongming']) && empty($dailyreport['pinming']) && empty($dailyreport['gongxu'])) {
			// dd('无计划');

			$dianmei = null;
			$meishu = null;
			$shijishengchanshijian =null;
			// $bupinbuchongshijian = $dailyreport['wujihua'];
			$bupinbuchongshijian = null;
			$chajiandianshu = null;
			$jiadonglv = null;

		} else {

			//读取点/枚
			$t = Smt_mpoint::select('diantai', 'pinban')
				->where('jizhongming', $dailyreport['jizhongming'])
				->where('pinming', $dailyreport['pinming'])
				->where('gongxu', $dailyreport['gongxu'])
				->first();
			// dd($t);
			
			if ($t == null) return 0;
			
			$dianmei = $t->diantai * $t->pinban;

			$meishu = ceil($dailyreport['taishu'] / $t->pinban);

			$shijishengchanshijian = $dailyreport['meimiao'] * $meishu;

			$bupinbuchongshijian = $dailyreport['shoudongshengchanshijian'] - $shijishengchanshijian / 60
				- $dailyreport['xinchan'] - $dailyreport['liangchan'] - $dailyreport['dengdaibupin']
				- $dailyreport['wujihua'] - $dailyreport['qianhougongchengdengdai'] - $dailyreport['wubupin']
				- $dailyreport['bupinanpaidengdai'] - $dailyreport['dingqidianjian'] - $dailyreport['guzhang']
				- $dailyreport['shizuo'];

			$chajiandianshu = $dianmei * $meishu;
			$jiadonglv = $meishu * $dailyreport['meimiao'] / 43200;

		}

		// 获取录入者名称，用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$user_tmp = explode(' ', $user['displayname']);

		if (sizeof($user_tmp)>1) {
			$luruzhe = $user_tmp[0] . $user_tmp[1];
		} else {
			$luruzhe = $user['displayname'];
		}

		// 写入数据库
		try	{
			// $result = DB::table('dailyreports')->insert([
			$result = Smt_pdreport::create([
				'shengchanriqi'	=> $dailyreport['shengchanriqi'],
				'xianti'		=> $dailyreport['xianti'],
				'banci'			=> $dailyreport['banci'],
				'jizhongming'	=> $dailyreport['jizhongming'],
				'spno'			=> $dailyreport['spno'],
				'pinming'		=> $dailyreport['pinming'],
				'lotshu'		=> $dailyreport['lotshu'],
				'meimiao'		=> $dailyreport['meimiao'],
				'meishu'		=> $meishu,
				'shijishengchanshijian'		=> $shijishengchanshijian,
				'shoudongshengchanshijian'	=> $dailyreport['shoudongshengchanshijian'],
				'bupinbuchongshijian'		=> $bupinbuchongshijian,
				'gongxu'		=> $dailyreport['gongxu'],
				'dianmei'		=> $dianmei,
				'meimiao'		=> $dailyreport['meimiao'],
				'taishu'		=> $dailyreport['taishu'],
				'lotcan'		=> 0,
				'chajiandianshu'=> $chajiandianshu,
				'jiadonglv'		=> $jiadonglv,

				'xinchan'					=> $dailyreport['xinchan'],
				'liangchan'					=> $dailyreport['liangchan'],
				'qiehuancishu'				=> $qiehuancishu,
				'dengdaibupin'				=> $dailyreport['dengdaibupin'],
				'wujihua'					=> $dailyreport['wujihua'],
				'qianhougongchengdengdai'	=> $dailyreport['qianhougongchengdengdai'],
				'wubupin'					=> $dailyreport['wubupin'],
				'bupinanpaidengdai'			=> $dailyreport['bupinanpaidengdai'],
				'dingqidianjian'			=> $dailyreport['dingqidianjian'],
				'guzhang'					=> $dailyreport['guzhang'],
				// 'xinjizhongshengchanshijian'				=> $dailyreport['xinjizhongshengchanshijian'],
				'shizuo'					=> $dailyreport['shizuo'],
				'jizaishixiang1'			=> $dailyreport['jizaishixiang1'],
				'jizaishixiang2'			=> $dailyreport['jizaishixiang2'],
				'jizaishixiang3'			=> $dailyreport['jizaishixiang3'],
				'jizaishixiang4'			=> $dailyreport['jizaishixiang4'],
				'jizaishixiang5'			=> $dailyreport['jizaishixiang5'],
				'jizaishixiang6'			=> $dailyreport['jizaishixiang6'],
				'jizaishixiang7'			=> $dailyreport['jizaishixiang7'],
				'jizaishixiang8'			=> $dailyreport['jizaishixiang8'],
				'jizaishixiang9'			=> $dailyreport['jizaishixiang9'],
				'jizaishixiang'				=> $dailyreport['jizaishixiang'],
				'luruzhe'					=> $luruzhe,
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		// dd($result);
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
		
		$date_filter = $request->input('date_filter');
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
			// 分页结果
			// $result['paginate'] = Smt_pdreport::when($date_filter, function ($query) use ($date_filter) {
			$result['paginate'] = DB::table('smt_pdreports')->when($date_filter, function ($query) use ($date_filter) {
					return $query->whereBetween('shengchanriqi', $date_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', '=', $banci_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				// ->orderBy('shengchanriqi', 'asc')
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			// 总记录结果，包含全部分页，用于真正地计算汇总
			// $result['total'] = Smt_pdreport::when($date_filter, function ($query) use ($date_filter) {
			// 		return $query->whereBetween('shengchanriqi', $date_filter);
			// 	})
			// 	->when($xianti_filter, function ($query) use ($xianti_filter) {
			// 		return $query->where('xianti', '=', $xianti_filter);
			// 	})
			// 	->when($banci_filter, function ($query) use ($banci_filter) {
			// 		return $query->where('banci', '=', $banci_filter);
			// 	})
			// 	->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
			// 		return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
			// 	})
			// 	// ->orderBy('shengchanriqi', 'asc')
			// 	->orderBy('created_at', 'asc')
			// 	->get()->toArray();
		
			// $result['total'] = Smt_pdreport::when($date_filter, function ($query) use ($date_filter) {
			$result['total'] = DB::table('smt_pdreports')->when($date_filter, function ($query) use ($date_filter) {
					return $query->whereBetween('shengchanriqi', $date_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', '=', $banci_filter);
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->orderBy('created_at', 'asc')
				->get();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
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
		$id = $request->input('tableselect');
		try	{
			$result = Smt_pdreport::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			$result = 0;
		}
		Cache::flush();
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
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

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
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

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

		$smt_pdreport = Smt_pdreport::select(DB::raw('LEFT(shengchanriqi, 10)'), 'xianti', 'banci', 'jizhongming', 'spno', 'pinming',
		// $smt_pdreport = Smt_pdreport::select('shengchanriqi', 'xianti', 'banci', 'jizhongming', 'spno', 'pinming',
			'lotshu', 'gongxu', 'dianmei', 'meimiao', 'meishu', 'shijishengchanshijian', 'shoudongshengchanshijian', 'bupinbuchongshijian', 'taishu', 'lotcan', 'chajiandianshu',
			'xinchan', 'liangchan', 'jizaishixiang1', 'dengdaibupin', 'jizaishixiang2', 'wujihua', 'jizaishixiang3', 'qianhougongchengdengdai', 'jizaishixiang4',
			'wubupin', 'jizaishixiang5', 'bupinanpaidengdai', 'jizaishixiang6', 'dingqidianjian', 'jizaishixiang7', 'guzhang', 'jizaishixiang8', 'shizuo', 'jizaishixiang9',
			'jizaishixiang', 'luruzhe', 'bianjizhe', 'dandangzhe', 'querenzhe')
			->whereBetween('shengchanriqi', [$queryfilter_datefrom, $queryfilter_dateto])
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
			'LOT数', '工序', '点/枚', '枚/秒', '枚数', '实际生产时间', '手动生产时间', '部品补充时间', '台数', 'LOT残', '插件点数',
			'新产', '量产', '记载事项1', '等待部品', '记载事项2', '无计划', '记载事项3', '前后工程等待', '记载事项4',
			'部品欠品', '记载事项5', '部品准备等待', '记载事项6', '定期点检', '记载事项7', '故障', '记载事项8', '试作', '记载事项9',
			'其他记载事项', '录入者', '编辑者', '担当者', '确认者'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $smt_pdreport);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new pdreportExport($data), 'smt_pdreport_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
	}

	/**
     * pdplanImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdplanImport(Request $request)
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
			$filename = 'pdplanimport.'.$ext;
			// dd($filename);

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', $filename);
			// dd($filename);
		} else {
			return 0;
		}
		
		DB::beginTransaction();
		// 导入excel文件内容
		try {

			// 先清空表
			// Smt_pdplan::truncate();
			DB::statement('delete from smt_pdplans');
			
			$ret = Excel::import(new pdplanImport, 'excel/'.$filename);

			// 转换到planresult表中
			$plan = Smt_pdplan::select('suoshuyuefen', 'xianti', 'jizhongming', 'spno', 'pinming', 'gongxu', 'lotshu', 'chanliangxinxi')
				->get()->toArray();
			
			// $plan_data = [];
			// Smt_pdplanresult::truncate();
			DB::statement('delete from smt_pdplanresults');

			foreach ($plan as $key=>$value) {
				$chanliangxinxi = explode('|', $value['chanliangxinxi']);

				foreach ($chanliangxinxi as $k=>$v) {
					if ($v!=0) {
						$xinxi = explode('_', $v); // 01_A_120，日期+班次+产量

						// array_push($plan_data, [
						// 	'suoshuriqi' => $value['suoshuyuefen'] . '-' . $xinxi[0],
						// 	'xianti' => $value['xianti'],
						// 	'banci' => $xinxi[1],
						// 	'jizhongming' => $value['jizhongming'],
						// 	'spno' => $value['spno'],
						// 	'pinming' => $value['pinming'],
						// 	'gongxu' => $value['gongxu'],
						// 	'lotshu' => $value['lotshu'],
						// 	'jihuachanliang' => $xinxi[2],
						// ]);

						$result = Smt_pdplanresult::create([
							'suoshuriqi' => $value['suoshuyuefen'] . '-' . $xinxi[0],
							'xianti' => $value['xianti'],
							'banci' => $xinxi[1],
							'jizhongming' => $value['jizhongming'],
							'spno' => $value['spno'],
							'pinming' => $value['pinming'],
							'gongxu' => $value['gongxu'],
							'lotshu' => $value['lotshu'],
							'jihuachanliang' => $xinxi[2],
						]);

					}
				}
			}
			DB::commit();
			$result = 1;
		} catch (\Exception $e) {
			DB::rollBack();
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		} finally {
			Storage::delete('excel/'.$filename);
		}
		
		Cache::flush();
		// dd($result);
		return $result;
	}

		
    /**
     * pdplanDownload 导入模板下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdplanDownload(Request $request)
    {
		return Storage::download('download/smt_pdplanimport.xlsx', 'MoBan_SmtPdPlan.xlsx');
	}

	/**
     * pdplantableUpload
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdplantableUpload(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$fileCharater = $request->file('myfile');
 
		if ($fileCharater->isValid()) {
			$ext = $fileCharater->extension();

			if ($ext != 'xls' && $ext != 'xlsx') return 0;

			$filename = 'pdplantableupload.'.$ext;
			$fileCharater->storeAs('excel', $filename);
		} else {
			return 0;
		}
		
		return 1;
	}

		
    /**
     * pdplantableDownload 计划表文件下载
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdplantableDownload(Request $request)
    {
		return Storage::download('excel/pdplantableupload.xlsx', 'Download_SmtPdPlanTable.xlsx');
	}


	/**
	 * pdplanGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdplanGets(Request $request)
	{
		if (! $request->ajax()) return null;
		
		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		$date_filter = $request->input('date_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		// $jizhongming_filter = $request->input('jizhongming_filter');

		//对查询参数按照键名排序
		ksort($queryParams);
		
		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			// 总记录结果，包含全部分页，用于真正地计算汇总
			$result = Smt_pdplanresult::when($date_filter, function ($query) use ($date_filter) {
					return $query->whereBetween('suoshuriqi', $date_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', '=', $banci_filter);
				})
				// ->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				// 	return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				// })
				->orderBy('suoshuriqi', 'asc')
				// ->orderBy('created_at', 'asc')
				// ->get();
				->paginate($perPage, ['*'], 'page', $page);

		
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		// dd($result);
		return $result;
	}

	/**
	 * pdplanresultGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdplanresultGets(Request $request)
	{
		if (! $request->ajax()) return null;
		
		$url = request()->url();
		$queryParams = request()->query();

		// $perPage = $queryParams['perPage'] ?? 10000;
		// $page = $queryParams['page'] ?? 1;
		
		$date_filter = $request->input('date_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		// $jizhongming_filter = $request->input('jizhongming_filter');

		//对查询参数按照键名排序
		ksort($queryParams);
		
		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			// 总记录结果，包含全部分页，用于真正地计算汇总
			$result = Smt_pdplanresult::when($date_filter, function ($query) use ($date_filter) {
					return $query->whereBetween('suoshuriqi', $date_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', '=', $banci_filter);
				})
				// ->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				// 	return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				// })
				->orderBy('suoshuriqi', 'asc')
				// ->orderBy('created_at', 'asc')
				->get();
				// ->paginate($perPage, ['*'], 'page', $page);

		
			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		// dd($result);
		return $result;
	}

	/**
	 * pdplanRefresh
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdplanRefresh(Request $request)
	{
		if (! $request->ajax()) return null;
		
		$res = DB::connection('mysql_smtplandb')
			// ->select('SELECT
			// 	execdate AS suoshuriqi,
			// 	linename AS xianti,
			// 	production AS jizhongming,
			// 	spno,
			// 	panelname AS pinming,
			// 	RIGHT(prjname, 1) AS gongxu,
			// 	quantity AS lotshu,
			// 	lota,
			// 	lotb
			// FROM tcprjreport
			// limit 10');
			->table('tcprjreport')
			->select('execdate AS suoshuriqi',
				'linename AS xianti',
				// DB::raw('INSERT(linename, 5, 1, "") AS xianti'), // SMT-26 -> SMT-6 去年那个2
				'production AS jizhongming',
				'spno',
				'panelname AS pinming',
				DB::raw('RIGHT(prjname, 1) AS gongxu'), // RA或RB，去掉那个R
				'total AS lotshu',
				'lota',
				'lotb')
			->limit(1000)
			->orderBy('rptid', 'desc')
			->get();
		
		// dd($res);
		

		$nowtime = date("Y-m-d H:i:s",time());
		$data = [];

		foreach ($res as $key=>$value) {
			if (strlen($value->xianti) > 5 && substr($value->xianti, 4, 1) == '2') {
				$xianti = substr($value->xianti, 0, 4) . substr($value->xianti, 5);
			} else {
				$xianti = $value->xianti;
			}

			// A班
			if ($value->lota > 0) {
				array_push($data, [
					'suoshuriqi' => $value->suoshuriqi,
					'xianti' => $xianti,
					'banci' => 'A',
					'jizhongming' => $value->jizhongming,
					'spno' => $value->spno,
					'pinming' => $value->pinming,
					'gongxu' => $value->gongxu,
					'lotshu' => $value->lotshu,
					'jihuachanliang' => $value->lota,
					'created_at' => $nowtime,
					'updated_at' => $nowtime,
				]);
			}
			// B班
			if ($value->lotb > 0) {
				array_push($data, [
					'suoshuriqi' => $value->suoshuriqi,
					'xianti' => $xianti,
					'banci' => 'B',
					'jizhongming' => $value->jizhongming,
					'spno' => $value->spno,
					'pinming' => $value->pinming,
					'gongxu' => $value->gongxu,
					'lotshu' => $value->lotshu,
					'jihuachanliang' => $value->lotb,
					'created_at' => $nowtime,
					'updated_at' => $nowtime,
				]);
			}
		}
		// dd($data);

		DB::beginTransaction();
		try {

			// Smt_pdplanresult::truncate();
			DB::statement('delete from smt_pdplanresults');

			$result = Smt_pdplanresult::insert($data);

			DB::commit();
			$result = 1;
		} catch (\Exception $e) {
			DB::rollBack();
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		
		Cache::flush();
		// dd($result);
		return $result;
	}

	/**
	 * pdplanTruncate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function pdplanTruncate(Request $request)
	{
		if (! $request->ajax()) return null;
		
		try {
			Smt_pdplanresult::truncate();
			$result = 1;
		} catch (\Exception $e) {
			$result = 0;
		}
		Cache::flush();
		return $result;
	}


	/**
	 * tongjiGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function tongjiGets(Request $request)
	{
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$tongji_date_filter = $request->input('tongji_date_filter');
		
		// $usecache = $request->input('usecache');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$tongji = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$tongji = Smt_pdreport::select('xianti',
					DB::raw('ROUND(SUM(shijishengchanshijian)/60) AS shijishijian'),
					DB::raw('SUM(chajiandianshu) AS shijidianshu'),
					DB::raw('SUM(bupinbuchongshijian) AS bupinbuchong'),

					DB::raw('SUM(IFNULL(xinchan, 0) + IFNULL(liangchan, 0)) AS jizhongqiehuanshijian'),
					DB::raw('SUM(qiehuancishu) AS jizhongqiehuancishu'),
					DB::raw('ROUND(SUM(IFNULL(xinchan, 0) + IFNULL(liangchan, 0))/SUM(qiehuancishu)) AS jizhongqiehuanyici'),

					DB::raw('SUM(dengdaibupin) AS dengdaibupin'),
					DB::raw('SUM(wujihua) AS wujihua'),
					DB::raw('SUM(qianhougongchengdengdai) AS qianhougongchengdengdai'),
					DB::raw('SUM(wubupin) AS wubupin'),
					DB::raw('SUM(bupinanpaidengdai) AS bupinanpaidengdai'),
					DB::raw('SUM(dingqidianjian) AS dingqidianjian'),
					DB::raw('SUM(guzhang) AS guzhang'),
					DB::raw('SUM(shizuo) AS shizuo'))
				->when($tongji_date_filter, function ($query) use ($tongji_date_filter) {
					return $query->whereBetween('shengchanriqi', $tongji_date_filter);
				})
				->groupBy('xianti')
				// ->orderBy('shengchanriqi', 'asc')
				->get()->toArray();
			
			Cache::put($fullUrl, $tongji, now()->addSeconds(10));
		}

		$tianshu = Smt_pdreport::select(
			DB::raw('COUNT(DISTINCT shengchanriqi) AS shengchantianshu')
			)
			->when($tongji_date_filter, function ($query) use ($tongji_date_filter) {
				return $query->whereBetween('shengchanriqi', $tongji_date_filter);
			})
			->first()->toArray();
		$shengchantianshu = $tianshu['shengchantianshu'];
		// dd($shengchantianshu);
		
		// 设备能力配置读取
		// $shebeinengli = Smt_config::select('value')
		// 	->where('suoshu', 'pdreport')
		// 	->where('name', 'like', 'shebeinengli_%')
		// 	->get()->toArray();
		
		$shebeinengli = Smt_config::where('suoshu', 'pdreport')
			->where('name', 'like', 'shebeinengli_smt%')
			->pluck('value', 'name')
			->toArray();
		
		$shebei = Smt_config::where('suoshu', 'pdreport')
			->where('name', 'like', 'shebei_smt%')
			->pluck('value', 'name')
			->toArray();

		// dd($shebeinengli);
		$result = compact('tongji', 'shengchantianshu', 'shebeinengli', 'shebei');
		return $result;
	}


	/**
	 * dailyreportUpdate1
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dailyreportUpdate1(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		$id = $request->input('id');
		$spno = $request->input('spno');
		$jizhongming = $request->input('jizhongming');
		$pinming = $request->input('pinming');
		$lotshu = $request->input('lotshu');
		$gongxu = $request->input('gongxu');
		$meimiao = $request->input('meimiao');
		$taishu = $request->input('taishu');
		$shoudongshengchanshijian = $request->input('shoudongshengchanshijian');

		// dd($jizhongming);
		
		//读取点/枚
		$t = Smt_mpoint::select('diantai', 'pinban')
			->where('jizhongming', $jizhongming)
			->where('pinming', $pinming)
			->where('gongxu', $gongxu)
			->first();
		
		if ($t == null) return 0;
		
		$dianmei = $t->diantai * $t->pinban;

		$meishu = ceil($taishu / $t->pinban);

		$shijishengchanshijian = $meimiao * $meishu;

		$chajiandianshu = $dianmei * $meishu;
		$jiadonglv = $meishu * $meimiao / 43200;

		$ss = Smt_pdreport::select('shijishengchanshijian', 'xinchan',
				'liangchan', 'dengdaibupin', 'wujihua', 'qianhougongchengdengdai', 'wubupin',
				'bupinanpaidengdai', 'dingqidianjian', 'guzhang', 'shizuo'
			)
			->where('id', $id)
			->first()->toArray();
		
		if (empty($jizhongming) && empty($pinming) && empty($gongxu)) {
			// $bupinbuchongshijian = $ss['wujihua'];
			$bupinbuchongshijian = null;
		} else {
			$bupinbuchongshijian = $shoudongshengchanshijian - $ss['shijishengchanshijian'] / 60
				- $ss['xinchan'] - $ss['liangchan'] - $ss['dengdaibupin']
				- $ss['wujihua'] - $ss['qianhougongchengdengdai'] - $ss['wubupin']
				- $ss['bupinanpaidengdai'] - $ss['dingqidianjian'] - $ss['guzhang']
				- $ss['shizuo'];
		}
		// dump($bupinbuchongshijian);

		// 获取录入者名称，用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$user_tmp = explode(' ', $user['displayname']);

		if (sizeof($user_tmp)>1) {
			$bianjizhe = $user_tmp[0] . $user_tmp[1];
		} else {
			$bianjizhe = $user['displayname'];
		}

		// 写入数据库
		try	{
			$result = Smt_pdreport::where('id', $id)
			->update([
				'jizhongming'	=> $jizhongming,
				'spno'			=> $spno,
				'pinming'		=> $pinming,
				'lotshu'		=> $lotshu,
				'meimiao'		=> $meimiao,
				'meishu'		=> $meishu,
				'shijishengchanshijian'		=> $shijishengchanshijian,
				'shoudongshengchanshijian'	=> $shoudongshengchanshijian,
				'gongxu'		=> $gongxu,
				'dianmei'		=> $dianmei,
				'meimiao'		=> $meimiao,
				'taishu'		=> $taishu,
				'lotcan'		=> 0,
				'chajiandianshu'=> $chajiandianshu,
				'jiadonglv'		=> $jiadonglv,
				'bianjizhe'		=> $bianjizhe,
				'bupinbuchongshijian' => $bupinbuchongshijian
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		Cache::flush();
		// dd($result);
		return $result;

	}

	/**
	 * dailyreportUpdate2
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dailyreportUpdate2(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		$id = $request->input('id');
		$xinchan = $request->input('xinchan');
		$liangchan = $request->input('liangchan');
		$dengdaibupin = $request->input('dengdaibupin');
		$wujihua = $request->input('wujihua');
		$qianhougongchengdengdai = $request->input('qianhougongchengdengdai');
		$wubupin = $request->input('wubupin');
		$bupinanpaidengdai = $request->input('bupinanpaidengdai');
		$dingqidianjian = $request->input('dingqidianjian');
		$guzhang = $request->input('guzhang');
		$shizuo = $request->input('shizuo');
		$jizaishixiang1 = $request->input('jizaishixiang1');
		$jizaishixiang2 = $request->input('jizaishixiang2');
		$jizaishixiang3 = $request->input('jizaishixiang3');
		$jizaishixiang4 = $request->input('jizaishixiang4');
		$jizaishixiang5 = $request->input('jizaishixiang5');
		$jizaishixiang6 = $request->input('jizaishixiang6');
		$jizaishixiang7 = $request->input('jizaishixiang7');
		$jizaishixiang8 = $request->input('jizaishixiang8');
		$jizaishixiang9 = $request->input('jizaishixiang9');
		$jizaishixiang = $request->input('jizaishixiang');

		// 切换次数
		$qiehuancishu = 0;
		if (!empty($xinchan)) $qiehuancishu++;
		if (!empty($liangchan)) $qiehuancishu++;
		// dd($id);

		$ss = Smt_pdreport::select('jizhongming', 'pinming', 'gongxu', 'shoudongshengchanshijian', 'shijishengchanshijian')
			->where('id', $id)
			->first()->toArray();
		$shoudongshengchanshijian = $ss['shoudongshengchanshijian'];
		$shijishengchanshijian = $ss['shijishengchanshijian'];
		
		if (empty($ss['jizhongming']) && empty($ss['pinming']) && empty($ss['gongxu'])) {
			// $bupinbuchongshijian = $wujihua;
			$bupinbuchongshijian = null;
		} else {
			$bupinbuchongshijian = $shoudongshengchanshijian - $shijishengchanshijian / 60
				- $xinchan - $liangchan - $dengdaibupin
				- $wujihua - $qianhougongchengdengdai - $wubupin
				- $bupinanpaidengdai - $dingqidianjian - $guzhang
				- $shizuo;
		}

		// 获取录入者名称，用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$user_tmp = explode(' ', $user['displayname']);

		if (sizeof($user_tmp)>1) {
			$bianjizhe = $user_tmp[0] . $user_tmp[1];
		} else {
			$bianjizhe = $user['displayname'];
		}

		// 写入数据库
		try	{
			$result = Smt_pdreport::where('id', $id)
			->update([
				'xinchan' => $xinchan,
				'liangchan' => $liangchan,
				'qiehuancishu' => $qiehuancishu,
				'dengdaibupin' => $dengdaibupin,
				'wujihua' => $wujihua,
				'qianhougongchengdengdai' => $qianhougongchengdengdai,
				'wubupin' => $wubupin,
				'bupinanpaidengdai' => $bupinanpaidengdai,
				'dingqidianjian' => $dingqidianjian,
				'guzhang' => $guzhang,
				'shizuo' => $shizuo,
				'jizaishixiang1' => $jizaishixiang1,
				'jizaishixiang2' => $jizaishixiang2,
				'jizaishixiang3' => $jizaishixiang3,
				'jizaishixiang4' => $jizaishixiang4,
				'jizaishixiang5' => $jizaishixiang5,
				'jizaishixiang6' => $jizaishixiang6,
				'jizaishixiang7' => $jizaishixiang7,
				'jizaishixiang8' => $jizaishixiang8,
				'jizaishixiang9' => $jizaishixiang9,
				'jizaishixiang' => $jizaishixiang,
				'bianjizhe'		=> $bianjizhe,
				'bupinbuchongshijian' => $bupinbuchongshijian
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		Cache::flush();
		// dd($result);
		return $result;

	}


	
}
