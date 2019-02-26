@extends('bpjg.layouts.mainbase')

@section('my_title')
部品加工课（中日程分析） - 
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
<strong>部品加工课 - 中日程分析</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Steps :current="4">
        <Step title="维护机种/部品关系表" icon="ios-list-box-outline" content="批量导入会覆盖所有数据，仅做初始化之用。"></Step>
        <Step title="导入中日程表" icon="ios-download-outline" content="可参考模板格式设定数据。"></Step>
        <Step title="分析数据" icon="ios-analytics-outline" content="结果数据按指定月份分析并覆盖。"></Step>
        <Step title="查询导出结果" icon="ios-flag-outline" content="按指定月份查询及导出数据。"></Step>
    </Steps>
	<br><br>

	<Tabs type="card" v-model="currenttabs">
		<Tab-pane label="机种/部品关系表">
		
			<Divider orientation="left">信息录入</Divider>

			<i-row :gutter="16">
				<i-col span="24">
					↓ 批量录入&nbsp;&nbsp;
					<Input-number v-model.lazy="piliangluruxiang_relation" @on-change="value=>piliangluru_relation_generate(value)" :min="1" :max="10" size="small" style="width: 60px"></Input-number>
					&nbsp;项
				</i-col>
			</i-row>
			
			&nbsp;

			<span v-for="(item, index) in piliangluru_relation">
			
			<i-row :gutter="16">
			<br>
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
					<i-select v-model.lazy="item.leibie" size="small" style="width:80px" placeholder="">
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
				<i-col span="3">
					&nbsp;&nbsp;<i-button @click="oncreate_relation()" type="primary">记入</i-button>
					&nbsp;&nbsp;<i-button @click="onclear_relation()">清除</i-button>
				</i-col>
				<i-col span="4">
					<Upload
						:before-upload="uploadstart_relation"
						:show-upload-list="false"
						:format="['xls','xlsx']"
						:on-format-error="handleFormatError"
						:max-size="2048"
						action="/">
						<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus" :disabled="uploaddisabled">@{{ loadingStatus ? '上传中...' : '批量导入 机种/部品关系表' }}</i-button>
					</Upload>
				</i-col>
				<i-col span="2">
					<i-button @click="download_relation()" type="text"><font color="#2db7f5">[下载模板]</font></i-button>
				</i-col>
				<i-col span="15">
					&nbsp;
				</i-col>
			</i-row>

			<br><br>

			<i-row :gutter="16">
				<i-col span="3">
					&nbsp;
				</i-col>
				<i-col span="21">
					<font color="#ff9900">* 注意：批量导入会将原有 [机种/部品关系表] 数据覆盖！！</font>
				</i-col>
			</i-row>
			
			<br><br>	
			
			
			<Divider orientation="left">信息查询</Divider>

			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="6">
					日期范围&nbsp;&nbsp;
					<Date-picker v-model.lazy="qcdate_filter_relation" :options="qcdate_filter_relation_options" @on-change="relationgets(pagecurrent_relation, pagelast_relation);onselectchange_relation();" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="12">
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
					<i-input v-model.lazy="jizhongming_filter_relation" @on-change="relationgets(pagecurrent_relation, pagelast_relation)" @on-keyup="jizhongming_filter_relation=jizhongming_filter_relation.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
				</i-col>
				<i-col span="3">
					品番&nbsp;&nbsp;
					<i-input v-model.lazy="pinfan_filter_relation" @on-change="relationgets(pagecurrent_relation, pagelast_relation)" @on-keyup="pinfan_filter_relation=pinfan_filter_relation.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
				</i-col>
				<i-col span="3">
					品名&nbsp;&nbsp;
					<i-input v-model.lazy="pinming_filter_relation" @on-change="relationgets(pagecurrent_relation, pagelast_relation)" @on-keyup="pinming_filter_relation=pinming_filter_relation.toUpperCase()" size="small" clearable style="width: 100px"></i-input>
				</i-col>
				<i-col span="3">
					类别&nbsp;&nbsp;
					<i-select v-model.lazy="leibie_filter" @on-change="relationgets(pagecurrent_relation, pagelast_relation);onselectchange_relation();" clearable size="small" style="width:100px" placeholder="">
						<i-option v-for="item in option_leibie" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
			</i-row>
			<br><br>

			<i-row :gutter="16">
				<br>
				<i-col span="2">
					<i-button @click="ondelete_relation()" :disabled="boo_delete_relation" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
				</i-col>
				<i-col span="4">
					导出：&nbsp;&nbsp;&nbsp;&nbsp;
					<i-button type="default" size="small" @click="exportData_relation()"><Icon type="ios-download-outline"></Icon> 导出后台数据</i-button>
				</i-col>
				<i-col span="18">
					&nbsp;
				</i-col>
			</i-row>

			<i-row :gutter="16">
				<i-col span="24">
					<i-table ref="table2" height="420" size="small" border :columns="tablecolumns_relation" :data="tabledata_relation" @on-selection-change="selection => onselectchange_relation(selection)"></i-table>
					<br><Page :current="pagecurrent_relation" :total="pagetotal_relation" :page-size="pagepagesize_relation" @on-change="currentpage => oncurrentpagechange_relation(currentpage)" show-total show-elevator></Page><br><br>
				</i-col>
			</i-row>

			<Modal v-model="modal_relation_edit" @on-ok="relation_edit_ok" ok-text="保存" title="编辑 - 机种/部品关系表" width="600">
				<div style="text-align:left">
					<p>
						创建时间：@{{ relation_created_at_edit }}
						
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						更新时间：@{{ relation_updated_at_edit }}
					
					</p>
					<br>
					
					<p>
						机种名&nbsp;&nbsp;
						<i-input v-model.lazy="relation_jizhongming_edit" @on-keyup="relation_jizhongming_edit=relation_jizhongming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

						&nbsp;&nbsp;&nbsp;&nbsp;

						品番&nbsp;&nbsp;
						<i-input v-model.lazy="relation_pinfan_edit" @on-keyup="relation_pinfan_edit=relation_pinfan_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 120px"></i-input>

						&nbsp;&nbsp;&nbsp;&nbsp;

						品名&nbsp;&nbsp;
						<i-input v-model.lazy="relation_pinming_edit" @on-keyup="relation_pinming_edit=relation_pinming_edit.toUpperCase()" placeholder="例：" size="small" clearable style="width: 160px"></i-input>

					</p>
					<br>
					
					<p>
						需求数量&nbsp;&nbsp;
						<Input-number v-model.lazy="relation_xuqiushuliang_edit[1]" :min="1" size="small" style="width: 80px"></Input-number>

						&nbsp;&nbsp;&nbsp;&nbsp;
						
						类别&nbsp;&nbsp;
						<i-select v-model.lazy="relation_leibie_edit" size="small" style="width:100px" placeholder="">
							<i-option v-for="item in option_leibie" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</i-select>
						
						&nbsp;&nbsp;&nbsp;&nbsp;
					</p>
					
					&nbsp;
				
				</div>	
			</Modal>
		
		</Tab-pane>


		<Tab-pane label="部品信息">
		
			<Divider orientation="left">导入、分析及查询导出</Divider>

			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					导入：
				</i-col>
				<i-col span="3">
					<Upload
						:before-upload="uploadstart_zrcfx"
						:show-upload-list="false"
						:format="['xls','xlsx']"
						:on-format-error="handleFormatError"
						:max-size="2048"
						action="/">
						<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus" :disabled="uploaddisabled" size="small">@{{ loadingStatus ? '上传中...' : '批量导入 中日程表' }}</i-button>
					</Upload>
				</i-col>
				<i-col span="2">
					<i-button @click="download_zrc()" type="text" size="small"><font color="#2db7f5">[下载模板]</font></i-button>
				</i-col>
				<i-col span="1">
					&nbsp;
				</i-col>
				<i-col span="1">
					分析：
				</i-col>
				<i-col span="4">
					* 选择月份&nbsp;&nbsp;
					<Date-picker v-model.lazy="date_fenxi_suoshuriqi" type="month" size="small" style="width:100px"></Date-picker>
				</i-col>
				<i-col span="8">
					<i-button type="primary" @click="analytics_main()" :loading="analytics_loading" :disabled="analytics_disabled"><Icon type="ios-analytics-outline" v-show="!analytics_loading"></Icon> <span v-if="!analytics_loading">分析数据</span><span v-else>分析数据中...</span></i-button>
				</i-col>
				<i-col span="2">
					&nbsp;
				</i-col>
			</i-row>
			
			<Modal	v-model="modal_fenxi"	title="分析数据" ok-text="开始分析" @on-ok="fenxi_ok" @on-cancel="fenxi_cancel" width="400">
				<p>所属日期：<strong>@{{ fenxi_suoshuriqi }}</strong></p>
				<br>
				<p>注意：务必确认“所属日期”，否则原有数据将被覆盖！！</p>
			</Modal>	
			<br><br>
			
			<i-row :gutter="16">
				<br>
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="4">
					* 选择月份&nbsp;&nbsp;
					<Date-picker v-model.lazy="qcdate_filter_result" @on-change="resultgets(pagecurrent_result, pagelast_result);" type="month" size="small" style="width:100px"></Date-picker>
				</i-col>
				<i-col span="4">
					品番&nbsp;&nbsp;
					<i-input v-model.lazy="pinfan_filter_result" @on-change="resultgets(pagecurrent_relation, pagelast_relation)" @on-keyup="pinfan_filter_result=pinfan_filter_result.toUpperCase()" placeholder="" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
					品名&nbsp;&nbsp;
					<i-input v-model.lazy="pinming_filter_result" @on-change="resultgets(pagecurrent_relation, pagelast_relation)" @on-keyup="pinming_filter_result=pinming_filter_result.toUpperCase()" placeholder="" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="9">
				&nbsp;
				</i-col>
			</i-row>
			<br><br>
			
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;<br>&nbsp;
					<!--<i-button @click="ondelete_relation()" :disabled="boo_delete_relation" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;-->
				</i-col>
				<i-col span="4">
					导出：&nbsp;&nbsp;&nbsp;&nbsp;
					<i-button type="default" size="small" @click="exportData_result()"><Icon type="ios-download-outline"></Icon> 导出后台数据</i-button>
				</i-col>
				<i-col span="18">
					&nbsp;
				</i-col>
			</i-row>
			<br>


			<i-row :gutter="16">
				<i-col span="24">
					<i-table ref="table_result" height="650" size="small" border :columns="tablecolumns_result" :data="tabledata_result"></i-table>
					<br><Page :current="pagecurrent_result" :total="pagetotal_result" :page-size="pagepagesize_result" @on-change="currentpage => oncurrentpagechange_result(currentpage)" show-total show-elevator></Page><br><br>
				</i-col>
			</i-row>

		</Tab-pane>

	</Tabs>

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
		//表relation分页
		pagecurrent_relation: 1,
		pagetotal_relation: 1,
		pagepagesize_relation: 10,
		pagelast_relation: 1,

		//表result分页
		pagecurrent_result: 1,
		pagetotal_result: 1,
		pagepagesize_result: 15,
		pagelast_result: 1,
		
		// ##########基本变量########
		// 批量录入realtion表
		piliangluru_relation: [
			{
				jizhongming: '',
				pinfan: '',
				pinming: '',
				xuqiushuliang: 1,
				leibie: ''
			},
		],

		// 批量录入项
		piliangluruxiang_relation: 1,

		//类别
		option_leibie: [
			{value: '冲压', label: '冲压'},
			{value: '成型', label: '成型'}
		],
		
		// ##########查询过滤########
		// 日期范围过滤
		qcdate_filter_relation: [], //new Date(),
		qcdate_filter_relation_options: {
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

		qcdate_filter_result: '', //new Date(),
		date_fenxi_suoshuriqi: '', //new Date(),
		
		// 机种名
		jizhongming_filter_relation: '',

		// 品番过滤
		pinfan_filter_result: '',
		pinfan_filter_relation: '',

		// 品名过滤
		pinming_filter_result: '',
		pinming_filter_relation: '',
		
		// 类别过滤
		leibie_filter: '',

		// ##########编辑变量########
		modal_relation_edit: false,
		relation_id_edit: '',
		relation_xianti_edit: '',
		relation_qufen_edit: '',
		relation_created_at_edit: '',
		relation_updated_at_edit: '',
		relation_jizhongming_edit: '',
		relation_pinfan_edit: '',
		relation_pinming_edit: '',
		relation_xuqiushuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值
		relation_leibie_edit: '',

		modal_fenxi: false,
		fenxi_suoshuriqi: '',
		analytics_disabled: false,
		analytics_loading: false,
		modal_relationimport: false,
		

		// 表头relation
		tablecolumns_relation: [
			{
				type: 'selection',
				width: 50,
				align: 'center',
				fixed: 'left'
			},
			{
				type: 'index',
				title: '序号',
				width: 70,
				align: 'center',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize_relation * (vm_app.pagecurrent_relation - 1)
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
				title: '品番',
				key: 'pinfan',
				align: 'center',
				width: 140,
				// sortable: true
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 200
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
				title: '类别',
				key: 'leibie',
				align: 'center',
				width: 100
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
									vm_app.relation_edit(params.row)
								}
							}
						}, 'Edit')
					]);
				},
				fixed: 'right'
			},			
		],
		tabledata_relation: [],
		tableselect_relation: [],
		
		
		// 表头 result
		tablecolumns_result: [
			{
				type: 'index',
				title: '序号',
				width: 70,
				align: 'center',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize_result * (vm_app.pagecurrent_result - 1)
				}
			},
			{
				title: '品番',
				key: 'pinfan',
				align: 'center',
				width: 140,
				// sortable: true
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 200
			},
			{
				title: '总数',
				key: 'zongshu',
				align: 'center',
				width: 100,
				className: 'table-info-column',
				// sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.zongshu ? params.row.zongshu.toLocaleString() : ''
					]);
				}
			},
			{
				title: '1',
				key: 'd1',
				align: 'center',
				width: 80,
				// className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						params.row.d1 ? params.row.d1.toLocaleString() : ''
					]);
				}
			},
			{
				title: '2',
				key: 'd2',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d2 ? params.row.d2.toLocaleString() : ''
					]);
				}
			},
			{
				title: '3',
				key: 'd3',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d3 ? params.row.d3.toLocaleString() : ''
					]);
				}
			},
			{
				title: '4',
				key: 'd4',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d4 ? params.row.d4.toLocaleString() : ''
					]);
				}
			},
			{
				title: '5',
				key: 'd5',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d5 ? params.row.d5.toLocaleString() : ''
					]);
				}
			},
			{
				title: '6',
				key: 'd6',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d6 ? params.row.d6.toLocaleString() : ''
					]);
				}
			},
			{
				title: '7',
				key: 'd7',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d7 ? params.row.d7.toLocaleString() : ''
					]);
				}
			},
			{
				title: '8',
				key: 'd8',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d8 ? params.row.d8.toLocaleString() : ''
					]);
				}
			},
			{
				title: '9',
				key: 'd9',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d9 ? params.row.d9.toLocaleString() : ''
					]);
				}
			},
			{
				title: '10',
				key: 'd10',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d10 ? params.row.d10.toLocaleString() : ''
					]);
				}
			},
			{
				title: '11',
				key: 'd11',
				align: 'center',
				width: 80,
				// className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						params.row.d11 ? params.row.d11.toLocaleString() : ''
					]);
				}
			},
			{
				title: '12',
				key: 'd12',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d12 ? params.row.d12.toLocaleString() : ''
					]);
				}
			},
			{
				title: '13',
				key: 'd13',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d13 ? params.row.d13.toLocaleString() : ''
					]);
				}
			},
			{
				title: '14',
				key: 'd14',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d14 ? params.row.d14.toLocaleString() : ''
					]);
				}
			},
			{
				title: '15',
				key: 'd15',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d15 ? params.row.d15.toLocaleString() : ''
					]);
				}
			},
			{
				title: '16',
				key: 'd16',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d16 ? params.row.d16.toLocaleString() : ''
					]);
				}
			},
			{
				title: '17',
				key: 'd17',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d17 ? params.row.d17.toLocaleString() : ''
					]);
				}
			},
			{
				title: '18',
				key: 'd18',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d18 ? params.row.d18.toLocaleString() : ''
					]);
				}
			},
			{
				title: '19',
				key: 'd19',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d19 ? params.row.d19.toLocaleString() : ''
					]);
				}
			},
			{
				title: '20',
				key: 'd20',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d20 ? params.row.d20.toLocaleString() : ''
					]);
				}
			},
			{
				title: '21',
				key: 'd21',
				align: 'center',
				width: 80,
				// className: 'table-info-column',
				render: (h, params) => {
					return h('div', [
						params.row.d21 ? params.row.d21.toLocaleString() : ''
					]);
				}
			},
			{
				title: '22',
				key: 'd22',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d22 ? params.row.d22.toLocaleString() : ''
					]);
				}
			},
			{
				title: '23',
				key: 'd23',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d23 ? params.row.d23.toLocaleString() : ''
					]);
				}
			},
			{
				title: '24',
				key: 'd24',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d24 ? params.row.d24.toLocaleString() : ''
					]);
				}
			},
			{
				title: '25',
				key: 'd25',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d25 ? params.row.d25.toLocaleString() : ''
					]);
				}
			},
			{
				title: '26',
				key: 'd26',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d26 ? params.row.d26.toLocaleString() : ''
					]);
				}
			},
			{
				title: '27',
				key: 'd27',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d27 ? params.row.d27.toLocaleString() : ''
					]);
				}
			},
			{
				title: '28',
				key: 'd28',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d28 ? params.row.d28.toLocaleString() : ''
					]);
				}
			},
			{
				title: '29',
				key: 'd29',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d29 ? params.row.d29.toLocaleString() : ''
					]);
				}
			},
			{
				title: '30',
				key: 'd30',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d30 ? params.row.d30.toLocaleString() : ''
					]);
				}
			},
			{
				title: '31',
				key: 'd31',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.d31 ? params.row.d31.toLocaleString() : ''
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
		],
		tabledata_result: [],
		tableselect_result: [],
		
		// 删除disabled
		boo_delete_relation: true,
		
		
		// 上传，批量导入
		file: null,
		loadingStatus: false,
		uploaddisabled: false,
		
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
		

		// main列表
		relationgets: function (page, last_page) {
			var _this = this;

			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}

			var jizhongming_filter = _this.jizhongming_filter_relation;
			var pinfan_filter = _this.pinfan_filter_relation;
			var pinming_filter = _this.pinming_filter_relation;
			var leibie_filter = _this.leibie_filter;
			
			var qcdate_filter_relation = [];

			if (_this.qcdate_filter_relation[0] == '') {
				if (jizhongming_filter == '' && pinfan_filter == '' && pinming_filter== '' && leibie_filter == '') {
					_this.tabledata_relation = [];
					return false;
				} else {
					const end = new Date();
					const start = new Date();
					// end.setTime(end.getTime() + 3600 * 1000 * 24 * 1);
					end.setDate(end.getDate());
					// start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
					start.setDate(start.getDate() - 365);
					qcdate_filter_relation = [start, end];
				}
			} else {
 				// for (var i in _this.qcdate_filter_relation) {
					// typeof(_this.qcdate_filter_relation[i])!='string' ? qcdate_filter_relation[i] =  _this.qcdate_filter_relation[i].Format("yyyy-MM-dd") : qcdate_filter_relation[i] =  _this.qcdate_filter_relation[i];
				// }
 				// for (var i in _this.qcdate_filter_relation) {
				// 	qcdate_filter_relation0.push(_this.qcdate_filter_relation[i]);
				// }
				qcdate_filter_relation =  _this.qcdate_filter_relation;
			}

			qcdate_filter_relation = [qcdate_filter_relation[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter_relation[1].Format("yyyy-MM-dd 23:59:59")];
			// console.log(_this.qcdate_filter_relation);
			// console.log(qcdate_filter_relation);

			var url = "{{ route('bpjg.zrcfx.relationgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize_relation,
					page: page,
					qcdate_filter: qcdate_filter_relation,
					jizhongming_filter: jizhongming_filter,
					pinfan_filter: pinfan_filter,
					pinming_filter: pinming_filter,
					leibie_filter: leibie_filter
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.pagecurrent_relation = response.data.current_page;
					_this.pagetotal_relation = response.data.total;
					_this.pagelast_relation = response.data.last_page
					
					_this.tabledata_relation = response.data.data;
				} else {
					_this.tabledata_relation = [];
				}
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		
		
		// result列表
		resultgets: function (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			if (_this.qcdate_filter_result == '' || _this.qcdate_filter_result == undefined) {
				_this.tabledata_result = [];
				return false;
			}
			
			var datex = _this.qcdate_filter_result.Format("yyyy-MM");
			// var days =	getDays(datex); //例：getDays(2018-12)
			
			// var qcdate_filter_result = [];
			// qcdate_filter_result[0] = datex + '-01 00:00:00';
			// qcdate_filter_result[1] = datex + '-' + days + ' 23:59:59';
			
			// console.log(qcdate_filter_result);
			// return false;
			
			// var xianti_filter = _this.xianti_filter;
			// var jizhongming_filter_relation = _this.jizhongming_filter_relation;
			var pinfan_filter = _this.pinfan_filter_result;
			var pinming_filter = _this.pinming_filter_result;
			// var leibie_filter = _this.leibie_filter;

			var url = "{{ route('bpjg.zrcfx.resultgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize_result,
					page: page,
					qcdate_filter: datex,
					// xianti_filter: xianti_filter,
					// jizhongming_filter: jizhongming_filter_relation,
					pinfan_filter: pinfan_filter,
					pinming_filter: pinming_filter,
					// leibie_filter: leibie_filter
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.pagecurrent_result = response.data.current_page;
					_this.pagetotal_result = response.data.total;
					_this.pagelast_result = response.data.last_page
					
					_this.tabledata_result = response.data.data;
				} else {
					_this.tabledata_result = [];
				}
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		
		
		// onclear_relation
		onclear_relation: function () {
			var _this = this;
			_this.piliangluru_relation.map(function (v,i) {
				v.jizhongming = '';
				v.pinfan = '';
				v.pinming = '';
				v.xuqiushuliang = 1;
				v.leibie = '';
			});
			
			// _this.$refs.xianti.focus();
		},
		

		// oncreate_relation
		oncreate_relation: function () {
			var _this = this;

			var booFlagOk = true;
			_this.piliangluru_relation.map(function (v,i) {
				// jizhongming: '',
				// pinfan: '',
				// pinming: '',
				// xuqiushuliang: 0,
				// leibie: ''
				
				if (v.jizhongming == '' || v.pinfan == '' || v.pinming == ''  || v.xuqiushuliang == '' || v.leibie == ''
					|| v.jizhongming == undefined || v.pinfan == undefined || v.pinming == undefined || v.xuqiushuliang == undefined || v.leibie == undefined) {
					booFlagOk = false;
				}
			});
			
			if (booFlagOk == false) {
				_this.warning(false, '警告', '输入内容为空或不正确2！');
				return false;
			}
			
			var piliangluru_relation = _this.piliangluru_relation;
			
			var url = "{{ route('bpjg.zrcfx.relationcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				piliangluru: piliangluru_relation
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.onclear_relation();
					_this.success(false, '成功', '记入成功！');
					_this.boo_delete_relation = true;
					_this.tableselect_relation = [];
					_this.relationgets();
				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},
		

		// relation编辑前查看
		relation_edit: function (row) {
			var _this = this;
			
			_this.relation_id_edit = row.id;
			_this.relation_jizhongming_edit = row.jizhongming;
			_this.relation_pinfan_edit = row.pinfan;
			_this.relation_pinming_edit = row.pinming;
			_this.relation_xuqiushuliang_edit[0] = row.xuqiushuliang;
			_this.relation_xuqiushuliang_edit[1] = row.xuqiushuliang;
			_this.relation_leibie_edit = row.leibie;
			_this.relation_created_at_edit = row.created_at;
			_this.relation_updated_at_edit = row.updated_at;

			_this.modal_relation_edit = true;
		},		
		

		// relation编辑后保存
		relation_edit_ok: function () {
			var _this = this;
			
			var id = _this.relation_id_edit;
			var jizhongming = _this.relation_jizhongming_edit;
			var pinfan = _this.relation_pinfan_edit;
			var pinming = _this.relation_pinming_edit;
			var xuqiushuliang = _this.relation_xuqiushuliang_edit;
			var leibie = _this.relation_leibie_edit;
			var created_at = _this.relation_created_at_edit;
			var updated_at = _this.relation_updated_at_edit;
			
			if (jizhongming == '' || jizhongming == null || jizhongming == undefined
				|| pinfan == '' || pinfan == null || pinfan == undefined
				|| pinming == '' || pinming == null || pinming == undefined
				|| xuqiushuliang == '' || xuqiushuliang == null || xuqiushuliang == undefined
				|| leibie == '' || leibie == null || leibie == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			var url = "{{ route('bpjg.zrcfx.relationupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				jizhongming: jizhongming,
				pinfan: pinfan,
				pinming: pinming,
				xuqiushuliang: xuqiushuliang[1],
				leibie: leibie,
				created_at: created_at,
				updated_at: updated_at
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.relationgets(_this.pagecurrent_relation, _this.pagelast_relation);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.relation_id_edit = '';
					_this.relation_jizhongming_edit = '';
					_this.relation_pinfan_edit = '';
					_this.relation_pinming_edit = '';
					_this.relation_xuqiushuliang_edit = [0, 0];
					_this.relation_leibie_edit = '';
					_this.relation_created_at_edit = '';
					_this.relation_updated_at_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},
		
		
		// ondelete_relation
		ondelete_relation: function () {
			var _this = this;
			
			var tableselect_relation = _this.tableselect_relation;
			
			if (tableselect_relation[0] == undefined) return false;

			var url = "{{ route('bpjg.zrcfx.relationdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect_relation: tableselect_relation
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.boo_delete_relation = true;
					_this.tableselect_relation = [];
					_this.relationgets(_this.pagecurrent_relation, _this.pagelast_relation);
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},		
		
		
		// 表relation选择
		onselectchange_relation: function (selection) {
			var _this = this;
			_this.tableselect_relation = [];

			for (var i in selection) {
				_this.tableselect_relation.push(selection[i].id);
			}
			
			_this.boo_delete_relation = _this.tableselect_relation[0] == undefined ? true : false;
			
		},


		// 生成piliangluru_relation
		piliangluru_relation_generate: function (counts) {
			if (counts == undefined) counts = 1;
			var len = this.piliangluru_relation.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru_relation.push({value: 'piliangluru_relation'+parseInt(len+i+1)});
					this.piliangluru_relation.push(
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
				if (this.piliangluruxiang_relation != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang_relation == this.piliangluru_relation[i].value) {
							this.piliangluruxiang_relation = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru_relation.pop();
				}
			}
		},
		
		
		// upload function
		handleFormatError (file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select <strong>xls</strong> or <strong>xlsx</strong>.'
			});
		},
		handleMaxSize (file) {
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File  ' + file.name + ' is too large, no more than <strong>2M</strong>.'
			});
		},
		handleUpload: function (file) {
			this.file = file;
			return false;
		},
		uploadstart_zrcfx: function (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled = true;
			_this.loadingStatus = true;

			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',_this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('bpjg.zrcfx.zrcfximport') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
			axios({
				url: url,
				method: 'post',
				data: formData,
				processData: false,// 告诉axios不要去处理发送的数据(重要参数)
				contentType: false, // 告诉axios不要去设置Content-Type请求头
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					_this.success(false, 'Success', '导入成功！');
				} else {
					_this.error(false, 'Error', '导入失败！注意内容文本格式并且内容不能为空！');
				}
				
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
				
			})
		},
		uploadstart_relation: function (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled = true;
			_this.loadingStatus = true;

			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',_this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('bpjg.zrcfx.relationimport') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';
			axios({
				url: url,
				method: 'post',
				data: formData,
				processData: false,// 告诉axios不要去处理发送的数据(重要参数)
				contentType: false, // 告诉axios不要去设置Content-Type请求头
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, 'Success', '导入成功！');
				} else {
					_this.error(false, 'Error', '导入失败！注意内容文本格式并且内容不能为空！');
				}
				
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
			})
		},
		uploadcancel: function () {
			this.file = null;
			// this.loadingStatus = false;
		},
		
		
		// zrc模板下载
		download_zrc: function () {
			var url = "{{ route('bpjg.zrcfx.zrcdownload') }}";
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},


		// relation模板下载
		download_relation: function () {
			var url = "{{ route('bpjg.zrcfx.relationdownload') }}";
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},
		
		
		// exportData_relation 主表数据导出
		exportData_relation: function () {
			var _this = this;
			
			if (_this.qcdate_filter_relation[0] == '' || _this.qcdate_filter_relation[0] == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.qcdate_filter_relation[0].Format("yyyy-MM-dd");
			var queryfilter_dateto = _this.qcdate_filter_relation[1].Format("yyyy-MM-dd");
			
			var url = "{{ route('bpjg.zrcfx.relationexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			// console.log(url);
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},
		
		
		// exportData_result 结果表数据导出
		exportData_result: function () {
			var _this = this;
			
			if (_this.qcdate_filter_result == '' || _this.qcdate_filter_result == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var datex = _this.qcdate_filter_result.Format("yyyy-MM");
			
			var url = "{{ route('bpjg.zrcfx.resultexport') }}"
				+ "?queryfilter=" + datex;
				
			// console.log(url);
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},
		
		
		// 分析数据提示
		analytics_main: function () {
			var _this = this;
			
			if (_this.date_fenxi_suoshuriqi == '' || _this.date_fenxi_suoshuriqi == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			_this.fenxi_suoshuriqi = _this.date_fenxi_suoshuriqi.Format("yyyy-MM");
			_this.analytics_disabled = true;
			_this.analytics_loading = true;
			_this.modal_fenxi = true;
			return false;
		},
		
		// 分析确定
		fenxi_ok: function () {
			var _this = this;
			
			if (_this.date_fenxi_suoshuriqi == '' || _this.date_fenxi_suoshuriqi == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				_this.analytics_loading = false;
				_this.analytics_disabled = false;
				return false;
			}
			
			var datex = _this.date_fenxi_suoshuriqi.Format("yyyy-MM");
			// var days =	getDays(datex); //例：getDays(2018-12)
			
			// var date_fenxi_suoshuriqi = [];
			// date_fenxi_suoshuriqi[0] = datex + '-01 00:00:00';
			// date_fenxi_suoshuriqi[1] = datex + '-' + days + ' 23:59:59';

			var url = "{{ route('bpjg.zrcfx.zrcfxfunction') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					suoshuriqi_filter: datex,
					// suoshuriqi_range: date_fenxi_suoshuriqi,
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '分析数据成功！');
					// _this.boo_delete_relation = true;
					_this.tableselect_result = [];
					_this.resultgets(_this.pagecurrent_result, _this.pagelast_result);
				} else {
					_this.error(false, '失败', '分析数据失败！');
				}
				_this.analytics_loading = false;
				_this.analytics_disabled = false;
			})
			.catch(function (error) {
				_this.error(false, '错误', '分析数据失败！');
				_this.analytics_loading = false;
				_this.analytics_disabled = false;
			})
		},

		// 分析取消
		fenxi_cancel: function () {
			// this.modal_fenxi = false;
			this.analytics_disabled = false;
			this.analytics_loading = false;
		},
		
		
		// 切换当前页 result
		oncurrentpagechange_result: function (currentpage) {
			this.resultgets(currentpage, this.pagelast_result);
		},
		
		
		// 切换当前页 relation
		oncurrentpagechange_relation: function (currentpage) {
			this.relationgets(currentpage, this.pagelast_relation);
		},

			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter_relation = new Date().Format("yyyy-MM-dd");
		// _this.relationgets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection