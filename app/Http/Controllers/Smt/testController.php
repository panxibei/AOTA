<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Charts\SampleChart;
use App\Charts\ECharts; // 注意：此处只用到了ECharts。chart.js都没有测试成功。
use DB;

class testController extends Controller
{
    //
	public function test () {
		
		echo 'aaa';
		
		$test = DB::table('dailyreports')->get();
		// dd($test);
		
		$test0 = json_encode([1,2,3]);
		
		// return view('test', $share);
		
		$chart1 = new SampleChart;
		
		$chart1->labels(['One', 'Two', 'Three', 'Four', 'Five'])
			->options([
				'legend' => [
					'display' => true,
					'position' => 'bottom',
					'labels' => [
						'fontSize' => 16,
						'fontColor' => '#ff0000'
					]
				],
				'borderColor' => [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				'borderWidth' => 10,
				'showLine' => false,
			]);
		
		$chart1->dataset('Sample1', 'line', [110, 65, 84, 45, 90])
			->color('#ff0000')
			// ->backgroundColor([
				// 'rgba(255, 99, 0, 0.2)',
				// 'rgba(54, 162, 235, 0.2)',
				// 'rgba(255, 206, 86, 0.2)',
				// 'rgba(75, 192, 192, 0.2)',
				// 'rgba(153, 102, 255, 0.2)',
				// 'rgba(255, 159, 64, 0.2)'
			// ]);
			->backgroundColor('#33b5e500');
		$chart1->dataset('Sample2', 'line', [20, 165, 44, 75, 30])
			->color('#00ff00')
			->backgroundColor('#33b5e50c');
		
		
		
		
		$chart2 = new SampleChart;
		$chart2->title('aaaaaaaaa')
			->displayLegend(false)
			->labels(['One1', 'Two2', 'Three3', 'Four4', 'Five5'])
			->dataset('Sample Test1', 'bar', [
					3,4,1,5,2
				])
			->color('#00ff00')
			->backgroundColor('#33b5e50c');
		
		
		
		
		
		$chart3 = new SampleChart;
		$chart3->dataset('Sample Test2', 'line', [1,4,3])->color('#ff0000');
		
		$share = compact('test', 'test0', 'chart1', 'chart2', 'chart3');
		// return view('test', ['chart' => $chart]);	
		return view('test', $share);	
		
	}
	
	// test charts
	/**
	* Computes the sample chart.
	*
	* @return Response
	*/
	public function chart()
	{
		// $chart0 = new SampleChart;
		// $chart0->dataset('Sample Test', 'bar', [3,4,1,15])->color('#00ff00');
		// $chart0->dataset('Sample Test', 'line', [1,41,3,23])->color('#ff0000');
		// return $chart0->api();
		
		
		$chart0 = new echarts;
		// $chart0->dataset('Sample Test', 'bar', [3,4,1,15])->color('#00ff00');
		// $chart0->dataset('Sample Test', 'line', [1,41,3,23])->color('#ff0000');
		
		// options，使用数组对象形式
		$chart0->dataset('销量1', 'line', [3,4,1,15,20,23]);
		$chart0->dataset('销量2', 'line', [1,41,3,23,5,15])
			->options([
				'smooth' => true,
				'markPoint' => [
					'data' => [
						['type' => 'max', 'name' => '最大值'],
						['type' => 'min', 'name' => '最小值']
					]
				],
				'markLine' => [
					
					'data' => [
						['type' => 'average', 'name' => '平均值']
					]
					
				],
				'title' => [
					'text' => '未来一周气温变化',
					'subtext' => '纯属虚构'
				],
			]);

		return $chart0->api();
		
	}
	
	
	
}
