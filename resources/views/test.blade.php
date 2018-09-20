<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>Test SmtDailyProductionReport</title>
	<link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">
<style type="text/css">
	/* 解决闪烁问题的CSS */
	[v-cloak] {	display: none; }
</style>
<style type="text/css">
.ivu-table-cell{
	font-size: 12px;
}
</style>

</head>
<body>

	<div id="app" v-cloak>

	




			
				<div>
				<br>
				<br>
				
				<center>
				
				<div>{!! $chart1->container() !!}</div>

				
				{!! $chart1->script() !!}
				
				
				<div>{!! $chart2->container() !!}</div>

				{!! $chart2->script() !!}
				
				
				<div>{!! $chart3->container() !!}</div>

				
				{!! $chart3->script() !!}

				</center>
				
				
				<br>
				<br>
				<br>
				
				<i-button @click="onclick()" type="warning" size="small">Chart</i-button>&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;<i-button @click="onchart()" type="warning" size="small">Refresh Chart</i-button>&nbsp;&nbsp;
				
				<br>
				

111111111111111<br>
				<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
				<div id="main" style="height:400px"></div>
				321
				<!--
				X-axis&nbsp;&nbsp;
				<i-input v-model.lazy="chart_labels" size="small" clearable style="width: 120px"></i-input>
				&nbsp;&nbsp;&nbsp;&nbsp;
				Y-axis&nbsp;&nbsp;
				<i-input v-model.lazy="chart_datasets" size="small" clearable style="width: 120px"></i-input>
				<br>
				width&nbsp;&nbsp;
				<Input-number v-model.lazy="chart_width" :min="400" size="small" style="width: 120px"></Input-number>
				&nbsp;&nbsp;&nbsp;&nbsp;
				height&nbsp;&nbsp;
				<Input-number v-model.lazy="chart_height" :min="200" size="small" style="width: 120px"></Input-number>
				-->


				<br>
				
				<line-chart1 :data="chart_data" :options="chart_options" :width="chart_width" :height="chart_height"></line-chart1>
				
				<br>
				2222222222222222<br>
				</div>
				
				
				

					
					<!--{{ $test }}-->
					{{ $test }}
					<!--{{ $test0 }}-->
					{{ $test0 }}
					
					<br>
					

					<br>
					<i-table height="200" size="small" border :columns="tablecolumns" :data="tabledata"></i-table>
					<br>
					

					<br>

					<br>bottom bottom bottom

	
	
	
	
	
    </div>









	
	

<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>
<script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('statics/vue-chartjs/vue-chartjs.min.js') }}"></script>
<script src="{{ asset('statics/echarts/echarts.js') }}"></script>

<script type="text/javascript">

</script>

<script>
Vue.component('line-chart1', {
	extends: VueChartJs.Line,
	props: ['data', 'options'],
	mounted () {
		this.renderChart(this.data, this.options)
	}
})
</script>


<script>
var vm_app = new Vue({
        el: '#app',
        data: {

			
            tablecolumns: [
				// 1
				{
					title: '班次',
					key: 'class',
					align: 'center',
					width: 40
				},
				// 2
				{
					title: 'No',
					key: 'no',
					align: 'center',
					width: 40,
					filters: [
						{
							label: 'Joe',
							value: 1
						},
						{
							label: 'John',
							value: 2
						}
					],
					filterMultiple: false,
					filterMethod: function (value, row) {
						if (value === 1) {
							return row.name === 'Joe';
						} else if (value === 2) {
							return row.name === 'John Brown';
						}
					}
				},
				// 3
				{
					title: 'Other1',
					align: 'center',
					children: [
						{
							title: '机种名',
							key: 'type',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: 'SP NO.',
							key: 'spno',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '品名',
							key: 'name',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: 'LOT数',
							key: 'lot',
							align: 'center',
							width: 60,
							sortable: true
						}

					]
				},
				// 4
				{
					title: '程序',
					align: 'center',
					children: [
						{
							title: '工序',
							key: 'type',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '点/枚',
							key: 'spno',
							align: 'center',
							width: 60,
							sortable: true
						}
					]
				},
				// 5
				{
					title: '生产预定及实际',
					align: 'center',
					children: [
						{
							title: '时间枚/秒',
							key: 'type',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '枚数',
							key: 'spno',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '台数',
							key: 'type',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: 'LOT残',
							key: 'spno',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '插件点数',
							key: 'type',
							align: 'center',
							width: 60,
							sortable: true
						},
						{
							title: '嫁动率',
							key: 'spno',
							align: 'center',
							width: 60,
							sortable: true
						}
					]
				},
				
				// 6
				{
					title: '合计（分）',
					key: 'gender',
					align: 'center',
					width: 100

				},
				
				// 7
				{
					title: '机器未运转时间（分）',
					align: 'center',
					children: [
						{
							title: '1',
							align: 'center',
							children: [
								{
									title: '机种切换',
									align: 'center',
									children: [
										{
											title: 'N新',
											align: 'center',
											width: 60
										},
										{
											title: 'R量产',
											align: 'center',
											width: 60
										}
									]
								}
							]
						},
						{
							title: '2',
							align: 'center',
							children: [
								{
									title: '部品待',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '3',
							align: 'center',
							children: [
								{
									title: '计划无',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '4',
							align: 'center',
							children: [
								{
									title: '前后工程等待',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '5',
							align: 'center',
							children: [
								{
									title: '部品切',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '6',
							align: 'center',
							children: [
								{
									title: '部品段取等待',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '7',
							align: 'center',
							children: [
								{
									title: '定期点检',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '8',
							align: 'center',
							children: [
								{
									title: '问题',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '9',
							align: 'center',
							children: [
								{
									title: '部品补充',
									align: 'center',
									width: 60
								}
							]
						},
						{
							title: '10',
							align: 'center',
							children: [
								{
									title: '试作',
									align: 'center',
									width: 60
								}
							]
						}
					]

				},
				
				// 8
				{
					title: '品质确认',
					align: 'center',
					children: [
						{
							title: '定数确认',
							key: 'type',
							align: 'center',
							width: 50
						},
						{
							title: '外观确认',
							key: 'spno',
							align: 'center',
							width: 50
						},
						{
							title: '担当者',
							key: 'type',
							align: 'center',
							width: 50
						},
						{
							title: '确认者',
							key: 'spno',
							align: 'center',
							width: 50
						}
					]
				}
			],
			tabledata: [],
			
			

			data5: [
				{
					name: 'John Brown',
					age: 18,
					address: 'New York No. 1 Lake Park',
					date: '2016-10-03'
				},
				{
					name: 'Jim Green',
					age: 24,
					address: 'London No. 1 Lake Park',
					date: '2016-10-01'
				},
				{
					name: 'Joe Black',
					age: 30,
					address: 'Sydney No. 1 Lake Park',
					date: '2016-10-02'
				},
				{
					name: 'Joe Black1',
					age: 30,
					address: 'Sydney No. 1 Lake Park',
					date: '2016-10-02'
				},
				{
					name: 'Joe Black2',
					age: 30,
					address: 'Sydney No. 1 Lake Park',
					date: '2016-10-02'
				},
				{
					name: 'Joe Black3',
					age: 30,
					address: 'Sydney No. 1 Lake Park',
					date: '2016-10-02'
				},
				{
					name: 'Jon Snow',
					age: 26,
					address: 'Ottawa No. 2 Lake Park',
					date: '2016-10-04'
				}
			],
			
			
			
			

			// echarts ajax使用 这个才是实际使用的
			chart_type: 'line',
			
			chart_option_tooltip_show: true,
			
			chart_option_legend_data: ['销量1', '销量2'],
			
			chart_option_xAxis_data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],
			
			chart_option_series: [
				{
					name: '销量1',
					type: 'line',
					data: [5, 20, 40, 10, 10, 20],
					markLine: {
						data: [
							{type: 'average', name: '平均值'}
						]
					}
				},
				{
					'name': '销量2',
					'type': 'line',
					'data': [15, 120, 140,110, 110, 123]
				}
			],

			

				
				
			
			
			
			// chart
			chart_data: {
				labels: ['January1', 'February2', 'March3', 'April4', 'May5', 'June6', 'July7'], //['January', 'February', '33', '44', '55', '66'],
				datasets: [
					{
						label: 'GitHub Commits1',
						backgroundColor: '#f8797963',
						data: [40, 20, 23, 33, 54, 93, 55]
					},
					{
						label: 'GitHub Commits2',
						backgroundColor: '#f8797963',
						data: [30, 25, 33, 53, 24, 23, 65]
					},
				]
			},
			
			chart_options: {responsive: false, maintainAspectRatio: true},
			
	
			chart_width: 600,
			chart_height: 300,

			
        },
		methods: {
			
			// echarts public function 显示用的公共函数
			chart_function: function () {
				// 路径配置
				require.config({
					paths: {
						// echarts: 'http://echarts.baidu.com/build/dist'
						echarts: "{{ asset('statics/echarts') }}"
					}
				});
				
				// 使用
				require(
					[
						'echarts',
						// 'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
						'echarts/chart/' + vm_app.chart_type
					],
					function (ec) {
						// 基于准备好的dom，初始化echarts图表
						var myChart = ec.init(document.getElementById('main')); 
						
						var option = {
							title : {
								text: '未来一周气温变化',
								subtext: '纯属虚构'
							},
							tooltip: {
								show: vm_app.chart_option_tooltip_show,
								trigger: 'axis'
							},
							legend: {
								data: vm_app.chart_option_legend_data
							},
							xAxis : [
								{
									type : 'category',
									data : vm_app.chart_option_xAxis_data
								}
							],
							yAxis : [
								{
									type : 'value',
									axisLabel : {
										// formatter: '{value} °C'
										formatter: '{value} 单位'
									}
								}
							],
							series : vm_app.chart_option_series
						};
				
						// 为echarts对象加载数据 
						myChart.setOption(option, false); 
					}
				);
			},				
			
		
			// 显示图表
			onclick: function (selection) {
				
				this.chart_function();

			},
			
			// ajax返回后显示图表
			onchart: function () {
				var _this = this;
				
				var url = "{{ route('test.chart') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url, {
					// tableselect: tableselect
				})
				.then(function (response) {
					// [{"data":[3,4,1,15,20,23],"name":"Sample Test1","type":"line"},{"data":[1,41,3,23,5,15],"name":"Sample Test2","type":"line"}]
					console.log(response.data);
					
					// alert(response.data[0].type);
					_this.chart_type = response.data[0].type;
					
					_this.chart_option_series = response.data;
					_this.chart_function();

				})
				.catch(function (error) {
					console.log(error);
				})				
				
			},
			
			

		},
		mounted: function () {
			const data = [];
            for (let i = 0; i < 20; i++) {
                data.push({
                    key: i,
                    name: 'John Brown',
                    age: i + 1,
                    street: 'Lake Park',
                    building: 'C',
                    door: 2035,
                    caddress: 'Lake Street 42',
                    cname: 'SoftLake Co',
                    gender: 'M',
                });
            }
            this.data10 = data;
			
			
		}
    })
</script>


</body>
</html>