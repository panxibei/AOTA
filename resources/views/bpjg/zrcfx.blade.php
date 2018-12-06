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

	<Divider orientation="left">1. 机芯/完成品中日程 信息录入</Divider>
	
	<i-row :gutter="16">
		<i-col span="24">
			↓ 批量录入&nbsp;&nbsp;
			<Input-number v-model.lazy="piliangluruxiang_zrc" @on-change="value=>piliangluru_zrc_generate(value)" :min="1" :max="10" size="small" style="width: 60px"></Input-number>
			&nbsp;项
		</i-col>
	</i-row>

	<span v-for="(item, index) in piliangluru_zrc">
	<br>
	<i-row :gutter="16">
		<i-col span="1">
			&nbsp;No.@{{index+1}}
		</i-col>
		<i-col span="5">
			* 日期&nbsp;&nbsp;
			<Date-picker v-model.lazy="item.riqi" type="date" size="small" placement="top" style="width:160px"></Date-picker>
		</i-col>
		<i-col span="5">
			* 机种名&nbsp;&nbsp;
			<i-input v-model.lazy="item.jizhongming" @on-keyup="item.jizhongming=item.jizhongming.toUpperCase()" size="small" placeholder="例：QH00048" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="3">
			* 数量&nbsp;&nbsp;
			<Input-number v-model.lazy="item.shuliang" :min="1" size="small" style="width: 80px"></Input-number>
		</i-col>
		<i-col span="10">
		&nbsp;
		</i-col>
		
	</i-row>
	<br>
	</span>

	<br>

	<i-row :gutter="16">
		<i-col span="24">
			&nbsp;&nbsp;<i-button @click="oncreate_zrc()" type="primary">记入</i-button>
			&nbsp;&nbsp;<i-button @click="onclear_zrc()">清除</i-button>
			&nbsp;&nbsp;&nbsp;&nbsp;<i-button @click="onimport_zrc()">批量导入</i-button>
		</i-col>
	</i-row>

	<br><br><br>	

	<Divider orientation="left">2. 机芯/完成品中日程 信息查询</Divider>

	<i-row :gutter="16">
		<i-col span="2">
			&nbsp;
		</i-col>
		<i-col span="1">
			查询：
		</i-col>
		<i-col span="6">
			* 日期范围&nbsp;&nbsp;
			<Date-picker v-model.lazy="qcdate_filter_zrc" @on-change="zrcgets(pagecurrent2, pagelast2);onselectchange2();" type="daterange" size="small" placement="top" style="width:200px"></Date-picker>
		</i-col>
		<i-col span="3">
			机种名&nbsp;&nbsp;
			<i-input v-model.lazy="jizhongming_filter_zrc" @on-change="zrcgets(pagecurrent2, pagelast2)" @on-keyup="jizhongming_filter_zrc=jizhongming_filter_zrc.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
		</i-col>
		<i-col span="9">
		&nbsp;
		</i-col>
	</i-row>
	<br><br>

	<i-row :gutter="16">
		<br>
		<i-col span="2">
			<i-button @click="ondelete_zrc()" :disabled="boo_delete_zrc" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
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
			<br><Page :current="pagecurrent1" :total="pagetotal1" :page-size="pagepagesize1" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>
		</i-col>
	</i-row>
	
	<Modal v-model="modal_zrc_edit" @on-ok="zrc_edit_ok" ok-text="保存" title="编辑 - 机芯/完成品中日程" width="540">
		<div style="text-align:left">
			<p>
				创建时间：@{{ zrc_created_at_edit }}
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				更新时间：@{{ zrc_updated_at_edit }}
			</p>
			<br>
			
			<p>
				日期&nbsp;&nbsp;
				<Date-picker v-model.lazy="zrc_riqi_edit" type="date" size="small" placement="top" style="width:160px"></Date-picker>
				&nbsp;&nbsp;&nbsp;&nbsp;

				机种名&nbsp;&nbsp;
				<i-input v-model.lazy="zrc_jizhongming_edit" @on-keyup="zrc_jizhongming_edit=zrc_jizhongming_edit.toUpperCase()" placeholder="" size="small" clearable style="width: 120px"></i-input>
				&nbsp;&nbsp;&nbsp;&nbsp;

				数量&nbsp;&nbsp;
				<Input-number v-model.lazy="zrc_shuliang_edit[1]" :min="1" size="small" style="width: 80px"></Input-number>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</p>

			&nbsp;
		
		</div>	
	</Modal>
	
	<br>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	<Divider orientation="left">3. 线体/机种/部品 信息录入</Divider>

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
			<Input-number v-model.lazy="piliangluruxiang_main" @on-change="value=>piliangluru_main_generate(value)" :min="1" :max="10" size="small" style="width: 60px"></Input-number>
			&nbsp;项
		</i-col>
	</i-row>
	

	<span v-for="(item, index) in piliangluru_main">
	<br>
	<i-row :gutter="16">
		<i-col span="1">
			&nbsp;No.@{{index+1}}
		</i-col>
		<i-col span="4">
			* 机种名&nbsp;&nbsp;
			<i-input v-model.lazy="item.jizhongming" @on-keyup="item.jizhongming=item.jizhongming.toUpperCase()" size="small" placeholder="例：QH00048" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="4">
			* 品番&nbsp;&nbsp;
			<i-input v-model.lazy="item.pinfan" @on-keyup="item.pinfan=item.pinfan.toUpperCase()" size="small" placeholder="例：07-37361Z02-A" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="4">
			* 品名&nbsp;&nbsp;
			<i-input v-model.lazy="item.pinming" @on-keyup="item.pinming=item.pinming.toUpperCase()" size="small" placeholder="例：SPRING,GND 7L" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="4">
			* 需求数量&nbsp;&nbsp;
			<Input-number v-model.lazy="item.xuqiushuliang" :min="0" size="small" style="width: 80px"></Input-number>
		</i-col>
		<i-col span="3">
			* 类别&nbsp;&nbsp;
			<i-select v-model.lazy="item.leibie" clearable size="small" style="width:80px" placeholder="">
				<i-option v-for="item in option_leibie" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="4">
		&nbsp;
		</i-col>
		
	</i-row>
	<br>
	</span>

	<br>

	<i-row :gutter="16">
		<i-col span="24">
			&nbsp;&nbsp;<i-button @click="oncreate_main()" type="primary">记入</i-button>
			&nbsp;&nbsp;<i-button @click="onclear_main()">清除</i-button>
			&nbsp;&nbsp;&nbsp;&nbsp;<i-button @click="onimport_main()">批量导入</i-button>
		</i-col>
	</i-row>

	<br><br><br>
	
	
	<Divider orientation="left">4. 线体/机种/部品 信息查询</Divider>

	<i-row :gutter="16">
		<i-col span="2">
			&nbsp;
		</i-col>
		<i-col span="1">
			查询：
		</i-col>
		<i-col span="6">
			* 日期范围&nbsp;&nbsp;
			<Date-picker v-model.lazy="qcdate_filter_main" @on-change="maingets(pagecurrent2, pagelast2);onselectchange2();" type="daterange" size="small" placement="top" style="width:200px"></Date-picker>
		</i-col>
		<i-col span="3">
			线体&nbsp;&nbsp;
			<i-input v-model.lazy="xianti_filter" @on-change="maingets(pagecurrent2, pagelast2)" @on-keyup="xianti_filter=xianti_filter.toUpperCase()" placeholder="" size="small" clearable style="width: 120px"></i-input>
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
		<i-col span="3">
			机种名&nbsp;&nbsp;
			<i-input v-model.lazy="jizhongming_filter_main" @on-change="maingets(pagecurrent2, pagelast2)" @on-keyup="jizhongming_filter_main=jizhongming_filter_main.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
		</i-col>
		<i-col span="3">
			品番&nbsp;&nbsp;
			<i-input v-model.lazy="pinfan_filter" @on-change="maingets(pagecurrent2, pagelast2)" @on-keyup="pinfan_filter=pinfan_filter.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
		</i-col>
		<i-col span="3">
			品名&nbsp;&nbsp;
			<i-input v-model.lazy="pinming_filter" @on-change="maingets(pagecurrent2, pagelast2)" @on-keyup="pinming_filter=pinming_filter.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
		</i-col>
		<i-col span="3">
			类别&nbsp;&nbsp;
			<i-select v-model.lazy="leibie_filter" @on-change="maingets(pagecurrent2, pagelast2);onselectchange2();" clearable size="small" style="width:100px" placeholder="">
				<i-option v-for="item in option_leibie" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
	</i-row>
	<br><br>

	<i-row :gutter="16">
		<br>
		<i-col span="2">
			<i-button @click="ondelete_main()" :disabled="boo_delete_main" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
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
			<i-table ref="table2" height="400" size="small" border :columns="tablecolumns2" :data="tabledata2" @on-selection-change="selection => onselectchange2(selection)"></i-table>
			<br><Page :current="pagecurrent2" :total="pagetotal2" :page-size="pagepagesize2" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>
		</i-col>
	</i-row>
	
	<Modal v-model="modal_main_edit" @on-ok="main_edit_ok" ok-text="保存" title="工程内不良记录编辑" width="540">
		<div style="text-align:left">
			<p>
				线体：@{{ main_xianti_edit }}
			
				&nbsp;&nbsp;

				区分：@{{ main_qufen_edit }}
			
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				创建时间：@{{ main_created_at_edit }}
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				更新时间：@{{ main_updated_at_edit }}
			
			</p>
			<br>
			
			<!--<span v-for="(item, index) in piliangbianji">-->
			<p>
				机种名&nbsp;&nbsp;
				<i-input v-model.lazy="main_jizhongming_edit" @on-keyup="main_jizhongming_edit=main_jizhongming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;

				品番&nbsp;&nbsp;
				<i-input v-model.lazy="main_pinfan_edit" @on-keyup="main_pinfan_edit=main_pinfan_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;

				品名&nbsp;&nbsp;
				<i-input v-model.lazy="main_pinming_edit" @on-keyup="main_pinming_edit=main_pinming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

				&nbsp;&nbsp;&nbsp;&nbsp;
			
				需求数量&nbsp;&nbsp;
				<Input-number v-model.lazy="main_xuqiushuliang_edit[1]" :min="0" size="small" style="width: 80px"></Input-number>

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
		//表1分页
		pagecurrent1: 1,
		pagetotal1: 1,
		pagepagesize1: 10,
		pagelast1: 1,

		//表2分页
		pagecurrent2: 1,
		pagetotal2: 1,
		pagepagesize2: 10,
		pagelast2: 1,
		
		// ##########基本变量########
		// 批量录入中日程
		piliangluru_zrc: [
			{
				riqi: '',
				jizhongming: '',
				shuliang: 1
			},
		],

		// 批量录入项
		piliangluruxiang_zrc: 1,

		// 批量录入主表
		piliangluru_main: [
			{
				jizhongming: '',
				pinfan: '',
				pinming: '',
				xuqiushuliang: 1,
				leibie: ''
			},
		],

		// 批量录入项
		piliangluruxiang_main: 1,

		// 线体
		xianti: '',
		
		// 区分
		qufen: '',
		
		//类别
		option_leibie: [
			{value: '冲压', label: '冲压'},
			{value: '成型', label: '成型'}
		],
		
		
		// ##########查询过滤########
		// 日期范围过滤
		qcdate_filter_zrc: [], //new Date(),
		qcdate_filter_main: [], //new Date(),
		
		// 线体过滤
		xianti_filter: '',

		// 机种名
		jizhongming_filter_zrc: '',
		jizhongming_filter_main: '',

		// 品番过滤
		pinfan_filter: '',

		// 品名过滤
		pinming_filter: '',
		
		// 类别过滤
		leibie_filter: '',

		

		// ##########编辑变量########
		modal_zrc_edit: false,
		zrc_id_edit: '',
		zrc_riqi_edit: '',
		zrc_jizhongming_edit: '',
		zrc_shuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值
		zrc_created_at_edit: '',
		zrc_updated_at_edit: '',
		
		modal_main_edit: false,
		main_id_edit: '',
		main_xianti_edit: '',
		main_qufen_edit: '',
		main_created_at_edit: '',
		main_updated_at_edit: '',
		main_jizhongming_edit: '',
		main_pinfan_edit: '',
		main_pinming_edit: '',
		main_xuqiushuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值
		main_leibie: '',

		
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
				title: '日期',
				key: 'riqi',
				align: 'center',
				width: 160,
				render: (h, params) => {
					return h('div', [
						params.row.riqi.substring(0, 10)
					]);
				}
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
				// sortable: true
			},
			{
				title: '数量',
				key: 'shuliang',
				align: 'center',
				width: 100,
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.shuliang.toLocaleString()
					]);
				}
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
									vm_app.zrc_edit(params.row)
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
		
		// 表头2
		tablecolumns2: [
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
				title: '日期',
				key: 'riqi',
				align: 'center',
				width: 160,
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
				title: '类别',
				key: 'leibie',
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
				title: '总数',
				key: 'zongshu',
				align: 'center',
				width: 100,
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.zongshu.toLocaleString()
					]);
				}
			},
			{
				title: '数量',
				key: 'shuliang',
				align: 'center',
				width: 100,
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.shuliang.toLocaleString()
					]);
				}
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
		tabledata2: [],
		tableselect2: [],
		


		// 删除disabled
		boo_delete_zrc: true,

		// 删除disabled
		boo_delete_main: true,
		
		
		
		
		
		
		
		
		
		
		
		
		
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
		
		// zrc列表
		zrcgets: function (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var qcdate_filter_zrc = [];

			for (var i in _this.qcdate_filter_zrc) {
				if (typeof(_this.qcdate_filter_zrc[i])!='string') {
					qcdate_filter_zrc.push(_this.qcdate_filter_zrc[i].Format("yyyy-MM-dd"));
				} else if (_this.qcdate_filter_zrc[i] == '') {
					// qcdate_filter_zrc.push(new Date().Format("yyyy-MM-dd"));
				} else {
					qcdate_filter_zrc.push(_this.qcdate_filter_zrc[i]);
				}
			}
			
			var jizhongming_filter_zrc = _this.jizhongming_filter_zrc;

			var url = "{{ route('bpjg.zrcfx.zrcgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize2,
					page: page,
					qcdate_filter: qcdate_filter_zrc,
					jizhongming_filter: jizhongming_filter_zrc
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data) {
					_this.tabledata1 = response.data.data;
				} else {
					_this.tabledata1 = [];
				}
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
	
		// main列表
		maingets: function (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var qcdate_filter_main = [];

			for (var i in _this.qcdate_filter_main) {
				if (typeof(_this.qcdate_filter_main[i])!='string') {
					qcdate_filter_main.push(_this.qcdate_filter_main[i].Format("yyyy-MM-dd"));
				} else if (_this.qcdate_filter_main[i] == '') {
					// qcdate_filter_main.push(new Date().Format("yyyy-MM-dd"));
				} else {
					qcdate_filter_main.push(_this.qcdate_filter_main[i]);
				}
			}
			
			var xianti_filter = _this.xianti_filter;
			var jizhongming_filter_main = _this.jizhongming_filter_main;
			var pinfan_filter = _this.pinfan_filter;
			var pinming_filter = _this.pinming_filter;
			var leibie_filter = _this.leibie_filter;

			var url = "{{ route('bpjg.zrcfx.maingets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize2,
					page: page,
					qcdate_filter: qcdate_filter_main,
					xianti_filter: xianti_filter,
					jizhongming_filter: jizhongming_filter_main,
					pinfan_filter: pinfan_filter,
					pinming_filter: pinming_filter,
					leibie_filter: leibie_filter
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data) {
					_this.tabledata2 = response.data.data;
				} else {
					_this.tabledata2 = [];
				}
				
				// 合计
				// _this.buliangjianshuxiaoji = 0;
				// for (var i in _this.tabledata1) {
					// _this.buliangjianshuxiaoji += _this.tabledata1[i].shuliang;
				// }
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		
		
		// onclear_zrc
		onclear_zrc: function () {
			var _this = this;
			_this.piliangluru_zrc.map(function (v,i) {
				v.riqi = '';
				v.jizhongming = '';
				v.shuliang = 1;
			});
		},
		
		// onclear_main
		onclear_main: function () {
			var _this = this;
			_this.xianti = '';
			_this.qufen = '';
			_this.piliangluru_main.map(function (v,i) {
				v.jizhongming = '';
				v.pinfan = '';
				v.pinming = '';
				v.xuqiushuliang = 1;
				v.leibie = '';
			});
			
			_this.$refs.xianti.focus();
		},
		
		// oncreate_zrc
		oncreate_zrc: function () {
			var _this = this;
			
			_this.piliangluru_zrc.map(function (v,i) {
				// jizhongming: '',
				// riqi: '',
				// shuliang: 1
				
				if (v.jizhongming == '' || v.riqi == '' || v.shuliang == ''
					|| v.jizhongming == undefined || v.riqi == undefined || v.shuliang == undefined) {
					_this.warning(false, '警告', '输入内容为空或不正确！');
					return false;
				}
				
				if (typeof(v.riqi)!='string') {
					v.riqi = v.riqi.Format("yyyy-MM-dd");
				}
			});
			
			var piliangluru_zrc = _this.piliangluru_zrc;
			
			var url = "{{ route('bpjg.zrcfx.zrccreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				piliangluru: piliangluru_zrc
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data) {
					_this.onclear_zrc();
					_this.success(false, '成功', '记入成功！');
					_this.boo_delete_zrc = true;
					_this.tableselect1 = [];
					_this.zrcgets();

				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},
		
		// oncreate_main
		oncreate_main: function () {
			var _this = this;
			var xianti = _this.xianti;
			var qufen = _this.qufen;
			
			if (xianti == '' || xianti == undefined || qufen == '' || qufen == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确1！');
				return false;
			}
			
			_this.piliangluru_main.map(function (v,i) {
				// jizhongming: '',
				// pinfan: '',
				// pinming: '',
				// xuqiushuliang: 0,
				// leibie: ''
				
				if (v.jizhongming == '' || v.pinfan == '' || v.pinming == ''  || v.xuqiushuliang == '' || v.leibie == ''
					|| v.jizhongming == undefined || v.pinfan == undefined || v.pinming == undefined || v.xuqiushuliang == undefined || v.leibie == undefined) {
					_this.warning(false, '警告', '输入内容为空或不正确2！');
					return false;
				}
			});
			var piliangluru_main = _this.piliangluru_main;
			
			var url = "{{ route('bpjg.zrcfx.maincreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				xianti: xianti,
				qufen: qufen,
				piliangluru: piliangluru_main
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data) {
					_this.onclear_main();
					_this.success(false, '成功', '记入成功！');
					_this.boo_delete_main = true;
					_this.tableselect2 = [];
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
		
		// zrc编辑前查看
		zrc_edit: function (row) {
			var _this = this;

			_this.zrc_id_edit = row.id;
			_this.zrc_riqi_edit = row.riqi;
			_this.zrc_jizhongming_edit = row.jizhongming;
			_this.zrc_shuliang_edit[0] = row.shuliang;
			_this.zrc_shuliang_edit[1] = row.shuliang;
			_this.zrc_created_at_edit = row.created_at;
			_this.zrc_updated_at_edit = row.updated_at;

			_this.modal_zrc_edit = true;
		},

		// main编辑前查看
		main_edit: function (row) {
			var _this = this;
			
			_this.id_edit = row.id;
			_this.jizhongming_edit = row.jizhongming;
			_this.created_at_edit = row.created_at;
			_this.updated_at_edit = row.updated_at;
			_this.jianchajileixing_edit = row.jianchajileixing;
			_this.buliangneirong_edit = row.buliangneirong;
			_this.weihao_edit = row.weihao;
			_this.shuliang_edit[0] = row.shuliang;
			_this.shuliang_edit[1] = row.shuliang;
			_this.jianchazhe_edit = row.jianchazhe;
			_this.dianmei_edit = row.dianmei;
			_this.meishu_edit = row.meishu;
			_this.hejidianshu_edit = row.hejidianshu;
			_this.bushihejianshuheji_edit = row.bushihejianshuheji;
			_this.ppm_edit = row.ppm;

			_this.modal_qcreport_edit = true;
		},		
		
		// zrc编辑后保存
		zrc_edit_ok: function () {
			var _this = this;
			
			var id = _this.zrc_id_edit;
			var riqi = _this.zrc_riqi_edit;
			var jizhongming = _this.zrc_jizhongming_edit;
			var shuliang = _this.zrc_shuliang_edit;
			var created_at = _this.zrc_created_at_edit;
			var updated_at = _this.zrc_updated_at_edit;

			if (riqi == '' || riqi == null || riqi == undefined
				|| jizhongming == '' || jizhongming == null || jizhongming == undefined
				|| shuliang == '' || shuliang == null || shuliang == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			if (typeof(riqi)!='string') {
				riqi = riqi.Format("yyyy-MM-dd");
			}
			
			var url = "{{ route('bpjg.zrcfx.zrcupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				riqi: riqi,
				jizhongming: jizhongming,
				shuliang: shuliang[1],
				created_at: created_at,
				updated_at: updated_at
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				_this.zrcgets(_this.pagecurrent1, _this.pagelast1);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.zrc_id_edit = '';
					_this.zrc_jizhongming_edit = '';
					_this.zrc_shuliang_edit = [0, 0];
					_this.zrc_created_at_edit = '';
					_this.zrc_updated_at_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},

		// main编辑后保存
		main_edit_ok: function () {
			var _this = this;
			
			var id = _this.id_edit;
			var jizhongming = _this.main_jizhongming_edit;
			var created_at = _this.main_created_at_edit;
			var updated_at = _this.main_updated_at_edit;
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
				
				_this.maingets(_this.pagecurrent2, _this.pagelast2);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.id_edit = '';
					_this.main_jizhongming_edit = '';
					_this.main_created_at_edit = '';
					_this.main_updated_at_edit = '';
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
		
		
		// ondelete_zrc
		ondelete_zrc: function () {
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

		
		// ondelete_main
		ondelete_main: function () {
			var _this = this;
			
			var tableselect2 = _this.tableselect2;
			
			if (tableselect2[0] == undefined) return false;

			var url = "{{ route('bpjg.zrcfx.maindelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect2: tableselect2
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.tableselect2 = [];
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
			
			_this.boo_delete_zrc = _this.tableselect1[0] == undefined ? true : false;
			
		},

		// 表2选择
		onselectchange2: function (selection) {
			var _this = this;
			_this.tableselect2 = [];

			for (var i in selection) {
				_this.tableselect2.push(selection[i].id);
			}
			
			_this.boo_delete_main = _this.tableselect2[0] == undefined ? true : false;
			
		},
		
		
		
		
		
		
		
		
		
		
		
		
		

		// exportData_db 当前表数据导出
		exportData_db: function () {
			var _this = this;
			
			if (_this.qcdate_filter_main[0] == '' || _this.qcdate_filter_main == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.qcdate_filter_main[0].Format("yyyy-MM-dd");
			var queryfilter_dateto = _this.qcdate_filter_main[1].Format("yyyy-MM-dd");
			
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
		piliangluru_zrc_generate: function (counts) {
			var len = this.piliangluru_zrc.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru_zrc.push({value: 'piliangluru_zrc'+parseInt(len+i+1)});
					this.piliangluru_zrc.push(
						{
							riqi: '',
							jizhongming: '',
							shuliang: 1
						}
					);
				}
			} else if (counts < len) {
				if (this.piliangluruxiang_zrc != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang_zrc == this.piliangluru_zrc[i].value) {
							this.piliangluruxiang_zrc = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru_zrc.pop();
				}
			}			

		},

		// 生成piliangluru_main
		piliangluru_main_generate: function (counts) {
			var len = this.piliangluru_main.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru_main.push({value: 'piliangluru_main'+parseInt(len+i+1)});
					this.piliangluru_main.push(
						{
							jizhongming: '',
							pinfan: '',
							pinming: '',
							xuqiushuliang: 1,
							leibie: ''
						}
					);
				}
			} else if (counts < len) {
				if (this.piliangluruxiang_main != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang_main == this.piliangluru_main[i].value) {
							this.piliangluruxiang_main = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru_main.pop();
				}
			}			

		},
		
		// onimport_zrc
		onimport_zrc: function () {
			alert('zrc');
			
		},

		// onimport_main
		onimport_main: function () {
			alert('main');
			
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
							subtext: vm_app.qcdate_filter_main[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter_main[1].Format('yyyy-MM-dd') //'2018.06-2018-07'
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
							subtext: vm_app.qcdate_filter_main[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter_main[1].Format('yyyy-MM-dd'),
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
			
			if (_this.qcdate_filter_main[0] == '' || _this.qcdate_filter_main[1] == '') {
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
			
			if (_this.qcdate_filter_main[0] == '' || _this.qcdate_filter_main[1] == '') {
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
		
		
		
		

		
		
		
		
		
			
			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter_main = new Date().Format("yyyy-MM-dd");
		// _this.maingets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection