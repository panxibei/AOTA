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
			$dailyreport = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有        
			// $dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
			// 		return $query->whereBetween('shengchanriqi', $qcdate_filter);
			// 	})
			$dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
					return $query->whereBetween('created_at', $qcdate_filter);
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
				->when($pinming_filter, function ($query) use ($pinming_filter) {
					return $query->where('pinming', '=', $pinming_filter);
				})
				->when($gongxu_filter, function ($query) use ($gongxu_filter) {
					return $query->where('gongxu', '=', $gongxu_filter);
				})
				->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
					return $query->whereIn('buliangneirong', $buliangneirong_filter);
				})
				->orderBy('created_at', 'asc')
				->paginate($perPage, ['*'], 'page', $page);
			
			Cache::put($fullUrl, $dailyreport, now()->addSeconds(30));
		}
		
		return $dailyreport;
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
	
// 此函数暂未用到
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
			$saomiao_arr = explode('/', $saomiao);
			
			$jizhongming = $saomiao_arr[0];
			$spno = $saomiao_arr[1];
			$pinming = $saomiao_arr[2];
			$lotshu = $saomiao_arr[3];

			$res = Smt_mpoint::select('diantai', 'pinban')
				->where('jizhongming', $jizhongming)
				->where('pinming', $pinming)
				->where('gongxu', $gongxu)
				->first();
			
			$dianmei = $res['diantai'] * $res['pinban']; 
			
			$res = Smt_pdreport::select('created_at')
				->where('jizhongming', $jizhongming)
				->where('spno', $spno)
				->where('pinming', $pinming)
				->where('gongxu', $gongxu)
				->first();
			
			// 生产日报中的机种生产日期，暂保留，无用（返回但没用上）
			$shengchanriqi = date('Y-m-d H:i:s', strtotime($res['created_at']));

			$result = compact('dianmei', 'shengchanriqi');
			
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
		$shengchanriqi = $request->input('shengchanriqi');
		$xianti = $request->input('xianti');
		$banci = $request->input('banci');
		$gongxu = $request->input('gongxu');
		$dianmei = $request->input('dianmei');
		$meishu = $request->input('meishu');
		$piliangluru = $request->input('piliangluru');

		if ($dianmei == 0 || $meishu == 0) {
			return 0;
		}

		$saomiao_arr = explode('/', $saomiao);
		
		$s['jizhongming'] = $saomiao_arr[0];
		$s['spno'] = $saomiao_arr[1];
		$s['pinming'] = $saomiao_arr[2];
		$s['lotshu'] = $saomiao_arr[3];
		
		$s['shengchanriqi'] = $shengchanriqi;
		$s['xianti'] = $xianti;
		$s['banci'] = $banci;
		$s['gongxu'] = $gongxu;
		$s['dianmei'] = $dianmei;
		$s['meishu'] = $meishu;
		$s['hejidianshu'] = $dianmei * $meishu;
		
		$s['bushihejianshuheji'] = 0;
		foreach ($piliangluru as $value) {
			if ($value['shuliang'] == 0) return 0;
			$s['bushihejianshuheji'] += $value['shuliang'];
		}

		if ($s['bushihejianshuheji'] == 0) {
			$s['ppm'] = 0;
		} else {
			$s['ppm'] = $s['bushihejianshuheji'] / $s['hejidianshu'] * 1000000;
		}
		
		// 不良内容为一维数组，字符串化
		// foreach ($piliangluru as $key => $value) {
			// $piliangluru[$key]['buliangneirong'] = implode(',', $value['buliangneirong']);
		// }
		// dd($piliangluru);
		
		$p = [];
		foreach ($piliangluru as $value) {
			$p[] = array_merge($value, $s);
		}

		// dd($p);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			foreach ($p as $value) {
				Smt_qcreport::create($value);
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
	 * qcreportUpdate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function qcreportUpdate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$jizhongming = $request->input('jizhongming');
		$created_at = $request->input('created_at');
		$updated_at = $request->input('updated_at');
		$jianchajileixing = $request->input('jianchajileixing');
		$buliangneirong = $request->input('buliangneirong');
		$weihao = $request->input('weihao');
		$shuliang = $request->input('shuliang');
		$jianchazhe = $request->input('jianchazhe');
		$meishu = $request->input('meishu');
		$hejidianshu = $request->input('hejidianshu');
		$bushihejianshuheji = $request->input('bushihejianshuheji');
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
					'jizhongming'		=> $jizhongming,
					'jianchajileixing'	=> $jianchajileixing,
					'buliangneirong'	=> $buliangneirong,
					'weihao'			=> $weihao,
					'shuliang'			=> $shuliang,
					'jianchazhe'		=> $jianchazhe,
				]);
			$result = Smt_qcreport::where('created_at', $created_at)
				->update([
					'meishu'				=> $meishu,
					'hejidianshu'			=> $hejidianshu,
					'bushihejianshuheji'	=> $bushihejianshuheji,
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


		// $qcreport = Smt_qcreport::select('id', 'shengchanriqi', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
		// 	'buliangneirong', 'weihao', 'shuliang', 'jianchajileixing', 'jianchazhe', 'created_at')
		// 	->whereBetween('shengchanriqi', [$queryfilter_datefrom, $queryfilter_dateto])
		// 	->get()->toArray();
		$qcreport = Smt_qcreport::select('id', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
			'buliangneirong', 'weihao', 'shuliang', 'jianchajileixing', 'jianchazhe', 'created_at')
			->whereBetween('created_at', [$queryfilter_datefrom, $queryfilter_dateto])
			->get()->toArray();
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
		// $title[] = ['id', '生产日期', '线体', '班次', '机种名', '品名', '工序', 'SP NO.', 'LOT数', '点/枚', '枚数', '合计点数', '不适合件数合计', 'PPM',
		// 	'不良内容', '位号', '数量', '检查机类型', '检查者', '创建日期'];
		$title[] = ['id', '线体', '班次', '机种名', '品名', '工序', 'SP NO.', 'LOT数', '点/枚', '枚数', '合计点数', '不适合件数合计', 'PPM',
			'不良内容', '位号', '数量', '检查机类型', '检查者', '创建日期'];

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
	
	
	
	
	
	
	
	

	
	
	
	
	
}
