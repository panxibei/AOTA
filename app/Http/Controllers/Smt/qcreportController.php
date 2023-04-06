<?php

namespace App\Http\Controllers\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Smt\Smt_mpoint;
use App\Models\Smt\Smt_pdreport;
use App\Models\Smt\Smt_qcreport;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Smt\qcreportExport;
use App\Imports\qcreportImport;
use App\Charts\Smt\ECharts;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class qcreportController extends Controller
{
    //
	public function qcreportIndex () {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
		return view('smt.qcreport', $share);
		
	}
	
	
	/**
	 * qcreportGets
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportGets(Request $request)
	{
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;

		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$pinming_filter = $request->input('pinming_filter');
		$gongxu_filter = $request->input('gongxu_filter');

		$buliangneirong_filter = $request->input('buliangneirong_filter');
		
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
			$qcreport = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			// $qcreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
			// 		return $query->whereBetween('jianchariqi', $qcdate_filter);
			// 	})
			$qcreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('jianchariqi', $qcdate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', 'like', $banci_filter.'%');
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', '=', $pinming_filter);
				})
				->when($gongxu_filter, function ($query) use ($gongxu_filter) {
					return $query->where('gongxu', '=', $gongxu_filter);
				})
				->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
					// return $query->whereIn('buliangneirong', $buliangneirong_filter);
					// return $query->whereRaw('JSON_CONTAINS(buliangxinxi->"$**.weihao", \'["BBB"]\')');
					// return $query->whereRaw('JSON_CONTAINS(buliangxinxi->"$**.buliangneirong", \'[' . $buliangneirong_filter . ']\')');
					
					// 不良内容按or查询
					// if (!empty($buliangneirong_filter)) {
						$sql = '';
						foreach ($buliangneirong_filter as $value) {
							$sql .= ' JSON_CONTAINS(buliangxinxi->"$**.buliangneirong", \'["' . $value . '"]\')' . ' or';
						}
						$sql = substr($sql, 0, strlen($sql)-3);
					// }
					// dd($sql);
					
					return $query->whereRaw($sql);
				})
				->orderBy('jianchariqi', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $qcreport, now()->addSeconds(10));
		}
		
		return $qcreport;
	}	
	
	/**
	 * qcreportGetsChart1
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportGetsChart1(Request $request)
	{
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$pinming_filter = $request->input('pinming_filter');
		$gongxu_filter = $request->input('gongxu_filter');
		$buliangneirong_filter = $request->input('buliangneirong_filter');
		
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
			$dailyreport = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			// $dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
			// 		return $query->whereBetween('jianchariqi', $qcdate_filter);
			// 	})
			$dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('jianchariqi', $qcdate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', 'like', $banci_filter.'%');
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', '=', $pinming_filter);
				})
				->when($gongxu_filter, function ($query) use ($gongxu_filter) {
					return $query->where('gongxu', '=', $gongxu_filter);
				})
				->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
					return $query->whereIn('buliangneirong', $buliangneirong_filter);
				})
				->orderBy('jianchariqi', 'desc')
				// ->get()
				// ->groupBy('created_at')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $dailyreport, now()->addSeconds(10));
		}
		// dd($dailyreport);
		return $dailyreport;
	}	
	

	/**
	 * qcreportGetsChart2
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportGetsChart2(Request $request)
	{
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$pinming_filter = $request->input('pinming_filter');
		$gongxu_filter = $request->input('gongxu_filter');
		$buliangneirong_filter = $request->input('buliangneirong_filter');
		
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
			$chart2 = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			// $dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
			// 		return $query->whereBetween('jianchariqi', $qcdate_filter);
			// 	})
			$chart2 = Smt_qcreport::select('buliangxinxi')
				->when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('jianchariqi', $qcdate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', 'like', $banci_filter.'%');
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', '=', $pinming_filter);
				})
				->when($gongxu_filter, function ($query) use ($gongxu_filter) {
					return $query->where('gongxu', '=', $gongxu_filter);
				})
				->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
					// return $query->whereIn('buliangneirong', $buliangneirong_filter);
					// 不良内容按or查询
					$sql = '';
					foreach ($buliangneirong_filter as $value) {
						$sql .= ' JSON_CONTAINS(buliangxinxi->"$**.buliangneirong", \'["' . $value . '"]\')' . ' or';
					}
					$sql = substr($sql, 0, strlen($sql)-3);
					
					return $query->whereRaw($sql);
				})
				->orderBy('jianchariqi', 'desc')
				// ->get()
				// ->groupBy('created_at')
				// ->paginate($perPage, ['*'], 'page', $page);
				->get()->toArray();
			
			Cache::put($fullUrl, $chart2, now()->addSeconds(10));
		}

		$result = [];
		if (!empty($chart2)) {
			foreach ($chart2 as $key => $value) {
				if (!empty($value['buliangxinxi'])) {
					foreach ($value['buliangxinxi'] as $k => $v) {
						if ($v['shuliang'] == null || $v['shuliang'] == '') $v['shuliang'] = 0;
						array_push($result, $v);
					}
				}
			}
		}

		// dd($result);
		return $result;
	}	

	/**
	 * qcreportGetsChart3
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportGetsChart3(Request $request)
	{
		if (! $request->ajax()) return null;

		$url = request()->url();
		$queryParams = request()->query();

		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		// dd($queryParams);
		$qcdate_filter = $request->input('qcdate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$pinming_filter = $request->input('pinming_filter');
		$gongxu_filter = $request->input('gongxu_filter');
		$buliangneirong_filter = $request->input('buliangneirong_filter');
		
		// $usecache = $request->input('usecache');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$chart3 = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			$chart3 = Smt_qcreport::select('jianchariqi', 'hejidianshu', 'bushihejianshuheji', 'buliangxinxi')
				->when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('jianchariqi', $qcdate_filter);
				})
				->when($xianti_filter, function ($query) use ($xianti_filter) {
					return $query->where('xianti', '=', $xianti_filter);
				})
				->when($banci_filter, function ($query) use ($banci_filter) {
					return $query->where('banci', 'like', $banci_filter.'%');
				})
				->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
					return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
				})
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', '=', $pinming_filter);
				})
				->when($gongxu_filter, function ($query) use ($gongxu_filter) {
					return $query->where('gongxu', '=', $gongxu_filter);
				})
				->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
					// return $query->whereIn('buliangneirong', $buliangneirong_filter);
					// 不良内容按or查询
					$sql = '';
					foreach ($buliangneirong_filter as $value) {
						$sql .= ' JSON_CONTAINS(buliangxinxi->"$**.buliangneirong", \'["' . $value . '"]\')' . ' or';
					}
					$sql = substr($sql, 0, strlen($sql)-3);
					
					return $query->whereRaw($sql);
				})
				->orderBy('jianchariqi', 'desc')
				->get()->toArray();
			
			Cache::put($fullUrl, $chart3, now()->addSeconds(10));
		}
// dd($chart3);
		$result_jibenxinxi = [];
		$result_buliangxinxi = [];

		if (!empty($chart3)) {
			foreach ($chart3 as $key=>$value) {
				if (!empty($value['buliangxinxi'])) {
					foreach ($value['buliangxinxi'] as $k=>$v) {

						// 不良信息
						$b['jianchariqi'] = $value['jianchariqi'];
						$b['buliangneirong'] = $v['buliangneirong'] ?? '';
						$b['weihao'] = $v['weihao'] ?? '';
						$b['shuliang'] = $v['shuliang'] ?? 0;
						$b['jianchajileixing'] = $v['jianchajileixing'] ?? '';
						$b['jianchazhe'] = $v['jianchazhe'] ?? '';

						array_push($result_buliangxinxi, $b);
					}
				}

				// 基本机种信息
				$result_jibenxinxi[$key]['jianchariqi'] = $value['jianchariqi'];
				$result_jibenxinxi[$key]['hejidianshu'] = $value['hejidianshu'];
				$result_jibenxinxi[$key]['bushihejianshuheji'] = $value['bushihejianshuheji'];
			}
		}

		$result['jibenxinxi'] = $result_jibenxinxi;
		$result['buliangxinxi'] = $result_buliangxinxi;

		// dd($result);
		return $result;
	}

	
	/**
	 * buliangGets 暂未使用
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function buliangGets(Request $request)
	{
		if (! $request->ajax()) { return null; }

		$dr_id = $request->input('dr_id');
		// dd($dr_id);
		
		if ($dr_id == null) return null;
		
		// $xianti_filter = $request->input('xianti_filter');
		// $banci_filter = $request->input('banci_filter');
		
		// $mpoint = DB::table('mpoints')
		$qcreport = Smt_qcreport::whereIn('dr_id', $dr_id)
			->get();
		

		// dd($qcreport);
		return $qcreport;
	}	
	
	/**
	 * getSaomiao
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getSaomiao(Request $request)
	{
		if (! $request->ajax()) return null;

		$saomiao = $request->input('saomiao');
		$gongxu = $request->input('gongxu');

		if ($saomiao == null || $gongxu == null) return 0;
		
		try {
			// $saomiao_arr = explode('/', $saomiao);

			// 二维码示例：
			// [)>06T550120230406004037 1P66-44895Z13 Q24 1TC91S00123040714000 30TC91S00123040714000 32T
			// $saomiao_arr = explode('/', $saomiao);
			
			$jizhongming = substr($saomiao, 26, 11);
			$pinming = 'ABCD';

			$QtmpString = substr($saomiao, 38, 4);
			$Qpos = strpos($QtmpString, '1T');
			$lotshu = substr($QtmpString, 0, 4 - $Qpos);

			$spno = substr($saomiao, 40 + $Qpos, 18);

			
			// $jizhongming = substr($saomiao_arr[0], 0, 8);
			// $pinming = $saomiao_arr[1];
			// $spno = $saomiao_arr[1];
			// $lotshu = $saomiao_arr[2];

			$res = Smt_mpoint::select('diantai', 'pinban')
				->where('jizhongming', $jizhongming)
				// ->where('pinming', $pinming)
				->where('gongxu', $gongxu)
				->first();
			
			$dianmei = $res['diantai'] * $res['pinban']; 
			
			$res = Smt_pdreport::select('xianti', 'banci')
				->where('jizhongming', $jizhongming)
				->where('spno', 'like', $spno.'%')
				// ->where('pinming', $pinming)
				->where('gongxu', $gongxu)
				->first();

				// 生产日报中的机种生产日期，暂保留，无用（返回但没用上）
			// $jianchariqi = date('Y-m-d H:i:s', strtotime($res['created_at']));

			$xianti = $res['xianti'];
			$banci = $res['banci'];

			$result = compact('dianmei', 'xianti', 'banci', 'lotshu');
			
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;

	}


	/**
	 * qcreportCreate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportCreate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$saomiao = $request->input('saomiao');
		$jianchariqi = $request->input('jianchariqi');
		$xianti = $request->input('xianti');
		$banci = $request->input('banci');
		$gongxu = $request->input('gongxu');
		$dianmei = $request->input('dianmei');
		$meishu = $request->input('meishu');
		$piliangluru = $request->input('piliangluru');

		if ($dianmei == 0 || $meishu == 0) {
			return 0;
		}
// dd($jianchariqi);
		// 二维码示例：
		// [)>06T550120230406004037 1P66-44895Z13 Q24 1TC91S00123040714000 30TC91S00123040714000 32T
		// $saomiao_arr = explode('/', $saomiao);
		
		// $s['jizhongming'] = substr($saomiao_arr[0], 0, 8);
		// $s['pinming'] = $saomiao_arr[1];
		// $s['spno'] = $saomiao_arr[2];
		// $s['lotshu'] = $saomiao_arr[3];

		$s['jizhongming'] = substr($saomiao, 26, 11);
		$s['pinming'] = 'ABCD';
		$QtmpString = substr($saomiao, 38, 4);
		$Qpos = strpos($QtmpString, '1T');
		$s['lotshu'] = substr($QtmpString, 0, 4 - $Qpos);
		$s['spno'] = substr($saomiao, 40 + $Qpos, 18);

		// 获取生产日期
		// $jianchariqi = Smt_pdreport::select('jianchariqi')
		// ->where('jizhongming', $s['jizhongming'])
		// ->where('spno', $s['spno'])
		// ->where('pinming', $s['pinming'])
		// ->first();
		// dd($jianchariqi['jianchariqi']);
		$s['jianchariqi'] = $jianchariqi;
		$s['xianti'] = $xianti;
		$s['banci'] = $banci;
		$s['gongxu'] = $gongxu;
		$s['dianmei'] = $dianmei;
		$s['meishu'] = $meishu;
		$s['hejidianshu'] = $dianmei * $meishu;
		
		$s['bushihejianshuheji'] = 0;


		$p = [];
		// 如果批量不良录入为空
		if (empty($piliangluru)) {
			$s['bushihejianshuheji'] = 0;
			$s['ppm'] = 0;
			// $p[] = $s;

		} else {
			foreach ($piliangluru as $value) {
				if ($value['shuliang'] != null && $value['shuliang'] != '' && $value['shuliang'] != 0) {
					$s['bushihejianshuheji'] += $value['shuliang'];
				}
			}

			if ($s['bushihejianshuheji'] == 0) {
				$s['ppm'] = 0;
			} else {
				$s['ppm'] = $s['bushihejianshuheji'] / $s['hejidianshu'] * 1000000;
			}

			// dd($s);
			
			// foreach ($piliangluru as $value) {
			// 	$p[] = array_merge($value, $s);
			// }

		}

		if (!empty($piliangluru)) {
			// 	$s['buliangxinxi'] =  json_encode(
			// 		$piliangluru, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			// 	);

			$i = 1;
			foreach ($piliangluru as $key => $value) {
				$piliangluru[$key]['id'] = $i;
				$i++;
				if ($value['shuliang'] == null || $value['shuliang'] == '') {
					$piliangluru[$key]['shuliang'] = 0;
				}
			}

			$s['buliangxinxi'] = $piliangluru;
		}

		// dd($piliangluru);
		// dd($s);
		// dd($p);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($p as $value) {
				Smt_qcreport::create($s);
			// }

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// dd('Message: ' .$e->getMessage());
			return 0;
		}

		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;		
	}

	
	/**
	 * qcreportAppend
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportAppend(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$id = $request->input('id');
		$jianchajileixing = $request->input('jianchajileixing');
		$buliangneirong = $request->input('buliangneirong');
		$weihao = $request->input('weihao');
		$shuliang = $request->input('shuliang');
		if ($shuliang == null || $shuliang == '') $shuliang = 0;
		$jianchazhe = $request->input('jianchazhe');
		$count_of_buliangxinxi_append = $request->input('count_of_buliangxinxi_append');

		$a['jianchajileixing'] = $jianchajileixing;
		$a['buliangneirong'] = $buliangneirong;
		$a['weihao'] = $weihao;
		$a['shuliang'] = $shuliang;
		$a['jianchazhe'] = $jianchazhe;

		// dd($a);
		// dd($count_of_buliangxinxi_append);

		// 获取不良件数与合计点数
		$bupin = Smt_qcreport::select('hejidianshu', 'bushihejianshuheji', DB::raw("JSON_EXTRACT(buliangxinxi, '$**.shuliang') AS shuliang"))
			->where('id', $id)
			->first();

		$hejidianshu = $bupin['hejidianshu'];
		// $bushihejianshuheji = $bupin['bushihejianshuheji'];
		// $bushihejianshuheji += $shuliang;
		// $ppm = $bushihejianshuheji / $hejidianshu * 1000000;

		$sl = 0;
		$sl_arr = json_decode($bupin['shuliang'], true);
		foreach ($sl_arr as $value) {
			if ($value != null || $value != '')	$sl += intval($value);
		}
		$bushihejianshuheji = $sl + $shuliang;
		$ppm = $bushihejianshuheji / $hejidianshu * 1000000;


		// 确认json id
		// $count_of_buliangxinxi_append++;
		$buliangxinxi = '';
		// $buliangxinxi = '"id":' . $count_of_buliangxinxi_append . ',';
		foreach ($a as $key => $value) {
			// if ($key == 'shuliang') {
				// $buliangxinxi .= '"'. $key . '":' . $value . ',';
			// } else {
				$buliangxinxi .= '"'. $key . '":"' . $value . '",';
			// }
		}
		$buliangxinxi = substr($buliangxinxi, 0, strlen($buliangxinxi)-1);
		// dd($buliangxinxi);

		if ($count_of_buliangxinxi_append == 0) {
			$sql = '\'[{' . $buliangxinxi . '}]\'';
		} else {
			// $sql = 'JSON_MERGE(buliangxinxi, '[{"id":3, "weihao":"ZZZ", "shuliang":5, "jianchazhe":"黎小娟", "buliangneirong":"CHIP部品横立", "jianchajileixing":"AOI-2"}]')';
			$sql = 'JSON_MERGE(buliangxinxi, \'[{' . $buliangxinxi . '}]\')';
		}
		// dd($sql);

		$nowtime = date("Y-m-d H:i:s",time());

		// 尝试更新（追加json）
		try	{
			DB::beginTransaction();
			// $result = Smt_qcreport::where('id', $id)
			// 	->update([
			// 		'buliangxinxi'	=> $sql,
			// 		'updated_at' => '2019-07-01 16:41:11',
			// 	]);

			$result = DB::update('update smt_qcreports set buliangxinxi = ' . $sql . ', bushihejianshuheji = ' . $bushihejianshuheji . ', ppm = ' . $ppm . ', updated_at = "' . $nowtime . '" where id = ?', [$id]);
			$result = 1;
		}
		catch (\Exception $e) {
			DB::rollBack();
			// dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;
	}

	/**
	 * qcreportUpdate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportUpdate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		// $jizhongming = $request->input('jizhongming');
		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		// $jianchajileixing = $request->input('jianchajileixing');
		// $buliangneirong = $request->input('buliangneirong');
		// $weihao = $request->input('weihao');
		// $shuliang = $request->input('shuliang');
		// $jianchazhe = $request->input('jianchazhe');
		$meishu = $request->input('meishu');
		$hejidianshu = $request->input('hejidianshu');
		// $bushihejianshuheji = $request->input('bushihejianshuheji');
		$ppm = $request->input('ppm');

		// dd($id);
		// dd($updated_at);
		
		// 判断如果不是最新的记录，不可被编辑
		// 因为可能有其他人在你当前表格未刷新的情况下已经更新过了
		$res = Smt_qcreport::select('updated_at')
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
			$result = Smt_qcreport::where('id', $id)
				->update([
					'meishu'				=> $meishu,
					'hejidianshu'			=> $hejidianshu,
					// 'bushihejianshuheji'	=> $bushihejianshuheji,
					'ppm'					=> $ppm,
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
	 * qcreportUpdateSub
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportUpdateSub(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$subid = $request->input('subid');

		$updated_at = $request->input('updated_at');

		$jianchajileixing = $request->input('jianchajileixing');
		$buliangneirong = $request->input('buliangneirong');
		$weihao = $request->input('weihao');
		$shuliang = $request->input('shuliang');
		if ($shuliang[0] == null || $shuliang[0] == '') $shuliang[0] = 0;
		if ($shuliang[1] == null || $shuliang[1] == '') $shuliang[1] = 0;
		$jianchazhe = $request->input('jianchazhe');

		// 判断如果不是最新的记录，不可被编辑
		// 因为可能有其他人在你当前表格未刷新的情况下已经更新过了
		$res = Smt_qcreport::select('updated_at', 'hejidianshu', 'bushihejianshuheji', DB::raw("JSON_EXTRACT(buliangxinxi, '$**.shuliang') AS shuliang"))
			->where('id', $id)
			->first();
		$res_updated_at = date('Y-m-d H:i:s', strtotime($res['updated_at']));
		// dd(json_decode($res['shuliang'], true));
		if ($updated_at != $res_updated_at) return 0;
		
		// 获取json数据 buliangxinxi，修改对应subid项内容
		$hejidianshu = $res['hejidianshu'];
		// $bushihejianshuheji = $res['bushihejianshuheji'] + $shuliang[1] - $shuliang[0];
		$sl = 0;
		$sl_arr = json_decode($res['shuliang'], true);
		foreach ($sl_arr as $value) {
			if ($value != null || $value != '')	$sl += intval($value);
		}
		$bushihejianshuheji = $sl + $shuliang[1] - $shuliang[0];
		$ppm = $bushihejianshuheji / $hejidianshu * 1000000;

		// $sql = 'JSON_REPLACE(buliangxinxi, ';
		// $sql .= '\'$[' . $subid . '].jianchajileixing\', "' . $jianchajileixing . '", ';
		// $sql .= '\'$[' . $subid . '].buliangneirong\', "' . $buliangneirong . '", ';
		// $sql .= '\'$[' . $subid . '].weihao\', "' . $weihao . '", ';
		// $sql .= '\'$[' . $subid . '].shuliang\', ' . $shuliang[1] . ', ';
		// $sql .= '\'$[' . $subid . '].jianchazhe\', "' . $jianchazhe . '")';
		// dd($sql);

		$nowtime = date("Y-m-d H:i:s",time());

		// 尝试更新json
		try	{
			DB::beginTransaction();

			// if (empty($buliangneirong) && empty($weihao) && empty($shuliang[1])) {
				// $result = DB::update('update smt_qcreports set bushihejianshuheji = ' . $bushihejianshuheji . ', ppm = ' . $ppm . ', updated_at = "' . $nowtime . '" where id = ?', [$id]);
			// } else {
				$sql = 'JSON_REPLACE(buliangxinxi, ';
				$sql .= '\'$[' . $subid . '].jianchajileixing\', "' . $jianchajileixing . '", ';
				$sql .= '\'$[' . $subid . '].buliangneirong\', "' . $buliangneirong . '", ';
				$sql .= '\'$[' . $subid . '].weihao\', "' . $weihao . '", ';
				$sql .= '\'$[' . $subid . '].shuliang\', ' . $shuliang[1] . ', ';
				$sql .= '\'$[' . $subid . '].jianchazhe\', "' . $jianchazhe . '")';

				$result = DB::update('update smt_qcreports set buliangxinxi = ' . $sql . ', bushihejianshuheji = ' . $bushihejianshuheji . ', ppm = ' . $ppm . ', updated_at = "' . $nowtime . '" where id = ?', [$id]);
			// }
			$result = 1;
		}
		catch (\Exception $e) {
			DB::rollBack();
			dd('Message: ' .$e->getMessage());
			$result = 0;
		}
		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;

	}


	/**
	 * qcreportDelete
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportDelete(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('tableselect1');

		try	{
			$result = Smt_qcreport::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		Cache::flush();
		return $result;

	}

	/**
	 * qcreportRemoveSub
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportRemoveSub(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$subid = $request->input('subid');
		$shuliang = $request->input('shuliang');
		if ($shuliang == null || $shuliang == '') $shuliang = 0;

		$sql = 'JSON_REMOVE(buliangxinxi, \'$[' . $subid . ']\')';
		// dd($sql);

		// 获取合计点数和不良数量
		$res = Smt_qcreport::select('hejidianshu', 'bushihejianshuheji', DB::raw("JSON_EXTRACT(buliangxinxi, '$**.shuliang') AS shuliang"))
			->where('id', $id)
			->first();
		
		$hejidianshu = $res['hejidianshu'];
		// $bushihejianshuheji = $res['bushihejianshuheji'] - $shuliang;

		$sl = 0;
		$sl_arr = json_decode($res['shuliang'], true);
		foreach ($sl_arr as $value) {
			if ($value != null || $value != '')	$sl += intval($value);
		}
		$bushihejianshuheji = $sl - $shuliang;

		if ($bushihejianshuheji <= 0) {
			$bushihejianshuheji = 0;
			$ppm = 0;
		} else {
			$ppm = $bushihejianshuheji / $hejidianshu * 1000000;
		}

		$nowtime = date("Y-m-d H:i:s",time());

		try	{
			// UPDATE t_json SET info = json_remove(info,'$.ip');
			$result = DB::update('update smt_qcreports set buliangxinxi = ' . $sql . ', bushihejianshuheji = ' . $bushihejianshuheji . ', ppm = ' . $ppm . ', updated_at = "' . $nowtime . '" where id = ?', [$id]);
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
	 * qcreportExport
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportExport(Request $request)
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


		// $qcreport = Smt_qcreport::select('id', 'jianchariqi', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
		// 	'buliangneirong', 'weihao', 'shuliang', 'jianchajileixing', 'jianchazhe', 'created_at')
		// 	->whereBetween('jianchariqi', [$queryfilter_datefrom, $queryfilter_dateto])
		// 	->get()->toArray();
		$res = Smt_qcreport::select(DB::raw('LEFT(jianchariqi, 10) AS jianchariqi'), 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
		// $res = Smt_qcreport::select('jianchariqi', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
			'buliangxinxi', 'created_at', 'updated_at')
			->whereBetween('jianchariqi', [$queryfilter_datefrom, $queryfilter_dateto])
			->get()->toArray();
		
		// foreach ($res as $key=>$value) {
		// 	unset($res[$key]['buliangxinxi']);
		// }
		// dd($res);

		$qcreport = [];
		$arr = [];
		$tmp_empty = [
			'jianchariqi' => null,
			'xianti' => null,
			'banci' => null,
			'jizhongming' => null,
			'pinming' => null,
			'gongxu' => null,
			'spno' => null,
			'lotshu' => null,
			'dianmei' => null,
			'meishu' => null,
			'hejidianshu' => null,
			'bushihejianshuheji' => null,
			'ppm' => null,
			'created_at' => null,
			'updated_at' => null,
		];

		if (!empty($res)) {
			foreach ($res as $key=>$value) {

				$tmp = [
					'jianchariqi' => $value['jianchariqi'],
					'xianti' => $value['xianti'],
					'banci' => $value['banci'],
					'jizhongming' => $value['jizhongming'],
					'pinming' => $value['pinming'],
					'gongxu' => $value['gongxu'],
					'spno' => $value['spno'],
					'lotshu' => $value['lotshu'],
					'dianmei' => $value['dianmei'],
					'meishu' => $value['meishu'],
					'hejidianshu' => $value['hejidianshu'],
					'bushihejianshuheji' => $value['bushihejianshuheji'],
					'ppm' => $value['ppm'],
					'created_at' => $value['created_at'],
					'updated_at' => $value['updated_at'],
				];

				if (!empty($value['buliangxinxi'])) {
					foreach ($value['buliangxinxi'] as $k=>$v) {
						$arr['buliangneirong'] = $v['buliangneirong'] ?? '';
						$arr['weihao'] = $v['weihao'] ?? '';
						$arr['shuliang'] = $v['shuliang'] ?? '';
						$arr['jianchajileixing'] = $v['jianchajileixing'] ?? '';
						$arr['jianchazhe'] = $v['jianchazhe'] ?? '';

						// unset($tmp[$key]['buliangxinxi']);

						// 第一行显示主记录
						// if ($k==0) {
						// 	array_push($qcreport, array_merge($tmp, $arr));
						// } else {
						// 	array_push($qcreport, array_merge($tmp_empty, $arr));
						// }
						
						array_push($qcreport, array_merge($tmp, $arr));

					}
				} else {
					array_push($qcreport, $tmp);
				}

			}
		}
		// dd($qcreport);
		

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
		// $title[] = ['检查日期', '线体', '班次', '机种名', '品名', '工序', 'SP NO.', 'LOT数', '点/枚', '枚数', '合计点数', '不适合件数合计', 'PPM',
		// 	'不良内容', '位号', '数量', '检查机类型', '检查者', '创建日期'];
		$title[] = ['检查日期', '线体', '班次', '机种名', '品名', '工序', 'SP NO.', 'LOT数', '点/枚', '枚数', '合计点数', '不适合件数合计', 'PPM',
			'创建日期', '更新日期', '不良内容', '位号', '数量', '检查机类型', '检查者'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $qcreport);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new qcreportExport($data), 'smt_qc_report_'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
	}
	
	
	/**
	 * qcreportImport
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportImport(Request $request)
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
			if ($ext != 'xlsx') {
				return 0;
			}

			//获取文件的绝对路径
			$path = $fileCharater->path();
			// dd($path);

			//定义文件名
			// $filename = date('Y-m-d-h-i-s').'.'.$ext;

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', 'import.xlsx');
		} else {
			return 0;
		}
		
		// 导入excel文件内容
		try {
			$ret = Excel::import(new qcreportImport, 'excel/import.xlsx');
			// dd($ret);
			$result = 1;
		} catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		} finally {
			Storage::delete('excel/import.xlsx');
		}
		
		return $result;
		
	}	
	
	
	// chart1 未使用
	/**
	* Computes the sample chart.
	*
	* @return Response
	*/
	public function chart1(Request $request)
	{
		
		$hejidianshu = $request->input('hejidianshu');
		$bushihejianshuheji = $request->input('bushihejianshuheji');
		
		for ($i=0;$i<10;$i++) {
			// $hejidianshu[$i] = 0;
			// $bushihejianshuheji[$i] = 0;
			if ($hejidianshu[$i] == 0) {
				$ppm[$i] = 0;
			} else {
				$ppm[$i] = $bushihejianshuheji[$i] / $hejidianshu[$i] * 1000000;
			}
		}
		
		
		$chart1 = new echarts;
		// $chart0->dataset('Sample Test', 'bar', [3,4,1,15])->color('#00ff00');
		// $chart0->dataset('Sample Test', 'line', [1,41,3,23])->color('#ff0000');
		
		// options，使用数组对象形式
		// $chart1->dataset('不适合件数合计', 'bar', [3,4,1,15,20,23,7,8,22,32])
		// $chart1->dataset('不适合件数合计', 'bar', $hejidianshu)
		$chart1->dataset('不良件数', 'bar', $hejidianshu)
			->options([
				'barWidth' => 30,
				'itemStyle' => [
					'normal' => [
						'label' => [
							'show' => true,
							'position' => 'inside'
						]
					]
				]
			]);
				

		
		// $chart1->dataset('合计点数', 'bar', [3,4,1,15,20,23,22,11,53,23])
		$chart1->dataset('合计点数', 'bar', $bushihejianshuheji)
			->options([
				'barWidth' => 30,
				'itemStyle' => [
					'normal' => [
						'label' => [
							'show' => true,
							'position' => 'top'
						]
					]
				]
			]);
				
		
		// $chart1->dataset('PPM', 'line', [3,4,1,15,20,23,12,16,21,25]);
		// $chart0->dataset('销量2', 'line', [1,41,3,23,5,15])
			// ->options([
				// 'smooth' => true,
				// 'markPoint' => [
					// 'data' => [
						// ['type' => 'max', 'name' => '最大值'],
						// ['type' => 'min', 'name' => '最小值']
					// ]
				// ],
				// 'markLine' => [
					
					// 'data' => [
						// ['type' => 'average', 'name' => '平均值']
					// ]
					
				// ],
				// 'title' => [
					// 'text' => '未来一周气温变化',
					// 'subtext' => '纯属虚构'
				// ],
			// ]);
		// $chart1->dataset('PPM', 'line', [3,4,1,15,20,23,12,16,21,25])
		$chart1->dataset('PPM', 'line', $ppm)
			->options([
				'yAxisIndex' => 1,
				'itemStyle' => [
					'normal' => [
						'label' => [
							'show' => true,
							// 'position' => 'outer'
							'textStyle' => [
								'fontSize' => '20',
								'fontFamily' => '微软雅黑',
								'fontWeight' => 'bold'
							]
							
						]
					]
				]
			
				// 'smooth' => true,
				// 'markPoint' => [
					// 'data' => [
						// ['type' => 'max', 'name' => '最大值'],
						// ['type' => 'min', 'name' => '最小值']
					// ]
				// ],
				// 'markLine' => [
					
					// 'data' => [
						// ['type' => 'average', 'name' => '平均值']
					// ]
					
				// ],
				// 'title' => [
					// 'text' => '未来一周气温变化',
					// 'subtext' => '纯属虚构'
				// ],
			]);

		return $chart1->api();
		
	}
	
	
	// chart2 未使用
	/**
	* Computes the sample chart.
	*
	* @return Response
	*/
	public function chart2(Request $request)
	{
		
		// $hejidianshu = $request->input('hejidianshu');
		// $bushihejianshuheji = $request->input('bushihejianshuheji');
		
		$a = [
			['value'=>335, 'name'=>'SMT-1'],
			['value'=>310, 'name'=>'SMT-2'],
			['value'=>335, 'name'=>'SMT-3'],
			['value'=>310, 'name'=>'SMT-4'],
			['value'=>234, 'name'=>'SMT-5'],
			['value'=>135, 'name'=>'SMT-6'],
			['value'=>154, 'name'=>'SMT-7'],
			['value'=>335, 'name'=>'SMT-8'],
			['value'=>310, 'name'=>'SMT-9'],
			['value'=>234, 'name'=>'SMT-10'],
		];


		$chart2 = new echarts;
		$chart2->dataset('LINE别不良占有率', 'pie2d', $a);
				
		return $chart2->api();
		
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
			// $tongji = Smt_qcreport::select('jianchariqi', 'xianti', 'hejidianshu', 'bushihejianshuheji')
			// 	->when($tongji_date_filter, function ($query) use ($tongji_date_filter) {
			// 		return $query->whereBetween('jianchariqi', $tongji_date_filter);
			// 	})
			// 	->orderBy('jianchariqi', 'asc')
			// 	->get()->toArray();
			
			$tongji = Smt_qcreport::leftJoin('smt_mpoints', function ($join) {
					$join->on('smt_mpoints.jizhongming', '=', 'smt_qcreports.jizhongming')
					->on('smt_mpoints.pinming', '=', 'smt_qcreports.pinming')
					->on('smt_mpoints.gongxu', '=', 'smt_qcreports.gongxu');
				})	
				->select('smt_qcreports.jianchariqi', 'smt_qcreports.xianti', 'smt_qcreports.hejidianshu', 'smt_qcreports.bushihejianshuheji', DB::raw('smt_qcreports.meishu * smt_mpoints.pinban AS hejitaishu'))
				->when($tongji_date_filter, function ($query) use ($tongji_date_filter) {
					return $query->whereBetween('jianchariqi', $tongji_date_filter);
				})
				->orderBy('jianchariqi', 'asc')
				->get()->toArray();
			
			Cache::put($fullUrl, $tongji, now()->addSeconds(10));
		}
		
		// dd($tongji);
		return $tongji;
	}
	
	
	
	
	

	
	
	
	
	
}
