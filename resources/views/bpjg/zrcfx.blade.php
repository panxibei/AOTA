@extends('bpjg.layouts.mainbase')

@section('my_title')
部品加工课 - 中日程分析 
@parent
@endsection

@section('my_style')
<style>
.ivu-table td.tableclass1{
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
<strong>部品加工课 - 中日程分析</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Divider orientation="left">线体/机种/部品 对应信息录入</Divider>

	<i-row :gutter="16">
		<i-col span="5">
			* <strong>线体</strong>&nbsp;&nbsp;
			<i-input ref="xianti" v-model.lazy="xianti" @on-keyup="xianti=xianti.toUpperCase()" placeholder="例：HN06" size="large" clearable autofocus style="width: 200px"></i-input>
		</i-col>
		<i-col span="5">
			* <strong>区分</strong>&nbsp;&nbsp;
			<i-input v-model.lazy="qufen" @on-keyup="qufen=qufen.toUpperCase()" size="large" placeholder="例：18ACCORD" clearable style="width: 200px"></i-input>
		</i-col>
		<i-col span="14">
		&nbsp;
		</i-col>
	</i-row>

	<br><br><br>
	
	<i-row :gutter="16">
		<i-col span="24">
			↓ 批量录入&nbsp;&nbsp;
			<Input-number v-model.lazy="piliangluruxiang" @on-change="value=>piliangluru_generate(value)" :min="1" :max="10" size="small" style="width: 60px"></Input-number>
			&nbsp;项
		</i-col>
	</i-row>
	

	<span v-for="(item, index) in piliangluru">
	<br>
	<i-row :gutter="16">
		<i-col span="1">
			&nbsp;No.@{{index+1}}
		</i-col>
		<i-col span="5">
			* 机种名&nbsp;&nbsp;
			<i-input v-model.lazy="item.jizhongming" @on-keyup="item.jizhongming=item.jizhongming.toUpperCase()" size="small" placeholder="例：QH00048" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="5">
			* 品番&nbsp;&nbsp;
			<i-input v-model.lazy="item.pinfan" @on-keyup="item.pinfan=item.pinfan.toUpperCase()" size="small" placeholder="例：07-37361Z02-A" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="5">
			* 品名&nbsp;&nbsp;
			<i-input v-model.lazy="item.pinming" @on-keyup="item.pinming=item.pinming.toUpperCase()" size="small" placeholder="例：SPRING,GND 7L" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="3">
			需求数量&nbsp;&nbsp;
			<Input-number v-model.lazy="item.xuqiushuliang" :min="0" size="small" style="width: 80px"></Input-number>
		</i-col>
		<i-col span="5">
		&nbsp;
		</i-col>
		
	</i-row>
	<br>
	</span>

	<br>

	<i-row :gutter="16">
		<i-col span="24">
			&nbsp;&nbsp;<i-button @click="oncreate()" type="primary">记入</i-button>
			&nbsp;&nbsp;<i-button @click="onclear()">清除</i-button>
			&nbsp;&nbsp;&nbsp;&nbsp;<i-button @click="onimport()">批量导入</i-button>
		</i-col>
	</i-row>

	
	
	<br><br><br>
	<Divider orientation="left">线体/机种/部品 对应信息查询</Divider>

	<i-row :gutter="16">
		<i-col span="2">
			&nbsp;
		</i-col>
		<i-col span="1">
			查询：
		</i-col>
		<i-col span="6">
			* 日期范围&nbsp;&nbsp;
			<Date-picker v-model.lazy="qcdate_filter" @on-change="maingets(pagecurrent, pagelast);onselectchange1();" type="daterange" size="small" placement="top" style="width:200px"></Date-picker>
		</i-col>
		<i-col span="3">
			线体&nbsp;&nbsp;
			<i-input v-model.lazy="xianti_filter" @on-keyup="xianti_filter=xianti_filter.toUpperCase()" placeholder="" size="small" clearable style="width: 80px"></i-input>
		</i-col>
		<i-col span="9">
		&nbsp;
		</i-col>
	</i-row>
	<br><br>

	<i-row :gutter="16">
		<i-col span="3">
			&nbsp;
		</i-col>
		<i-col span="4">
			机种名&nbsp;&nbsp;
			<i-input v-model.lazy="jizhongming_filter" @on-change="maingets(pagecurrent, pagelast)" @on-keyup="jizhongming_filter=jizhongming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="4">
			品名&nbsp;&nbsp;
			<i-input v-model.lazy="pinming_filter" @on-change="maingets(pagecurrent, pagelast)" @on-keyup="pinming_filter=pinming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="3">
			品番&nbsp;&nbsp;
			<i-input v-model.lazy="pinfan_filter" @on-change="maingets(pagecurrent, pagelast)" @on-keyup="pinfan_filter=pinfan_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
		</i-col>

	</i-row>
	<br><br>

	<i-row :gutter="16">
		<br>
		<i-col span="2">
			<i-button @click="ondelete()" :disabled="boo_delete" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
		</i-col>
		<i-col span="8">
			导出：&nbsp;&nbsp;&nbsp;&nbsp;
			<i-button type="default" size="small" @click="exportData_db()"><Icon type="ios-download-outline"></Icon> 导出全部后台数据</i-button>
		</i-col>
		<i-col span="10">
			&nbsp;
		</i-col>
		<i-col span="4">
			&nbsp;
		</i-col>
	</i-row>

	<i-row :gutter="16">
		<i-col span="24">
			<i-table ref="table1" height="400" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange1(selection)"></i-table>
			<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>
		</i-col>
	</i-row>
	
	<Modal v-model="modal_main_edit" @on-ok="qcreport_edit_ok" ok-text="保存" title="工程内不良记录编辑" width="540">
		<div style="text-align:left">
			<p>
				线体：@{{ xianti_edit }}
			
				&nbsp;&nbsp;

				区分：@{{ qufen_edit }}
			
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				创建时间：@{{ created_at_edit }}
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				更新时间：@{{ updated_at_edit }}
			
			</p>
			<br>
			
			<!--<span v-for="(item, index) in piliangbianji">-->
			<p>
				机种名&nbsp;&nbsp;
				<i-input v-model.lazy="jizhongming_edit" @on-keyup="jizhongming_edit=jizhongming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;

				品番&nbsp;&nbsp;
				<i-input v-model.lazy="pinfan_edit" @on-keyup="pinfan_edit=pinfan_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;

				品名&nbsp;&nbsp;
				<i-input v-model.lazy="pinming_edit" @on-keyup="pinming_edit=pinming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;
			
				需求数量&nbsp;&nbsp;
				<Input-number v-model.lazy="xuqiushuliang_edit[1]" :min="0" size="small" style="width: 80px"></Input-number>

				&nbsp;&nbsp;&nbsp;&nbsp;
			</p>
			<br>
			<!--</span>-->
			
			&nbsp;
		
			<p>
			※ 数量为 0 保存时，自动清除 “不良内容” 和 “位号” 的内容。
			</p>
		
		</div>	
	</Modal>
	
	<br>
	
	
	<Divider orientation="left">品质管理图表</Divider>
	
	<br>

	<br><br>
	<i-row :gutter="16">
		<i-col span="24">
			<div id="chart1" style="height:400px"></div>
		</i-col>
	</i-row>

	<br><br>
	<i-row :gutter="16">
		<i-col span="24">
			<div id="chart2" style="height:400px"></div>
		</i-col>
	</i-row>

</div>
@endsection

@section('my_js_others')
@parent	
<script>
// var current_date = new Date();
var current_date = new Date("January 12,2006 22:19:35");
var vm_app = new Vue({
	el: '#app',
	data: {
		//分页
		pagecurrent: 1,
		pagetotal: 1,
		pagepagesize: 10,
		pagelast: 1,
		
		// 批量录入
		piliangluru: [
			{
				jizhongming: '',
				pinfan: '',
				pinming: '',
				xuqiushuliang: 0
			},
		],

		// 批量录入项
		piliangluruxiang: 1,

		// 线体
		xianti: '',
		
		// 区分
		qufen: '',
		
		// 日期范围过滤
		qcdate_filter: [], //new Date(),
		
		// 线体过滤
		xianti_filter: '',

		// 区分过滤
		qufen_filter: '',

		// 机种名
		jizhongming_filter: '',

		// 品番过滤
		pinfan_filter: '',

		// 品名过滤
		pinming_filter: '',

		// 编辑
		modal_main_edit: false,
		// id_edit: '',
		xianti_edit: '',
		qufen_edit: '',
		created_at_edit: '',
		updated_at_edit: '',
		jizhongming_edit: '',
		pinfan_edit: '',
		pinming_edit: '',
		xuqiushuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值

		
		// 表头1
		tablecolumns1: [
			{
				type: 'selection',
				width: 50,
				align: 'center',
				fixed: 'left'
			},
			{
				type: 'index',
				width: 60,
				align: 'center'
			},

			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80,
			},
			{
				title: '区分',
				key: 'qufen',
				align: 'center',
				width: 80,
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
				// sortable: true
			},
			{
				title: '品番',
				key: 'pinfan',
				align: 'center',
				width: 100,
				// sortable: true
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 100
			},
			{
				title: '需求数量',
				key: 'xuqiushuliang',
				align: 'center',
				width: 100,
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.xuqiushuliang.toLocaleString()
					]);
				}
			},
			{
				title: '当前月日期',
				align: 'center',
				renderHeader: (h, index) => {
					return h('div', {
						domProps: {
							title: '当前月份日期',
							// innerHTML: new Date().Format("yyyy年MM月")
							innerHTML: current_date.Format("yyyy年MM月")
						}
					});
				},
				children: [
					{
						title: '1',
						key: 'd1',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d1.toLocaleString()
							]);
						}
					},
					{
						title: '2',
						key: 'd2',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d2.toLocaleString()
							]);
						}
					},
					{
						title: '3',
						key: 'd3',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d3.toLocaleString()
							]);
						}
					},
					{
						title: '4',
						key: 'd4',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d4.toLocaleString()
							]);
						}
					},
					{
						title: '5',
						key: 'd5',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d5.toLocaleString()
							]);
						}
					},
					{
						title: '6',
						key: 'd6',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d6.toLocaleString()
							]);
						}
					},
					{
						title: '7',
						key: 'd7',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d7.toLocaleString()
							]);
						}
					},
					{
						title: '8',
						key: 'd8',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d8.toLocaleString()
							]);
						}
					},
					{
						title: '9',
						key: 'd9',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d9.toLocaleString()
							]);
						}
					},
					{
						title: '10',
						key: 'd10',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d10.toLocaleString()
							]);
						}
					},
					{
						title: '11',
						key: 'd11',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d11.toLocaleString()
							]);
						}
					},
					{
						title: '12',
						key: 'd12',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d12.toLocaleString()
							]);
						}
					},
					{
						title: '13',
						key: 'd13',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d13.toLocaleString()
							]);
						}
					},
					{
						title: '14',
						key: 'd14',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d14.toLocaleString()
							]);
						}
					},
					{
						title: '15',
						key: 'd15',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d15.toLocaleString()
							]);
						}
					},
					{
						title: '16',
						key: 'd16',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d16.toLocaleString()
							]);
						}
					},
					{
						title: '17',
						key: 'd17',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d17.toLocaleString()
							]);
						}
					},
					{
						title: '18',
						key: 'd18',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d18.toLocaleString()
							]);
						}
					},
					{
						title: '19',
						key: 'd19',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d19.toLocaleString()
							]);
						}
					},
					{
						title: '20',
						key: 'd20',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d20.toLocaleString()
							]);
						}
					},
					{
						title: '21',
						key: 'd21',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d21.toLocaleString()
							]);
						}
					},
					{
						title: '22',
						key: 'd22',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d22.toLocaleString()
							]);
						}
					},
					{
						title: '23',
						key: 'd23',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d23.toLocaleString()
							]);
						}
					},
					{
						title: '24',
						key: 'd24',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d24.toLocaleString()
							]);
						}
					},
					{
						title: '25',
						key: 'd25',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d25.toLocaleString()
							]);
						}
					},
					{
						title: '26',
						key: 'd26',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d26.toLocaleString()
							]);
						}
					},
					{
						title: '27',
						key: 'd27',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d27.toLocaleString()
							]);
						}
					},
					{
						title: '28',
						key: 'd28',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d28.toLocaleString()
							]);
						}
					},
					{
						title: '29',
						key: 'd29',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d29.toLocaleString()
							]);
						}
					},
					{
						title: '30',
						key: 'd30',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d30.toLocaleString()
							]);
						}
					},
					{
						title: '31',
						key: 'd31',
						align: 'center',
						width: 60,
						render: (h, params) => {
							return h('div', [
								params.row.d31.toLocaleString()
							]);
						}
					},
				]
			},			
			{
				title: '创建日期',
				key: 'created_at',
				align: 'center',
				width: 160,
			},
			{
				title: '更新日期',
				key: 'updated_at',
				align: 'center',
				width: 160,
			},
			{
				title: '操作',
				key: 'action',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'info',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.main_edit(params.row)
								}
							}
						}, 'Edit')
					]);
				},
				fixed: 'right'
			},			
		],
		tabledata1: [],
		tableselect1: [],

		
		
		
		
		
		
		
		
		
		
		
		
		
		//工序
		jizhongming: '',
		option_jizhongming: [
			{value: 'A', label: 'A'},
			{value: 'B', label: 'B'}
		],
		
		// 点/枚
		dianmei: '',
		
		// 枚数
		meishu: '',
		
		// 检查机类型
		select_jianchajileixing: '',
		option_jianchajileixing: [
			{
				value: 'AOI-1',
				label: 'AOI-1'
			},
			{
				value: 'AOI-2',
				label: 'AOI-2'
			},
			{
				value: 'AOI-3',
				label: 'AOI-3'
			},
			{
				value: 'AOI-4',
				label: 'AOI-4'
			},
			{
				value: 'VQZ',
				label: 'VQZ'
			},
			{
				value: 'MD',
				label: 'MD'
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
		
		// 班次
		select_banci: '',
		option_qufen: [
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
		
		// 不良内容
		// select_buliangneirong: '',
		option_buliangneirong1: [
			{value: '连焊', label: '连焊'}, {value: '引脚焊锡量少/无', label: '引脚焊锡量少/无'},
			{value: 'chip部品焊锡少/无', label: 'chip部品焊锡少/无'}, {value: '焊锡球', label: '焊锡球'}
		],
		option_buliangneirong2: [
			{value: '部品浮起竖立', label: '部品浮起竖立'}, {value: 'chip部品横立', label: 'chip部品横立'},
			{value: '欠品', label: '欠品'}, {value: '焊锡未熔解', label: '焊锡未熔解'},
		],
		option_buliangneirong3: [
			{value: '部品错误', label: '部品错误'},
		],
		option_buliangneirong4: [
			{value: '部品多余', label: '部品多余'},
		],
		option_buliangneirong5: [
			{value: '异物', label: '异物'},
		],
		option_buliangneirong6: [
			{value: '极性错误', label: '极性错误'},
		],
		option_buliangneirong7: [
			{value: '炉后部品破损', label: '炉后部品破损'}, {value: '引脚弯曲', label: '引脚弯曲'},
			{value: '基板/部品变形后引脚浮起', label: '基板/部品变形后引脚浮起'},
		],
		option_buliangneirong8: [
			{value: '引脚不上锡', label: '引脚不上锡'},
			{value: '基板不上锡', label: '基板不上锡'}, {value: 'chip部品不上锡', label: 'chip部品不上锡'},
			{value: '部品不良', label: '部品不良'},
		],
		option_buliangneirong9: [
			{value: '其他', label: '其他'},
		],

		
		// 位号
		// weihao: '',
		
		// 数量
		// shuliang: '',
		
		// 检查者
		// select_jianchazhe: '',
		option_jianchazhe1: [
			{value: '许瑞萍', label: '许瑞萍'},
			{value: '李世英', label: '李世英'},
			{value: '张向果', label: '张向果'},
			{value: '第小霞', label: '第小霞'},
			{value: '蔡素英', label: '蔡素英'},
			{value: '孙吻茹', label: '孙吻茹'},
			{value: '葛敏', label: '葛敏'},
			{value: '陈小枝', label: '陈小枝'},
			{value: '李阳', label: '李阳'},
		],
		option_jianchazhe2: [
			{value: '贾东梅', label: '贾东梅'},
			{value: '蔡小红', label: '蔡小红'},
			{value: '黄俊英', label: '黄俊英'},
			{value: '黎小娟', label: '黎小娟'},
			{value: '张艳敏', label: '张艳敏'},
			{value: '杨晓娟', label: '杨晓娟'},
			{value: '朱风婷', label: '朱风婷'},
		],
		option_jianchazhe3: [
			{value: '王凤娇', label: '王凤娇'},
			{value: '肖厚春', label: '肖厚春'},
			{value: '朱建珊', label: '朱建珊'},
			{value: '李燕', label: '李燕'},
			{value: '张艳红', label: '张艳红'},
			{value: '贺转云', label: '贺转云'},
			{value: '曾加英', label: '曾加英'},
		],
		


		
		
		// 删除disabled
		boo_delete: true,
		
		// dailyproductionreport 暂未用到
		saomiao_data: '',
		
		// 不良件数小计
		buliangjianshuxiaoji: 0,
		
		
		// echarts ajax使用 这个才是实际使用的
		chart1_type: 'bar',
		
		chart1_option_tooltip_show: true,
		
		chart1_option_legend_data: ['不适合件数合计', '合计点数', 'PPM'],
		
		chart1_option_xAxis_data: ['SMT-1','SMT-2','SMT-3','SMT-4','SMT-5','SMT-6','SMT-7','SMT-8','SMT-9','SMT-10'],
		
		chart1_option_series: [
			// {
				// name: '销量1',
				// type: 'line',
				// data: [5, 20, 40, 10, 10, 20],
				// markLine: {
					// data: [
						// {type: 'average', name: '平均值'}
					// ]
				// }
			// },
			// {
				// 'name': '销量2',
				// 'type': 'line',
				// 'data': [15, 120, 140,110, 110, 123]
			// }
		],
		
		// chart2参数
		chart2_option_title_text: 'LINE别不良占有率',
		chart2_option_legend_data: ['SMT-1','SMT-2','SMT-3','SMT-4','SMT-5','SMT-6','SMT-7','SMT-8','SMT-9','SMT-10'],
		
		chart2_option_series_data: [
			{value:335, name:'SMT-1'},
			{value:310, name:'SMT-2'},
			{value:335, name:'SMT-3'},
			{value:310, name:'SMT-4'},
			{value:234, name:'SMT-5'},
			{value:135, name:'SMT-6'},
			{value:154, name:'SMT-7'},
			{value:335, name:'SMT-8'},
			{value:310, name:'SMT-9'},
			{value:234, name:'SMT-10'}
		],
		
		file: null,
		loadingStatus: false
		
		
		
			
			
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
		
		autocomplete_search (value) {
			this.autocomplete_data = !value ? [] : [
				value,
				value + value,
				value + value + value
			];
		},
		
		// qcreport列表
		maingets: function(){
			var _this = this;
			var qcdate_filter = [];

			for (var i in _this.qcdate_filter) {
				if (typeof(_this.qcdate_filter[i])!='string') {
					qcdate_filter.push(_this.qcdate_filter[i].Format("yyyy-MM-dd"));
				} else if (_this.qcdate_filter[i] == '') {
					qcdate_filter.push(new Date().Format("yyyy-MM-dd"));
				} else {
					qcdate_filter.push(_this.qcdate_filter[i]);
				}
			}
			
			var xianti_filter = _this.xianti_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			var url = "{{ route('bpjg.zrcfx.maingets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data) {
					_this.tabledata1 = response.data.data;
				} else {
					_this.tabledata1 = [];
				}
				
				// 合计
				_this.buliangjianshuxiaoji = 0;
				for (var i in _this.tabledata1) {
					_this.buliangjianshuxiaoji += _this.tabledata1[i].shuliang;
				}
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		
		
		// onclear
		onclear: function () {
			var _this = this;
			_this.xianti = '';
			_this.qufen = '';
			_this.piliangluru.map(function (v,i) {
				v.jizhongming = '';
				v.pinfan = '';
				v.pinming = '';
				v.xuqiushuliang = 0;
			});
			
			_this.$refs.xianti.focus();
		},
		
		// oncreate
		oncreate: function () {
			var _this = this;
			var xianti = _this.xianti;
			var qufen = _this.qufen;
			
			if (xianti == '' || xianti == undefined || qufen == '' || qufen == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}
			
			_this.piliangluru.map(function (v,i) {
				// jizhongming: '',
				// pinfan: '',
				// pinming: '',
				// xuqiushuliang: 0
				
				if (v.jizhongming == '' || v.pinfan == '' || v.pinming == ''  || v.xuqiushuliang == ''
					|| v.jizhongming == undefined || v.pinfan == undefined || v.pinming == undefined || v.xuqiushuliang == undefined) {
					_this.warning(false, '警告', '输入内容为空或不正确！');
					return false;
				}
			});
			var piliangluru = _this.piliangluru;
			
			var url = "{{ route('bpjg.zrcfx.maincreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				xianti: xianti,
				qufen: qufen,
				piliangluru: piliangluru
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data) {
					_this.onclear();
					_this.success(false, '成功', '记入成功！');
					_this.boo_delete = true;
					_this.tableselect1 = [];
					_this.maingets();

				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},
		
		
		// 编辑后保存
		qcreport_edit_ok: function () {
			var _this = this;
			
			var id = _this.id_edit;
			var jizhongming = _this.jizhongming_edit;
			var created_at = _this.created_at_edit;
			var updated_at = _this.updated_at_edit;
			var jianchajileixing = _this.jianchajileixing_edit;
			var buliangneirong = _this.buliangneirong_edit;
			var weihao = _this.weihao_edit;
			var shuliang = _this.shuliang_edit;
			var jianchazhe = _this.jianchazhe_edit;
			var dianmei = _this.dianmei_edit;
			var meishu = _this.meishu_edit;
			var hejidianshu = _this.hejidianshu_edit;
			var bushihejianshuheji = _this.bushihejianshuheji_edit;
			var ppm = _this.ppm_edit;

			// 重新计算枚数、合计点数、不良件数合计和PPM
			hejidianshu = dianmei * meishu;
			bushihejianshuheji = bushihejianshuheji + shuliang[1] - shuliang[0];
			ppm = bushihejianshuheji / hejidianshu * 1000000;
			
			// console.log(buliangneirong);
			// return false;
			
			// 数量为0时，清空不良内容、位号和数量
			if (shuliang[1] == 0) {
				buliangneirong = '';
				weihao = '';
				shuliang[1] = '';
			} else if (buliangneirong == '' || buliangneirong == null || buliangneirong == undefined
				|| weihao == '' || weihao == null || weihao == undefined) {
				_this.warning(false, '警告', '[不良内容] 或 [位号] 不能为空！');
				return false;
			}
			
			var url = "{{ route('bpjg.zrcfx.mainupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				jizhongming: jizhongming,
				created_at: created_at,
				updated_at: updated_at,
				jianchajileixing: jianchajileixing,
				buliangneirong: buliangneirong,
				weihao: weihao,
				shuliang: shuliang[1],
				jianchazhe: jianchazhe,
				meishu: meishu,
				hejidianshu: hejidianshu,
				bushihejianshuheji: bushihejianshuheji,
				ppm: ppm
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				_this.maingets(_this.pagecurrent, _this.pagelast);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.id_edit = '';
					_this.jizhongming_edit = '';
					_this.created_at_edit = '';
					_this.updated_at_edit = '';
					_this.jianchajileixing_edit = '';
					_this.buliangneirong_edit = '';
					_this.weihao_edit = '';
					_this.shuliang_edit = [0, 0];
					_this.jianchazhe_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},
		
		
		// ondelete
		ondelete: function () {
			var _this = this;
			
			var tableselect1 = _this.tableselect1;
			
			if (tableselect1[0] == undefined) return false;

			var url = "{{ route('bpjg.zrcfx.maindelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect1: tableselect1
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.tableselect1 = [];
					_this.maingets();
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// 表1选择
		onselectchange1: function (selection) {
			var _this = this;
			_this.tableselect1 = [];

			for (var i in selection) {
				_this.tableselect1.push(selection[i].id);
			}
			
			_this.boo_delete = _this.tableselect1[0] == undefined ? true : false;
			
		},
		

		// exportData_db 当前表数据导出
		exportData_db: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.qcdate_filter[0].Format("yyyy-MM-dd");
			var queryfilter_dateto = _this.qcdate_filter[1].Format("yyyy-MM-dd");
			
			var url = "{{ route('bpjg.zrcfx.mainexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			// console.log(url);
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);

		},
		
		//
		// 生成piliangluru
		piliangluru_generate: function (counts) {
			var len = this.piliangluru.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru.push({value: 'piliangluru'+parseInt(len+i+1)});
					this.piliangluru.push(
						{
							jizhongming: '',
							pinfan: '',
							pinming: '',
							xuqiushuliang: 0
						}
					);
				}
			} else if (counts < len) {
				if (this.piliangluruxiang != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang == this.piliangluru[i].value) {
							this.piliangluruxiang = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru.pop();
				}
			}			

		},
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

		// echarts public function 显示用的公共函数
		chart1_function: function () {
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
					'echarts/chart/bar', // 使用柱状图就加载bar模块，按需加载
					'echarts/chart/line'
					// 'echarts/chart/' + vm_app.chart1_type
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart1')); 
					
					var option = {
						title : {
							text: '工程内不良记录（PPM）',
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd') //'2018.06-2018-07'
						},
						tooltip: {
							show: vm_app.chart1_option_tooltip_show,
							trigger: 'axis'
						},
						legend: {
							data: vm_app.chart1_option_legend_data
						},
						xAxis : [
							{
								type : 'category',
								data : vm_app.chart1_option_xAxis_data
							}
						],
						yAxis : [
							{
								type : 'value',
								name : '件数',
								axisLabel : {
									formatter: '{value} 件'
								}
							},
							{
								type : 'value',
								name : 'PPM',
								axisLabel : {
									formatter: '{value} ppm'
								}
							}
						],
						calculable : true,
						toolbox: {
							show: true,
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						series : vm_app.chart1_option_series
					};
			
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},

		chart2_function: function () {
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
					'echarts/chart/pie', // 使用柱状图就加载bar模块，按需加载
					// 'echarts/chart/' + vm_app.chart1_type
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart2')); 
					
					var option = {
						title: {
							text: vm_app.chart2_option_title_text,
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd'),
							x:'center'
						},
						tooltip: {
							trigger: 'item',
							formatter: "{a} <br/>{b} : {c} ({d}%)"
						},
						legend: {
							orient : 'vertical',
							x: 'left',
							data: vm_app.chart2_option_legend_data
						},
						toolbox: {
							show: true,
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								// magicType : {
									// show: true, 
									// type: ['pie', 'funnel'],
									// option: {
										// funnel: {
										// x: '25%',
										// width: '50%',
										// funnelAlign: 'left',
										// max: 1548
										// }
									// }
								// },
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						calculable: true,
						
						// series : vm_app.chart2_option_series
						series : [
							{
								name: vm_app.chart2_option_title_text,
								type: 'pie',
								radius: '55%',
								center: ['50%', '60%'],
								selectedMode: 'multiple',
								itemStyle: {
									normal: {
										label: {
											// position : 'inner',
											formatter: function (params) {
												// console.log(params);
												return params.name + ' : ' + params.value + ' (' + (params.percent - 0).toFixed(0) + '%)'
											}
										},
										labelLine: {
											show : true
										}
									},
									emphasis: {
										label: {
											show: true,
											formatter: "{b}\n{d}%"
										}
									}
								},
								
								data: vm_app.chart2_option_series_data,
								// [
									// {value:335, name:'SMT-1'},
									// {value:310, name:'SMT-2'},
									// {value:335, name:'SMT-3'},
									// {value:310, name:'SMT-4'},
									// {value:234, name:'SMT-5'},
									// {value:135, name:'SMT-6'},
									// {value:154, name:'SMT-7'},
									// {value:335, name:'SMT-8'},
									// {value:310, name:'SMT-9'},
									// {value:234, name:'SMT-10'},
								// ]
							}
						]						
					};
			
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},
		
		
		
		// ajax返回后显示图表
		onchart1: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			var hejidianshu = [];
			var bushihejianshuheji = [];
			var ppm = [];
			
			var i = 0;
			for (i=0;i<10;i++) {
				hejidianshu[i] = 0;
				bushihejianshuheji[i] = 0;
				ppm[i] = 0;
			}
			
			_this.tabledata1.map(function (v,j) {
				switch(v.xianti)
				{
					case 'SMT-1':
						i = 0;
						break;
					case 'SMT-2':
						i = 1;
						break;
					case 'SMT-3':
						i = 2;
						break;
					case 'SMT-4':
						i = 3;
						break;
					case 'SMT-5':
						i = 4;
						break;
					case 'SMT-6':
						i = 5;
						break;
					case 'SMT-7':
						i = 6;
						break;
					case 'SMT-8':
						i = 7;
						break;
					case 'SMT-9':
						i = 8;
						break;
					case 'SMT-10':
						i = 9;
						break;
					default:
					  
				}
			
				hejidianshu[i] += v.hejidianshu;
				bushihejianshuheji[i] += v.bushihejianshuheji;

				// if (hejidianshu[i] == 0) {
					// ppm[i] = 0;
				// } else {
					// ppm[i] = bushihejianshuheji[i] / hejidianshu[i] * 1000000;
				// }
				ppm[i] += v.ppm;

			});
			
			// console.log(bushihejianshuheji);
			// console.log(hejidianshu);
			// console.log(ppm);
			// return false;
			
			// bushihejianshuheji
			var a1 = [{
				name: '不适合件数合计',
				type: 'bar',
				barWidth: 30,
				itemStyle: {
					normal: {
						label: {
							show: true,
							position: 'top'
						}
					}
				},
				data: bushihejianshuheji
			},
			{
				name: '合计点数',
				type: 'bar',
				barWidth: 30,
				itemStyle: {
					normal: {
						label: {
							show: true,
							position: 'top'
						}
					}
				},
				data: hejidianshu
			},
			{
				name: 'PPM',
				type: 'line',
				yAxisIndex: 1,
				itemStyle: {
					normal: {
						label: {
							show: true,
							// 'position' => 'outer'
							textStyle: {
								fontSize: '20',
								fontFamily: '微软雅黑',
								fontWeight: 'bold'
							}
						}
					}
				},
				data: ppm
			}];

			_this.chart1_option_series = a1;
			_this.chart1_function();
			
		},
		
		
		onchart2: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			var bushihejianshuheji = [];
			for (var i=0;i<10;i++) {
				bushihejianshuheji[i] = 0;
			}
			
			_this.tabledata1.map(function (v,j) {
				switch(v.xianti)
				{
					case 'SMT-1':
						i = 0;
						break;
					case 'SMT-2':
						i = 1;
						break;
					case 'SMT-3':
						i = 2;
						break;
					case 'SMT-4':
						i = 3;
						break;
					case 'SMT-5':
						i = 4;
						break;
					case 'SMT-6':
						i = 5;
						break;
					case 'SMT-7':
						i = 6;
						break;
					case 'SMT-8':
						i = 7;
						break;
					case 'SMT-9':
						i = 8;
						break;
					case 'SMT-10':
						i = 9;
						break;
					default:
					  
				}
			
				bushihejianshuheji[i] += v.bushihejianshuheji;
			});
			
			var data = 
			[
				{value: bushihejianshuheji[0], name:'SMT-1'},
				{value: bushihejianshuheji[1], name:'SMT-2'},
				{value: bushihejianshuheji[2], name:'SMT-3'},
				{value: bushihejianshuheji[3], name:'SMT-4'},
				{value: bushihejianshuheji[4], name:'SMT-5'},
				{value: bushihejianshuheji[5], name:'SMT-6'},
				{value: bushihejianshuheji[6], name:'SMT-7'},
				{value: bushihejianshuheji[7], name:'SMT-8'},
				{value: bushihejianshuheji[8], name:'SMT-9'},
				{value: bushihejianshuheji[9], name:'SMT-10'},
			];
			
			_this.chart2_option_series_data = data;
			_this.chart2_function();

		},
		
		
		//
		onimport: function () {
			alert('aa');
			
		},
		
		

		
		
		
		
		
			
			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
		// _this.maingets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection