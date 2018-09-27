<?php

namespace App\Http\Controllers\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use App\Models\Smt\Smt_mpoint;
use App\Models\Smt\Smt_qcreport;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Smt\qcreportExport;
use App\Imports\qcreportImport;
use App\Charts\Smt\ECharts;

use Illuminate\Support\Facades\Storage;


class qcreportController extends Controller
{
    //
	public function qcreport () {
		
		return view('smt.qcreport');
		
	}
	
	
    /**
     * qcreportGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function qcreportGets(Request $request)
    {
		if (! $request->ajax()) { return null; }

		$perPage = $request->input('perPage') ?: 10000;
		$page = $request->input('page') ?: 1;

		$qcdate_filter = $request->input('qcdate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		$xianti_filter = $request->input('xianti_filter');
		$buliangneirong_filter = $request->input('buliangneirong_filter');
		
		// $mpoint = DB::table('mpoints')
		// $dailyreport = Smt_qcreport::select('*', DB::raw('dianmei * meishu as hejidianshu'))
			// ->when($qcdate_filter, function ($query) use ($qcdate_filter) {
				// return $query->whereBetween('created_at', $qcdate_filter);
			// })
			// ->when($xianti_filter, function ($query) use ($xianti_filter) {
				// return $query->where('xianti', 'like', '%'.$xianti_filter.'%');
			// })
			// ->when($banci_filter, function ($query) use ($banci_filter) {
				// return $query->where('banci', 'like', '%'.$banci_filter.'%');
			// })
			// ->orderBy('created_at', 'asc')
			// ->paginate($perPage, ['*'], 'page', $page);

		$dailyreport = Smt_qcreport::when($qcdate_filter, function ($query) use ($qcdate_filter) {
				return $query->whereBetween('created_at', $qcdate_filter);
			})
			->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
			})
			->when($xianti_filter, function ($query) use ($xianti_filter) {
				return $query->where('xianti', 'like', '%'.$xianti_filter.'%');
			})
			->when($buliangneirong_filter, function ($query) use ($buliangneirong_filter) {
				return $query->where('buliangneirong', 'like', '%'.$buliangneirong_filter.'%');
			})
			->orderBy('created_at', 'asc')
			->paginate($perPage, ['*'], 'page', $page);
		

		// dd($dailyreport);
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
		if (! $request->ajax()) { return null; }

		$saomiao = $request->input('saomiao');
		
		if ($saomiao == null) return 0;
		
		try {
			$saomiao_arr = explode('/', $saomiao);
			
			$jizhongming = $saomiao_arr[0];
			$spno = $saomiao_arr[1];
			$pinming = $saomiao_arr[2];
			$lotshu = $saomiao_arr[3];

			$result = Smt_qcreport::where('jizhongming', $jizhongming)
				->where('pinming', $pinming)
				->where('spno', $spno)
				->where('lotshu', $lotshu)
				->first();
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
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }
		
		$saomiao = $request->input('saomiao');
		$xianti = $request->input('xianti');
		$banci = $request->input('banci');
		$gongxu = $request->input('gongxu');
		$dianmei = $request->input('dianmei');
		$meishu = $request->input('meishu');
		$piliangluru = $request->input('piliangluru');

		$saomiao_arr = explode('/', $saomiao);
		
		$s['jizhongming'] = $saomiao_arr[0];
		$s['spno'] = $saomiao_arr[1];
		$s['pinming'] = $saomiao_arr[2];
		$s['lotshu'] = $saomiao_arr[3];
		
		$s['xianti'] = $xianti;
		$s['banci'] = $banci;
		$s['gongxu'] = $gongxu;
		$s['dianmei'] = $dianmei;
		$s['meishu'] = $meishu;
		$s['hejidianshu'] = $dianmei * $meishu;
		
		$s['bushihejianshuheji'] = 0;
		foreach ($piliangluru as $value) {
			$s['bushihejianshuheji'] += $value['shuliang'];
		}

		if ($s['bushihejianshuheji'] == 0) {
			$s['ppm'] = 0;
		} else {
			$s['ppm'] = $s['bushihejianshuheji'] / $s['hejidianshu'] * 1000000;
		}
		
		// 不良内容为一维数组，字符串化
		foreach ($piliangluru as $key => $value) {
			$piliangluru[$key]['buliangneirong'] = implode(',', $value['buliangneirong']);
		}
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
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$id = $request->input('tableselect1');

		try	{
			$result = Smt_qcreport::whereIn('id', $id)->delete();
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
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


		$qcreport = Smt_qcreport::select('id', 'created_at', 'xianti', 'banci', 'jizhongming', 'pinming', 'gongxu', 'spno', 'lotshu', 'dianmei', 'meishu', 'hejidianshu', 'bushihejianshuheji', 'ppm',
			'buliangneirong', 'weihao', 'shuliang', 'jianchajileixing', 'jianchazhe')
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
		$title[] = ['id', '日期', '线体', '班次', '机种名', '品名', '工序', 'SP NO.', 'LOT数', '点/枚', '枚数', '合计点数', '不适合件数合计', 'PPM',
			'不良内容', '位号', '数量', '检查机类型', '检查者'];

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
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

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
		$chart1->dataset('不适合件数合计', 'bar', $hejidianshu)
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
