@extends('smt.layouts.mainbase')

@section('my_title')
SMT - daily production report 
@parent
@endsection

@section('my_style')
<style>
.ivu-table td.table-info-column{
	background-color: #2db7f5;
	color: #fff;
}
</style>
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_project')
<strong>SMT Daily Production Report</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Tabs type="card" v-model="currenttabs">
		<Tab-pane label="生产信息录入">

			<Divider orientation="left">生产基本信息</Divider>

			<i-row :gutter="16">
				<i-col span="4">
					* 线体&nbsp;&nbsp;
					<i-select v-model.lazy="xianti" clearable style="width:120px" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
					* 班次&nbsp;&nbsp;
					<i-select v-model.lazy="banci" clearable style="width:120px" placeholder="">
						<i-option v-for="item in option_banci" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
				</i-col>
			</i-row>

			<br><br><br>

			<i-row :gutter="16">
				<i-col span="4">
					* 机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming" @on-blur="load_jizhongming()" @on-keyup="jizhongming=jizhongming.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					* SP NO.&nbsp;&nbsp;
					<i-input v-model.lazy="spno" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					* 品名&nbsp;&nbsp;
					<i-select v-model.lazy="select_pinming" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_pinming" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
					* LOT数&nbsp;&nbsp;
					<Input-number v-model.lazy="lotshu" :min="1" size="small" style="width: 120px"></Input-number>
				</i-col>
				<i-col span="4">
					* 工序&nbsp;&nbsp;
					<i-select v-model.lazy="select_gongxu" clearable style="width:120px" size="small" placeholder="">
						<i-option v-for="item in option_gongxu" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="4">
				</i-col>
			</i-row>

			<br><br>

			<i-row :gutter="16">
				<i-col span="4">
					* 枚/秒&nbsp;&nbsp;
					<Input-number v-model.lazy="meimiao" :min="1" size="small" style="width: 120px"></Input-number>
				</i-col>
				<i-col span="4">
					* 枚数&nbsp;&nbsp;
					<Input-number v-model.lazy="meishu" :min="1" size="small" style="width: 120px"></Input-number>
				</i-col>
				<i-col span="16">
				</i-col>
			</i-row>
			<br><br>

			<Divider orientation="left">机器未运转时间（分）</Divider>

			<i-row :gutter="16">
				<i-col span="3">
					1.新产&nbsp;&nbsp;
					<Input-number v-model.lazy="xinchan" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="3">
					1.量产&nbsp;&nbsp;
					<Input-number v-model.lazy="liangchan" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="2">
				&nbsp;
				</i-col>
				<i-col span="4">
					2.等待部品&nbsp;&nbsp;
					<Input-number v-model.lazy="dengdaibupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					3.无计划&nbsp;&nbsp;
					<Input-number v-model.lazy="wujihua" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					4.前后工程等待&nbsp;&nbsp;
					<Input-number v-model.lazy="qianhougongchengdengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					5.无部品&nbsp;&nbsp;
					<Input-number v-model.lazy="wubupin" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<i-col span="4">
					6.部品安排等待&nbsp;&nbsp;
					<Input-number v-model.lazy="bupinanpaidengdai" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					7.定期点检&nbsp;&nbsp;
					<Input-number v-model.lazy="dingqidianjian" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					8.故障&nbsp;&nbsp;
					<Input-number v-model.lazy="guzhang" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					9.部品补充&nbsp;&nbsp;
					<Input-number v-model.lazy="bupinbuchong" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
					10.试作&nbsp;&nbsp;
					<Input-number v-model.lazy="shizuo" :min="1" size="small" style="width: 80px"></Input-number>
				</i-col>
				<i-col span="4">
				</i-col>
			</i-row>
			
			<br><br>
			
			<i-row :gutter="16">
				<i-col span="8">
					记载事项&nbsp;<i-button @click="modal_jizhaishixiang=true" type="text" size="small"><font color="#2db7f5">[查看说明]</font></i-button><br>
					<i-input type="textarea" :rows="3" v-model.lazy="jizaishixiang" size="small" placeholder="" clearable style="width: 400px"></i-input>
				</i-col>
				<i-col span="16">
					<br>&nbsp;&nbsp;<i-button @click="create()" type="primary" size="large">记入</i-button>
					&nbsp;&nbsp;<i-button @click="clear()" size="large">清除</i-button>
				</i-col>
			</i-row>
			<br><br>

		</Tab-pane>


		<Tab-pane label="生产信息表">
			<br>
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="6">
					* 日期范围&nbsp;&nbsp;
					<Date-picker v-model.lazy="date_filter_pdreport" :options="date_filter_options" @on-change="dailyreportgets(pagecurrent, pagelast)" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="4">
					线体&nbsp;&nbsp;
					<i-input v-model.lazy="xianti_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="xianti_filter=xianti_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					班次&nbsp;&nbsp;
					<i-input v-model.lazy="banci_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="banci_filter=banci_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming_filter" @on-change="dailyreportgets(pagecurrent, pagelast)" @on-keyup="jizhongming_filter=jizhongming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="3">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>
			
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;<br>&nbsp;
				</i-col>
				<i-col span="4">
					导出：&nbsp;&nbsp;&nbsp;&nbsp;
					<i-button type="default" size="small" @click="exportData_pdreport()"><Icon type="ios-download-outline"></Icon> 导出后台数据</i-button>
				</i-col>
				<i-col span="18">
					&nbsp;
				</i-col>
			</i-row>
			<br><br>
			
			<i-row :gutter="16">
				
				<i-col span="2">
					<i-button @click="ondelete()" :disabled="boo_delete" type="warning" size="small">Delete</i-button>&nbsp;&nbsp;
				</i-col>
				<i-col span="3">
					担当者&nbsp;&nbsp;
					<i-select v-model.lazy="select_dandangzhe" :disabled="disabled_dandangzhe" @on-change="value => dandangzhechange(value)" clearable style="width:80px" size="small" placeholder="">
						<i-option v-for="item in option_dandangzhe" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="3">
					确认者&nbsp;&nbsp;
					<i-select v-model.lazy="select_querenzhe" :disabled="disabled_querenzhe" @on-change="value => querenzhechange(value)" clearable style="width:80px" size="small" placeholder="">
						<i-option v-for="item in option_querenzhe" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				
				<i-col span="8">
				&nbsp;
				</i-col>
				
				<i-col span="8">
					&nbsp;&nbsp;&nbsp;<strong>插件点数小计：@{{ xiaoji_chajiandianshu.toLocaleString() }} &nbsp;&nbsp;&nbsp;&nbsp;稼动率小计：@{{ parseFloat(xiaoji_jiadonglv * 100) + '%' }} &nbsp;&nbsp;&nbsp;&nbsp;合计（分）：@{{ hejifen }}</strong>&nbsp;&nbsp;
				</i-col>
			</i-row>
			<br><br>
			
			
			<i-table height="300" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange(selection)"></i-table>
			<br>
			
			<Modal v-model="modal_jizhaishixiang" title="机器未运转原因区分表" width="540">
				<div style="text-align:center">
					<i-table height="300" size="small" border :columns="tablecolumns3" :data="tabledata3"></i-table>
				</div>
			</Modal>

			<br>
			<i-table height="400" size="small" border :columns="tablecolumns2" :data="tabledata2"></i-table>

		</Tab-pane>

	</Tabs>

</div>
@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	data: {
		// 日期
		// daily_date: new Date(),
		
		// 记载事项说明
		modal_jizhaishixiang: false,
		
		// 担当者
		disabled_dandangzhe: true,
		select_dandangzhe: '',
		option_dandangzhe: [
			{
				value: '庄慧',
				label: '庄慧'
			},
			{
				value: '曹平兰',
				label: '曹平兰'
			}
		],
		
		// 确认者
		disabled_querenzhe: true,
		select_querenzhe: '',
		option_querenzhe: [
			{
				value: '庄慧1',
				label: '庄慧1'
			},
			{
				value: '曹平兰1',
				label: '曹平兰1'
			}
		],
		
		// 线体
		select_xianti: '',
		option_xianti: [
			{
				value: 'SMT-1',
				label: 'SMT-1'
			},
			{
				value: 'SMT-2',
				label: 'SMT-2'
			},
			{
				value: 'SMT-3',
				label: 'SMT-3'
			},
			{
				value: 'SMT-4',
				label: 'SMT-4'
			},
			{
				value: 'SMT-5',
				label: 'SMT-5'
			},
			{
				value: 'SMT-6',
				label: 'SMT-6'
			},
			{
				value: 'SMT-7',
				label: 'SMT-7'
			},
			{
				value: 'SMT-8',
				label: 'SMT-8'
			},
			{
				value: 'SMT-9',
				label: 'SMT-9'
			},
			{
				value: 'SMT-10',
				label: 'SMT-10'
			}
		],
		
		// 线体
		xianti: '',
		select_xianti: '',
		option_xianti: [
			{
				value: 'SMT-1',
				label: 'SMT-1'
			},
			{
				value: 'SMT-2',
				label: 'SMT-2'
			},
			{
				value: 'SMT-3',
				label: 'SMT-3'
			},
			{
				value: 'SMT-4',
				label: 'SMT-4'
			},
			{
				value: 'SMT-5',
				label: 'SMT-5'
			},
			{
				value: 'SMT-6',
				label: 'SMT-6'
			},
			{
				value: 'SMT-7',
				label: 'SMT-7'
			},
			{
				value: 'SMT-8',
				label: 'SMT-8'
			},
			{
				value: 'SMT-9',
				label: 'SMT-9'
			},
			{
				value: 'SMT-10',
				label: 'SMT-10'
			}		
		],
		
		// 班次
		banci: '',
		select_banci: '',
		option_banci: [
			{
				value: 'A-1',
				label: 'A-1'
			},
			{
				value: 'A-2',
				label: 'A-2'
			},
			{
				value: 'A-3',
				label: 'A-3'
			},
			{
				value: 'B-1',
				label: 'B-1'
			},
			{
				value: 'B-2',
				label: 'B-2'
			},
			{
				value: 'B-3',
				label: 'B-3'
			}
		],
		
		// 机种名
		jizhongming: '',
		
		//sp no.
		spno: '',
		
		//品名
		select_pinming: '',
		option_pinming: [],
		
		//lot数
		lotshu: '',
		
		//枚/秒
		meimiao: '',
		
		//枚数
		meishu: '',
		
		//台数
		taishu: '',
		
		//工序
		select_gongxu: '',
		option_gongxu: [],
		
		// 异常
		xinchan: '',
		liangchan: '',
		dengdaibupin: '',
		wujihua: '',
		qianhougongchengdengdai: '',
		wubupin: '',
		bupinanpaidengdai: '',
		dingqidianjian: '',
		guzhang: '',
		bupinbuchong: '',
		shizuo: '',
		jizaishixiang: '',
		
		
		// 表头1
		tablecolumns1: [
			{
				type: 'selection',
				width: 50,
				align: 'center'
			},
			// 1
			{
				type: 'index',
				width: 40,
				align: 'center'
			},
			// 1
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80
			},
			// 1
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 60,
				filters: [
					{
						label: 'A-1',
						value: 'A-1'
					},
					{
						label: 'A-2',
						value: 'A-2'
					},
					{
						label: 'A-3',
						value: 'A-3'
					},
					{
						label: 'B-1',
						value: 'B-1'
					},
					{
						label: 'B-2',
						value: 'B-2'
					},
					{
						label: 'B-3',
						value: 'B-3'
					}
				],
				filterMultiple: false,
				filterMethod: function (value, row) {
					if (value === 'A-1') {
						return row.banci === 'A-1';
					} else if (value === 'A-2') {
						return row.banci === 'A-2';
					} else if (value === 'A-3') {
						return row.banci === 'A-3';
					} else if (value === 'B-1') {
						return row.banci === 'B-1';
					} else if (value === 'B-2') {
						return row.banci === 'B-2';
					} else if (value === 'B-3') {
						return row.banci === 'B-3';
					}
				}
			},
			// 3
			{
				title: '班次',
				align: 'center',
				children: [
					{
						title: '机种名',
						key: 'jizhongming',
						align: 'center',
						width: 120,
						sortable: true
					},
					{
						title: 'SP NO.',
						key: 'spno',
						align: 'center',
						width: 140,
						sortable: true
					},
					{
						title: '品名',
						key: 'pinming',
						align: 'center',
						width: 100,
						sortable: true
					},
					{
						title: 'LOT数',
						key: 'lotshu',
						align: 'center',
						width: 100,
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
						key: 'gongxu',
						align: 'center',
						width: 60
					},
					{
						title: '点/枚',
						key: 'dianmei',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.dianmei.toLocaleString()
							]);
						}
					}
				]
			},
			// 5
			{
				title: '生产预定及实际',
				align: 'center',
				children: [
					{
						title: '枚/秒',
						key: 'meimiao',
						align: 'center',
						width: 60
					},
					{
						title: '枚数',
						key: 'meishu',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.meishu.toLocaleString()
							]);
						}
					},
					{
						title: '台数',
						key: 'taishu',
						align: 'center',
						width: 80,
						render: (h, params) => {
							return h('div', [
								params.row.taishu.toLocaleString()
							]);
						}
					},
					{
						title: 'LOT残',
						key: 'lotcan',
						align: 'center',
						width: 80
					},
					{
						title: '插件点数',
						key: 'chajiandianshu',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.chajiandianshu.toLocaleString()
							]);
						}
					},
					{
						title: '稼动率',
						key: 'jiadonglv',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								parseFloat(params.row.jiadonglv * 100) + '%'
							]);
						}
					}
				]
			},
			
			// 1
			{
				title: '创建日期',
				key: 'created_at',
				align: 'center',
				width: 160,
			}

			
			

		],
		tabledata1: [],

		// 表头2
		tablecolumns2: [
			// 1
			{
				type: 'index',
				width: 40,
				align: 'center'
			},
			// 2
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
										title: '新产',
										key: 'xinchan',
										align: 'center',
										width: 80
									},
									{
										title: '量产',
										key: 'liangchan',
										align: 'center',
										width: 80
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
								title: '等待部品',
								key: 'dengdaibupin',
								align: 'center',
								width: 80,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '等待'),
										h('br', {
										}, ''),
										h('span', {
										}, '部品')
									]);
								}
							}
						]
					},
					{
						title: '3',
						align: 'center',
						children: [
							{
								title: '无计划',
								key: 'wujihua',
								align: 'center',
								width: 80
							}
						]
					},
					{
						title: '4',
						align: 'center',
						children: [
							{
								title: '前后工程等待',
								key: 'qianhougongchengdengdai',
								align: 'center',
								width: 80,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '前后工'),
										h('br', {
										}, ''),
										h('span', {
										}, '程等待')
									]);
								}
							}
						]
					},
					{
						title: '5',
						align: 'center',
						children: [
							{
								title: '无部品',
								key: 'wubupin',
								align: 'center',
								width: 80
							}
						]
					},
					{
						title: '6',
						align: 'center',
						children: [
							{
								title: '部品安排等待',
								key: 'bupinanpaidengdai',
								align: 'center',
								width: 80,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '部品安'),
										h('br', {
										}, ''),
										h('span', {
										}, '排等待')
									]);
								}
							}
						]
					},
					{
						title: '7',
						align: 'center',
						children: [
							{
								title: '定期点检',
								key: 'dingqidianjian',
								align: 'center',
								width: 80,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '定期'),
										h('br', {
										}, ''),
										h('span', {
										}, '点检')
									]);
								}
							}
						]
					},
					{
						title: '8',
						align: 'center',
						children: [
							{
								title: '故障',
								key: 'guzhang',
								align: 'center',
								width: 80
							}
						]
					},
					{
						title: '9',
						align: 'center',
						children: [
							{
								title: '部品补充',
								key: 'bupinbuchong',
								align: 'center',
								width: 80,
								renderHeader: (h, params) => {
									return h('div', [
										h('span', {
										}, '部品'),
										h('br', {
										}, ''),
										h('span', {
										}, '补充')
									]);
								}
							}
						]
					},
					{
						title: '10',
						align: 'center',
						children: [
							{
								title: '试作',
								key: 'shizuo',
								align: 'center',
								width: 80
							}
						]
					},
					{
						title: '记载事项<br>查看说明',
						key: 'jizaishixiang',
						align: 'center',
						width: 200,
						renderHeader: (h, params) => {
							return h('div', [
								h('span', {
								}, '记 载 事 项'),
								h('br', {
								}, ''),
								h('Button', {
									props: {
										type: 'text',
										size: 'small'
									},
									on: {
										click: () => {
											// vm_app.viewmpoint(params.row)
											vm_app.modal_jizhaishixiang = true
										}
									}
								}, '查看说明')
								
							]);
						}
					}
				]

			},
			
			// 3
			{
				title: '品质确认',
				align: 'center',
				children: [
					{
						title: '定数确认',
						key: 'dingshuqueren',
						align: 'center',
						width: 50
					},
					{
						title: '外观确认',
						key: 'waiguanqueren',
						align: 'center',
						width: 50
					},
					{
						title: '担当者',
						key: 'dandangzhe',
						align: 'center',
						width: 100
					},
					{
						title: '确认者',
						key: 'querenzhe',
						align: 'center',
						width: 100
					}
				]
			}
		],
		tabledata2: [],
		
		
		// 未运转说明表
		// 表头3
		tablecolumns3: [
			{
				title: '机器未运转原因区分表',
				align: 'center',
				children: [
					{
						type: 'index',
						width: 60,
						align: 'center'
					},
					{
						title: '原因区分',
						key: 'yuanyinqufeng',
						align: 'left',
						width: 150
					},
					{
						title: '内容',
						key: 'neirong',
						align: 'left'
					}
				]
			}
		],
		tabledata3: [
			{
				yuanyinqufeng: '机种切换',
				neirong: '因切换机种而停机的时间'
			},
			{
				yuanyinqufeng: '部品待ち',
				neirong: '待基板/待设计确认'
			},
			{
				yuanyinqufeng: '計画なし',
				neirong: '无计划STOP/来客对应准备STOP/天灾STOP/早礼'
			},
			{
				yuanyinqufeng: '前後工程待ち',
				neirong: '程(等待基板,检查机/初物检查)'
			},
			{
				yuanyinqufeng: '部品切れ',
				neirong: '资材出库的基板・部品出库错误/实装错误造成的部品不足'
			},
			{
				yuanyinqufeng: '部品段取待ち',
				neirong: '等待资材部品准备/部品组装'
			},
			{
				yuanyinqufeng: '定期点検',
				neirong: '班次,日,周,月的定期点检时间'
			},
			{
				yuanyinqufeng: 'トラブル',
				neirong: 'OP设备还原对应不了的停止(设备故障/品质关联等的设备调整,停止)'
			},
			{
				yuanyinqufeng: 'ﾁｮｺ停部品補充',
				neirong: '部品补充'
			},
			{
				yuanyinqufeng: '試作',
				neirong: 'PP/AP生产'
			}

		],
		
		// 删除disabled
		boo_delete: true,

		// 更新disabled
		boo_update: true,

		// 过滤变量
		date_filter_pdreport: [],//new Date(),
		date_filter_options: {
			shortcuts: [
				{
					text: '前 1 周',
					value () {
						const end = new Date();
						const start = new Date();
						// start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
						start.setDate(start.getDate() - 7);
						return [start, end];
					}
				},
				{
					text: '前 1 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 30);
						return [start, end];
					}
				},
				{
					text: '前 3 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 90);
						return [start, end];
					}
				},
				{
					text: '前 6 月',
					value () {
						const end = new Date();
						const start = new Date();
						start.setDate(start.getDate() - 180);
						return [start, end];
					}
				},
				{
					text: '前 1 年',
					value () {
						const end = new Date();
						const start = new Date();
						// start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
						start.setDate(start.getDate() - 365);
						return [start, end];
					}
				},
			]
		},		
		xianti_filter: '',
		banci_filter: '',
		jizhongming_filter: '',
		
		//
		xiaoji_chajiandianshu: 0,
		xiaoji_jiadonglv: 0,
		hejifen: 0,
		
		//分页
		pagecurrent: 1,
		pagetotal: 1,
		pagepagesize: 10,
		pagelast: 1,

		// tabs索引
		currenttabs: 0,

			
	},
	methods: {
		// 2.Notice 通知提醒
		info (nodesc, title, content) {
			this.$Notice.info({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		success (nodesc, title, content) {
			this.$Notice.success({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		warning (nodesc, title, content) {
			this.$Notice.warning({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		error (nodesc, title, content) {
			this.$Notice.error({
				title: title,
				desc: nodesc ? '' : content
			});
		},

		alert_logout: function () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},
		
		datepickerchange: function (date) {
			if (typeof(date)=='string') {
				return date;
			} else {
				return date.Format("yyyy-MM-dd");
			}
		},
		
		//
		load_jizhongming: function () {
			var _this = this;
			if (_this.jizhongming.trim() == '') {
				_this.jizhongming = '';
				return false;
			}
			
			var jizhongming = _this.jizhongming;
			
			var url = "{{ route('smt.pdreport.getjizhongming') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					jizhongming: jizhongming
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.error(false, '错误', '登录失效，请重新登录！');
					window.setTimeout(function(){
						window.location.href = "{{ route('portal') }}";
					}, 2000);
				}
				
				if (response.data) {
					var tmp_pinming = '';
					var tmp_gongxu = '';
					var boo_flag = false;
					
					_this.option_pinming = [];
					_this.option_gongxu = [];
					for (var i in response.data) {
						// pinming
						tmp_pinming = response.data[i].pinming;
						
						if (_this.option_pinming.length != 0) {
							for (var j in _this.option_pinming) {
								if (_this.option_pinming[j].value == tmp_pinming) {
									boo_flag = true;
									break;
								} else {
									boo_flag = false;
								}
							}
							if (boo_flag == false) {
								_this.option_pinming.push({value: tmp_pinming, label: tmp_pinming});
							}
						} else {
							_this.option_pinming.push({value: tmp_pinming, label: tmp_pinming});
						}
						
						// gongxu
						tmp_gongxu = response.data[i].gongxu;
						
						if (_this.option_gongxu.length != 0) {
							for (var j in _this.option_gongxu) {
								if (_this.option_gongxu[j].value == tmp_gongxu) {
									boo_flag = true;
									break;
								} else {
									boo_flag = false;
								}
							}
							if (boo_flag == false) {
								_this.option_gongxu.push({value: tmp_gongxu, label: tmp_gongxu});
							}
						} else {
							_this.option_gongxu.push({value: tmp_gongxu, label: tmp_gongxu});
						}
						
					}
				}
			})
			.catch(function (error) {
				// console.log(error);
			})				
		},
		
		//
		clear: function () {
			var _this = this;
			_this.jizhongming = '';
			_this.spno = '';
			_this.select_pinming = '';
			_this.lotshu = '';
			_this.meimiao = '';
			_this.meishu = '';
			_this.taishu = '';
			_this.select_gongxu = '';
			_this.xinchan = '';
			_this.liangchan = '';
			_this.dengdaibupin = '';
			_this.wujihua = '';
			_this.qianhougongchengdengdai = '';
			_this.wubupin = '';
			_this.bupinanpaidengdai = '';
			_this.dingqidianjian = '';
			_this.guzhang = '';
			_this.bupinbuchong = '';
			_this.shizuo = '';
			_this.jizaishixiang = '';
		},
		
		// create
		create: function () {
			var _this = this;
			
			var xianti = _this.xianti;
			var banci = _this.banci;
			var jizhongming = _this.jizhongming;
			var spno = _this.spno;
			var pinming = _this.select_pinming;
			var lotshu = _this.lotshu;
			var gongxu = _this.select_gongxu;
			var meimiao = _this.meimiao;
			var meishu = _this.meishu;
			
			
			if (xianti == '' || banci == '' || jizhongming == '' || spno == ''  || pinming == '' || lotshu == '' || meimiao == '' || meishu == '' || gongxu == ''
				|| xianti == undefined || banci == undefined || jizhongming == undefined || spno == undefined || pinming == undefined || lotshu == undefined || meimiao == undefined || meishu == undefined || gongxu == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}

			var url = "{{ route('smt.pdreport.dailyreportcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				xianti : xianti,
				banci : banci,
				jizhongming : jizhongming,
				spno : spno,
				pinming : pinming,
				lotshu : lotshu,
				gongxu : gongxu,
				meimiao : meimiao,
				meishu : meishu
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '记入成功！');
					// _this.clear();
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},
		
		//
		onselectchange: function (selection) {
			// console.log(row);
			var _this = this;
			_this.tableselect = [];

			for (var i in selection) {
				_this.tableselect.push(selection[i].id);
			}
			// console.log(_this.tableselect);
			
			_this.boo_delete = _this.tableselect[0] == undefined ? true : false;

			// 担当者
			_this.disabled_dandangzhe = _this.tableselect[0] == undefined ? true : false;
			
			// 确认者
			_this.disabled_querenzhe = _this.tableselect[0] == undefined ? true : false;
		},

		
		//
		ondelete: function (selection) {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) {
				return false;
			}

			var url = "{{ route('smt.pdreport.dailyreportdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect: tableselect
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.tableselect = [];
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},

		// dailyreport列表
		dailyreportgets: function(page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var date_filter_pdreport = [];

			for (var i in _this.date_filter_pdreport) {
				if (typeof(_this.date_filter_pdreport[i])!='string') {
					date_filter_pdreport.push(_this.date_filter_pdreport[i].Format("yyyy-MM-dd"));
				} else if (_this.date_filter_pdreport[i] == '') {
					date_filter_pdreport.push(new Date().Format("yyyy-MM-dd"));
					// _this.tabledata_relation = [];
					// return false;
				} else {
					date_filter_pdreport.push(_this.date_filter_pdreport[i]);
				}
			}
			
			var url = "{{ route('smt.pdreport.dailyreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					dailydate_filter : date_filter_pdreport,
					xianti_filter : _this.xianti_filter,
					banci_filter : _this.banci_filter,
					jizhongming_filter: _this.jizhongming_filter,
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.pagecurrent = response.data.current_page;
					_this.pagetotal = response.data.total;
					_this.pagelast = response.data.last_page
					
					_this.tabledata1 = response.data.data;
					_this.tabledata2 = response.data.data;
					
					// 合计
					_this.xiaoji_chajiandianshu = 0;
					_this.xiaoji_jiadonglv = 0;
					// _this.hejifen = 0;
					for (var i in _this.tabledata1) {
						_this.xiaoji_chajiandianshu += _this.tabledata1[i].chajiandianshu;
						_this.xiaoji_jiadonglv += _this.tabledata1[i].jiadonglv;
					}
					_this.hejifen = 720 * _this.xiaoji_jiadonglv;
				
				} else {
					_this.tabledata1 = [];
					_this.tabledata2 = [];
				}
				
				// 恢复禁用状态
				_this.boo_delete = true;
				_this.disabled_dandangzhe = true;
				_this.disabled_querenzhe = true;
				_this.select_dandangzhezhe = '';
				_this.select_querenzhe = '';
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
			})
		},
		
		// 担当者变更
		dandangzhechange: function (value) {
			if (value == undefined ) return false;
			var _this = this;
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('smt.pdreport.dandangzhechange') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: tableselect,
				dandangzhe : value
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
					
					// _this.boo_delete = true;
					// _this.disabled_dandangzhe = true;
					// _this.disabled_querenzhe = true;
					// _this.select_dandangzhe = '';
				} else {
					_this.error(false, '失败', '更新失败！');
				}
			})
			.catch(function (error) {
				console.log(error);
				_this.error(false, '错误', '更新失败！');
			})			
		},

		// 确认者变更
		querenzhechange: function (value) {
			if (value == undefined ) return false;
			var _this = this;
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('smt.pdreport.querenzhechange') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: tableselect,
				querenzhe : value
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					_this.dailyreportgets(_this.pagecurrent, _this.pagelast);
					
					// _this.boo_delete = true;
					// _this.disabled_dandangzhe = true;
					// _this.disabled_querenzhe = true;
					// _this.select_querenzhe = '';
				} else {
					_this.error(false, '失败', '更新失败！');
				}
			})
			.catch(function (error) {
				console.log(error);
				_this.error(false, '错误', '更新失败！');
			})			
		},
		
		
		// 结果表数据导出
		exportData_pdreport: function () {
			var _this = this;
			
			if (_this.date_filter_pdreport[0] == '' || _this.date_filter_pdreport[0] == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.date_filter_pdreport[0].Format("yyyy-MM-dd");
			var queryfilter_dateto = _this.date_filter_pdreport[1].Format("yyyy-MM-dd");
			
			var url = "{{ route('smt.pdreport.pdreportexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			// console.log(url);
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},		
		
	},
	mounted: function () {
		// var _this = this;
		// _this.date_filter_pdreport = new Date().Format("yyyy-MM-dd");
		// _this.dailyreportgets(1, 1); // page: 1, last_page: 1
	
		
	}
})
</script>
@endsection