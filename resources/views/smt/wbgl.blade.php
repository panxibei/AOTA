@extends('smt.layouts.mainbase')

@section('my_title')
SMT(网板管理) - 
@parent
@endsection

@section('my_style')
<style type="text/css">
/* 合并单元格样式 */
.subCol>ul>li{
      margin:0 -18px;
      list-style:none;
      text-Align: center;
      padding: 9px;
      border-bottom:1px solid #E8EAEC;
      overflow-x: hidden;
	  line-height: 2.2;
}
.subCol>ul>li:last-child{
  border-bottom: none
}

.ivu-table .table-info-row td{
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-error-row td{
	background-color: #ff6600;
	color: #fff;
}
.ivu-table td.table-info-column{
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-info-cell-name {
	background-color: #2db7f5;
	color: #fff;
}
.ivu-table .table-info-cell-age {
	background-color: #ff6600;
	color: #fff;
}
.ivu-table .table-info-cell-address {
	background-color: #187;
	color: #fff;
}

.ivu-table td.table-info-column-buliangxinxi {
	background-color: #90A4AE;
	color: #fff;
}
</style>
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_project')
<strong>SMT 网板管理</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Tabs type="card" v-model="currenttabs" :animated="false">

		@hasanyrole('role_smt_wbgl_write|role_super_admin')
		<Tab-pane label="网板录入">

			<br>
			<i-row :gutter="16">
				<i-col span="1">
				&nbsp;导入：
				</i-col>
				<i-col span="3">
					<Upload
						:before-upload="uploadstart_wbglbase"
						:show-upload-list="false"
						:format="['xls','xlsx']"
						:on-format-error="handleFormatError"
						:max-size="2048"
						action="/">
						<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus" :disabled="uploaddisabled" size="small">@{{ loadingStatus ? '导入中...' : '导入网板基础数据表' }}</i-button>
					</Upload>
				</i-col>
				<i-col span="2">
					<i-button @click="download_wbglbase()" type="text"><font color="#2db7f5">[下载模板]</font></i-button>
				</i-col>
				<i-col span="18">
					&nbsp;
				</i-col>
			</i-row>

			<br>
			<Divider></Divider>
			<br>

			<i-row :gutter="16">
				<i-col span="6">
					* 编号&nbsp;&nbsp;
					<Poptip trigger="focus" placement="top-start" content="输入编号开始查询录入...." transfer="true">
					<i-input ref="ref_bianhao" v-model.lazy="bianhao" element-id="id_bianhao" @on-keyup="bianhao=bianhao.toUpperCase()" @on-change="onchange_bianhao();" placeholder="6-1" size="large" clearable autofocus style="width: 180px"></i-input>
					</Poptip>
				</i-col>
				<i-col span="7">
					* <strong>网板部番</strong>&nbsp;&nbsp;
					<i-input ref="saomiao" element-id="id_wangbanbufan" v-model.lazy="wangbanbufan"  @on-keyup="wangbanbufan=wangbanbufan.toUpperCase()" placeholder="84-36240Z01-A" size="large" clearable style="width: 260px"></i-input>
				</i-col>
				<i-col span="7">
					* <strong>品名</strong>&nbsp;&nbsp;
					<i-input element-id="id_pinming" v-model.lazy="pinming"  @on-keyup="pinming=pinming.toUpperCase()" placeholder="MAIN-RA" size="large" clearable style="width: 160px"></i-input>
				</i-col>
				<i-col span="4">
					&nbsp;
				</i-col>
			</i-row>

			<br><br><br>

			<i-row :gutter="16">
				<i-col span="6">
					* 机种名&nbsp;&nbsp;
					<i-input v-model.lazy="jizhongming"  @on-keyup="jizhongming=jizhongming.toUpperCase()" placeholder="RH00202A" size="large" clearable style="width: 180px"></i-input>
				</i-col>
				<i-col span="6">
					* 系列&nbsp;&nbsp;
					<i-input v-model.lazy="xilie"  @on-keyup="xilie=xilie.toUpperCase()" placeholder="T7AW" size="large" clearable style="width: 180px"></i-input>
				</i-col>
				<i-col span="6">
					* 网板作成日期&nbsp;&nbsp;
					<Date-picker v-model.lazy="wangbanzuochengriqi" type="date" style="width:120px" size="large" placeholder=""></Date-picker>
				</i-col>
				<i-col span="6">
					* 网板编号&nbsp;&nbsp;
					<i-input v-model.lazy="wangbanbianhao"  @on-keyup="wangbanbianhao=wangbanbianhao.toUpperCase()" placeholder="SZ19-7708" size="large" clearable style="width: 180px"></i-input>
				</i-col>
				<!-- <i-col span="6">
					* 编号&nbsp;&nbsp;
					<i-input v-model.lazy="bianhao"  @on-keyup="bianhao=bianhao.toUpperCase()" placeholder="6-1" size="large" clearable style="width: 180px"></i-input>
				</i-col> -->
			</i-row>

			<br><br><br>

			<i-row :gutter="16">
				<i-col span="6">
					* 网板厚度&nbsp;&nbsp;
					<i-input v-model.lazy="wangbanhoudu"  @on-keyup="wangbanhoudu=wangbanhoudu.toUpperCase()" placeholder="T:0.13~0.11" size="large" clearable style="width: 180px"></i-input>
				</i-col>
				<i-col span="6">
					* 特殊工艺&nbsp;&nbsp;
					<i-input v-model.lazy="teshugongyi"  @on-keyup="teshugongyi=teshugongyi.toUpperCase()" placeholder="纳米, 0603" size="large" clearable style="width: 180px"></i-input>
				</i-col>
				<i-col span="2">
					* 张力1&nbsp;&nbsp;
					<Input-number v-model.lazy="zhangli1" :min="1" style="width: 60px"></Input-number>
				</i-col>
				<i-col span="2">
					* 张力2&nbsp;&nbsp;
					<Input-number v-model.lazy="zhangli2" :min="1" style="width: 60px"></Input-number>
				</i-col>
				<i-col span="2">
					* 张力3&nbsp;&nbsp;
					<Input-number v-model.lazy="zhangli3" :min="1" style="width: 60px"></Input-number>
				</i-col>
				<i-col span="2">
					* 张力4&nbsp;&nbsp;
					<Input-number v-model.lazy="zhangli4" :min="1" style="width: 60px"></Input-number>
				</i-col>
				<i-col span="2">
					* 张力5&nbsp;&nbsp;
					<Input-number v-model.lazy="zhangli5" :min="1" style="width: 60px"></Input-number>
				</i-col>
				<i-col span="2">
					&nbsp;
				</i-col>
			</i-row>

			&nbsp;<br>
			<i-row :gutter="16">
				<i-col span="24">
					&nbsp;&nbsp;<i-button @click="oncreate()" icon="ios-create-outline" :disabled="disabled_create" type="primary">记入</i-button>
					&nbsp;&nbsp;<i-button @click="onclear()" icon="ios-close-circle-outline">清除</i-button>
				</i-col>
			</i-row>

			&nbsp;<br><br><br>

			<span v-for="n in (10 - piliangluru.length)">
			<br><br>
			</span>

		</Tab-pane>
		@endhasanyrole

		<Tab-pane label="网板记录">

			<br>
			<i-row :gutter="16">
				<i-col span="2">
					&nbsp;
				</i-col>
				<i-col span="1">
					查询：
				</i-col>
				<i-col span="6">
					* 录入日期&nbsp;&nbsp;
					<Date-picker v-model.lazy="qcdate_filter" :options="qcdate_filter_options" @on-change="wbglgets(pagecurrent, pagelast);onselectchange1();" type="daterange" size="small" style="width:200px"></Date-picker>
				</i-col>
				<i-col span="4">
					网板部番&nbsp;&nbsp;
					<i-input v-model.lazy="wangbanbufan_filter" @on-change="wbglgets(pagecurrent, pagelast)" @on-keyup="wangbanbufan_filter=wangbanbufan_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="3">
					编号&nbsp;&nbsp;
					<i-input v-model.lazy="bianhao_filter" @on-change="wbglgets(pagecurrent, pagelast)" @on-keyup="bianhao_filter=bianhao_filter.toUpperCase()" size="small" clearable style="width: 90px"></i-input>
				</i-col>
				<i-col span="4">
					特殊工艺&nbsp;&nbsp;
					<i-input v-model.lazy="teshugongyi_filter" @on-change="wbglgets(pagecurrent, pagelast)" @on-keyup="teshugongyi_filter=teshugongyi_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
				</i-col>
				<i-col span="4">
				&nbsp;
				</i-col>
			</i-row>

			<br><br>

			<i-row :gutter="16">
				<br>
				<i-col span="2">
					@hasanyrole('role_smt_wbgl_write|role_super_admin')
					<Poptip confirm title="确定要删除选择的数据吗？" placement="right-start" @on-ok="ondelete()" @on-cancel="" transfer="true">
						<i-button :disabled="boo_delete" type="warning" size="small" icon="ios-trash-outline">删除</i-button>
					</Poptip>
					@endhasanyrole
					&nbsp;<br>&nbsp;
				</i-col>
				<i-col span="8">
					导出：&nbsp;&nbsp;
					<Poptip confirm title="确定要导出后台数据吗？" placement="right-start" @on-ok="exportData_db" @on-cancel="" transfer="true">
						<i-button type="default" size="small" icon="ios-download-outline">导出后台数据</i-button>
					</Poptip>
				</i-col>
				<i-col span="10">
					&nbsp;
				</i-col>
				<i-col span="4">
					<!-- &nbsp;&nbsp;&nbsp;<strong>不良件数小计：@{{ buliangjianshuheji.toLocaleString() }} </strong>&nbsp;&nbsp; -->
				</i-col>
			</i-row>

			<i-row :gutter="16">
				<i-col span="24">
					<i-table ref="table1" height="460" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange1(selection)"></i-table>
					<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total show-elevator></Page><br><br>
				</i-col>
			</i-row>

			<Modal v-model="modal_qcreport_edit" @on-ok="wbgl_edit_ok" ok-text="保存" title="编辑 - 网板记录" width="640">
				<div style="text-align:left">
				<p>
						创建时间：@{{ created_at_edit }}
						
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						更新时间：@{{ updated_at_edit }}
					
					</p><br>

					<p>

						网板部番：@{{ wangbanbufan_edit }}

						&nbsp;&nbsp;&nbsp;&nbsp;

						网板作成日期：@{{ wangbanzuochengriqi_edit }}

						&nbsp;&nbsp;&nbsp;&nbsp;

						机种名：@{{ jizhongming_edit }}
					
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						品名：@{{ pinming_edit }}
						
					</p>
					
					<Divider></Divider>
							
					<!--<span v-for="(item, index) in piliangbianji">-->
					<p>

						张力1&nbsp;&nbsp;
						<Input-number v-model="zhangli1_edit" :min="1" size="small" style="width: 60px"></Input-number>
						&nbsp;&nbsp;&nbsp;&nbsp;

						张力2&nbsp;&nbsp;
						<Input-number v-model="zhangli2_edit" :min="1" size="small" style="width: 60px"></Input-number>
						&nbsp;&nbsp;&nbsp;&nbsp;

						张力3&nbsp;&nbsp;
						<Input-number v-model="zhangli3_edit" :min="1" size="small" style="width: 60px"></Input-number>
						&nbsp;&nbsp;&nbsp;&nbsp;

						张力4&nbsp;&nbsp;
						<Input-number v-model="zhangli4_edit" :min="1" size="small" style="width: 60px"></Input-number>
						&nbsp;&nbsp;&nbsp;&nbsp;

						张力5&nbsp;&nbsp;
						<Input-number v-model="zhangli5_edit" :min="1" size="small" style="width: 60px"></Input-number>

						&nbsp;&nbsp;&nbsp;&nbsp;
					
					</p><br>
					<!--</span>-->
					
					&nbsp;
				
				</div>	
			</Modal>


		</Tab-pane>



	</Tabs>
	
</div>

<my-passwordchange></my-passwordchange>

@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	components: {
		'my-passwordchange': httpVueLoader("{{ asset('components/my-passwordchange.vue') }}")
	},
	data: {
		// 是否全屏
		isfullscreen: false,

		// 修改密码界面
		modal_password_edit: false,

		// tabs索引
		currenttabs: 0,
		currentsubtabs: 0,


		// 扫描
		wangbanbufan: '', // 84-36240Z01-A
		pinming: '', // MAIN-RA
		jizhongming: '',
		xilie: '',
		wangbanzuochengriqi: '',
		wangbanbianhao: '',
		bianhao: '',
		wangbanhoudu: '',
		teshugongyi: '',
		zhangli1: 0,
		zhangli2: 0,
		zhangli3: 0,
		zhangli4: 0,
		zhangli5: 0,







		// 批量录入
		piliangluru: [
			{
				jianchajileixing: '',
				buliangneirong: '',
				weihao: '',
				shuliang: '',
				jianchazhe: ''
			},
		],
		piliangluru_keep: false,
		

		lotshu: '',
		
		// 批量录入项
		piliangluruxiang: 1,
		
		//工序
		gongxu: '',
		option_gongxu: [
			{value: 'A', label: 'A'},
			{value: 'B', label: 'B'},
			{value: 'CP', label: 'CP'},
			{value: 'RB', label: 'RB'},
			{value: 'RD', label: 'RD'},
			{value: 'RF', label: 'RF'},
		],
		
		// 点/枚
		dianmei: '',
		
		// 枚数
		meishu: '',
		meishu_max: '',
		
		// 检查机类型
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
		xianti: '',
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
		option_banci: [
			{
				value: 'A',
				label: 'A'
			},
			// {
			// 	value: 'A-2',
			// 	label: 'A-2'
			// },
			// {
			// 	value: 'A-3',
			// 	label: 'A-3'
			// },
			{
				value: 'B',
				label: 'B'
			},
			// {
			// 	value: 'B-2',
			// 	label: 'B-2'
			// },
			// {
			// 	value: 'B-3',
			// 	label: 'B-3'
			// }
		],
		
		// 不良内容
		// select_buliangneirong: '',
		option_buliangneirong1: [
			{value: '连焊', label: '连焊'}, {value: '引脚焊锡量少', label: '引脚焊锡量少'},
			{value: 'CHIP部品焊锡少', label: 'CHIP部品焊锡少'}, {value: '焊锡球', label: '焊锡球'}
		],
		option_buliangneirong2: [
			{value: '1005部品浮起.竖立', label: '1005部品浮起.竖立'}, {value: 'CHIP部品横立', label: 'CHIP部品横立'},
			{value: '部品浮起.竖立', label: '部品浮起.竖立'}, {value: '欠品', label: '欠品'},
			{value: '焊锡未熔解', label: '焊锡未熔解'}, {value: '位置偏移', label: '位置偏移'},
			{value: '部品打反', label: '部品打反'}, {value: '部品错误', label: '部品错误'},
			{value: '多余部品', label: '多余部品'}
		],
		option_buliangneirong3: [
			{value: '异物', label: '异物'},
		],
		option_buliangneirong4: [
			{value: '极性错误', label: '极性错误'},{value: '炉后部品破损', label: '炉后部品破损'},
			{value: '引脚弯曲', label: '引脚弯曲'},{value: '基板/部品变形后引脚浮起', label: '基板/部品变形后引脚浮起'},
		],
		option_buliangneirong5: [
			{value: '引脚不上锡', label: '引脚不上锡'},{value: '基板不上锡', label: '基板不上锡'},
			{value: 'CHIP部品不上锡', label: 'CHIP部品不上锡'},{value: '基板不良', label: '基板不良'},
			{value: '部品不良', label: '部品不良'}
		],
		option_buliangneirong6: [
			{value: '其他', label: '其他'},
		],

		// 品名
		option_pinming: [
			{value: 'MAIN', label: 'MAIN'},
			{value: 'HVAC',	label: 'HVAC'},
			{value: 'KEY',	label: 'KEY'},
			{value: 'AUDIO', label: 'AUDIO'},
			{value: 'VIDEO', label: 'VIDEO'},
			{value: 'SW', label: 'SW'},
			{value: 'FRONT', label: 'FRONT'},
			{value: 'ODMD', label: 'ODMD'},
			{value: 'MIC', label: 'MIC'},
			{value: 'DIGITAL', label: 'DIGITAL'}
		],
		
		// 检查者
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
		
		// 表头1
		tablecolumns1: [
			@hasanyrole('role_smt_wbgl_write|role_super_admin')
			{
				type: 'selection',
				width: 60,
				align: 'center',
				fixed: 'left'
			},
			@endhasanyrole
			{
				type: 'index',
				align: 'center',
				width: 70,
				align: 'center',
				fixed: 'left',
				indexMethod: (row) => {
					return row._index + 1 + vm_app.pagepagesize * (vm_app.pagecurrent - 1)
				}
			},
			{
				title: '网板部番',
				key: 'wangbanbufan',
				align: 'center',
				width: 140,
			},
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 110,
			},
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 110,
			},
			{
				title: '系列',
				key: 'xilie',
				align: 'center',
				width: 100
			},
			{
				title: '网板作成日期',
				key: 'wangbanzuochengriqi',
				align: 'center',
				width: 110,
				render: (h, params) => {
					return h('div', [
						params.row.wangbanzuochengriqi  ? params.row.wangbanzuochengriqi.substring(0, 10) : ''
					]);
				}
			},
			{
				title: '网板编号',
				key: 'wangbanbianhao',
				align: 'center',
				width: 100,
			},
			{
				title: '编号',
				key: 'bianhao',
				align: 'center',
				width: 70,
			},
			{
				title: '网板厚度',
				key: 'wangbanhoudu',
				align: 'center',
				width: 100,
			},
			{
				title: '特殊工艺',
				key: 'teshugongyi',
				align: 'center',
				width: 120,
			},
			{
				title: '张力1',
				key: 'zhangli1',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						params.row.zhangli1.toLocaleString()
					]);
				}
			},
			{
				title: '张力2',
				key: 'zhangli2',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						params.row.zhangli2.toLocaleString()
					]);
				}
			},
			{
				title: '张力3',
				key: 'zhangli3',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						params.row.zhangli3.toLocaleString()
					]);
				}
			},
			{
				title: '张力4',
				key: 'zhangli4',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						params.row.zhangli4.toLocaleString()
					]);
				}
			},
			{
				title: '张力5',
				key: 'zhangl5',
				align: 'center',
				width: 70,
				render: (h, params) => {
					return h('div', [
						params.row.zhangli5.toLocaleString()
					]);
				}
			},
			{
				title: '录入者',
				key: 'luruzhe',
				align: 'center',
				width: 90,
			},
			{
				title: '编辑者',
				key: 'bianjizhe',
				align: 'center',
				width: 90,
			},


			@hasanyrole('role_smt_wbgl_write|role_super_admin')
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
									vm_app.wbgl_edit(params.row)
								}
							}
						}, '编辑'),
					]);
				},
				fixed: 'right'
			},
			@endhasanyrole
			// {
			// 	title: '创建日期',
			// 	key: 'created_at',
			// 	align: 'center',
			// 	width: 160,
			// },
			// {
			// 	title: '更新日期',
			// 	key: 'updated_at',
			// 	align: 'center',
			// 	width: 160,
			// },
		],
		tabledata1: [],
		tableselect1: [],
		
		// 表头2
		tablecolumns2: [
 			{
				title: '作成日期',
				key: 'jianchariqi',
				align: 'center',
				width: 100,
				fixed: 'left',
				render: (h, params) => {
					return h('div', [
						params.row.jianchariqi.substring(0,10)
					]);
				}
			},
			{
				title: 'SMT-1',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu1',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu1.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu1',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu1.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu1',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu1.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm1',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm1.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-2',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu2',
						align: 'center',
						width: 110,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu2.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu2',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu2.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu2',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu2.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm2',
						className: 'table-info-column',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm2.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-3',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu3',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu3.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu3',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu3.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu3',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu3.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm3',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm3.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-4',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu4',
						align: 'center',
						width: 110,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu4.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu4',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu4.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu4',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu4.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm4',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.ppm4.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-5',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu5',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu5.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu5',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu5.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu5',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu5.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm5',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm5.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-6',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu6',
						align: 'center',
						width: 110,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu6.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu6',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu6.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu6',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu6.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm6',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.ppm6.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-7',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu7',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu7.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu7',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu7.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu7',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu7.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm7',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm7.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-8',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu8',
						align: 'center',
						width: 110,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu8.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu8',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu8.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu8',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu8.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm8',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.ppm8.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-9',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu9',
						align: 'center',
						width: 110,
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu9.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu9',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu9.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu9',
						align: 'center',
						width: 90,
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu9.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm9',
						align: 'center',
						width: 100,
						render: (h, params) => {
							return h('div', [
								params.row.ppm9.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: 'SMT-10',
				align: 'center',
				children: [
					{
						title: '合计点数',
						key: 'hejidianshu10',
						align: 'center',
						width: 110,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejidianshu10.toLocaleString()
							]);
						}
					},
					{
						title: '不良件数',
						key: 'buliangjianshu10',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.buliangjianshu10.toLocaleString()
							]);
						}
					},
					{
						title: '合计台数',
						key: 'hejitaishu10',
						align: 'center',
						width: 90,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.hejitaishu10.toLocaleString()
							]);
						}
					},
					{
						title: 'PPM',
						key: 'ppm10',
						align: 'center',
						width: 100,
						className: 'table-info-column',
						render: (h, params) => {
							return h('div', [
								params.row.ppm10.toLocaleString()
							]);
						}
					},
				]
			},
			{
				title: '合计点数求和',
				key: 'hejidianshuqiuhe',
				align: 'center',
				width: 110,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '合计点数'),
						h('br', {
						}, ''),
						h('span', {
						}, '求和')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.hejidianshuqiuhe.toLocaleString()
					]);
				}
			},
			{
				title: '不良件数求和',
				key: 'buliangjianshuqiuhe',
				align: 'center',
				width: 100,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '不良件数'),
						h('br', {
						}, ''),
						h('span', {
						}, '求和')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.buliangjianshuqiuhe.toLocaleString()
					]);
				}
			},
			{
				title: '合计台数求和',
				key: 'hejitaishuqiuhe',
				align: 'center',
				width: 100,
				renderHeader: (h, params) => {
					return h('div', [
						h('span', {
						}, '合计台数'),
						h('br', {
						}, ''),
						h('span', {
						}, '求和')
					]);
				},
				render: (h, params) => {
					return h('div', [
						params.row.hejitaishuqiuhe.toLocaleString()
					]);
				}
			},
			{
				title: 'PPM',
				key: 'ppm',
				align: 'center',
				width: 100,
				render: (h, params) => {
					return h('div', [
						params.row.ppm.toLocaleString()
					]);
				}
			},
		],
		tabledata2: [],
		tableselect2: [],
		
		
		// 日期范围过滤
		qcdate_filter: [], //new Date(),
		qcdate_filter_options: {
			shortcuts: [
				{
					text: '今天',
					value () {
						return [new Date(), new Date()];
					},
					// onClick: (picker) => {
					// 	this.$Message.info('Click today');
					// },
				},
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

		// 编号过滤
		bianhao_filter: '',

		// 网板部番过滤
		wangbanbufan_filter: '',

		// 特殊工艺过滤
		teshugongyi_filter: '',
		
		// 删除disabled
		boo_delete: true,
		
		// dailyproductionreport 暂未用到
		saomiao_data: '',
		
		// 不良件数小计
		buliangjianshuheji: 0,

		// 统计日期过滤（年）
		tongji_date_filter: '',
		
		disabled_chart1: false,
		disabled_chart2: false,
		disabled_chart3: false,

		// echarts ajax使用 这个才是实际使用的
		// chart1_type: 'bar',
		
		// chart1_option_tooltip_show: true,
		
		// chart1_option_legend_data: ['不适合件数合计', '合计点数', 'PPM'],
		chart1_option_legend_data: ['不良件数', '合计点数', 'PPM'],
		
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
		chart2_option_title_text: '按不良内容统计不良占有率',
		chart2_option_legend_data: [
			'连焊','引脚焊锡量少','CHIP部品焊锡少','焊锡球',
			'1005部品浮起.竖立','CHIP部品横立','部品浮起.竖立','欠品','焊锡未熔解','位置偏移','部品打反','部品错误','多余部品',
			'异物',
			'极性错误','炉后部品破损','引脚弯曲','基板/部品变形后引脚浮起',
			'引脚不上锡','基板不上锡','CHIP部品不上锡','基板不良','部品不良',
			'其他',
		],
		
		chart2_option_series_data: [
			{value:335, name:'连焊'},
			{value:310, name:'引脚焊锡量少'},
			{value:335, name:'CHIP部品焊锡少'},
			{value:310, name:'焊锡球'},
			{value:234, name:'1005部品浮起.竖立'},
			{value:135, name:'CHIP部品横立'},
			{value:154, name:'部品浮起.竖立'},
			{value:335, name:'欠品'},
			{value:310, name:'焊锡未熔解'},
			{value:234, name:'位置偏移'},
			{value:236, name:'部品打反'},
			{value:274, name:'部品错误'},
			{value:294, name:'多余部品'},
			{value:334, name:'异物'},
			{value:134, name:'极性错误'},
			{value:214, name:'炉后部品破损'},
			{value:24, name:'引脚弯曲'},
			{value:68, name:'基板/部品变形后引脚浮起'},
			{value:32, name:'引脚不上锡'},
			{value:99, name:'基板不上锡'},
			{value:165, name:'CHIP部品不上锡'},
			{value:256, name:'基板不良'},
			{value:290, name:'部品不良'},
			{value:50, name:'其他'},
		],
		
		chart2_option_title_text_huizong: '按不良类别',
		
		chart2_option_series_data_huizong: [
			{value:335, name:'印刷系'},
			{value:679, name:'装着系'},
			{value:679, name:'异物系'},
			{value:679, name:'人系'},
			{value:679, name:'部品系'},
			{value:679, name:'其他系'},
			// {value:1548, name:'搜索引擎', selected:true}
		],
		
		// chart3
		chart3_option_xAxis_data: ['FY17平均','4月','5月','6月','7月','8月','9月','10月','11月','12月','1月','2月','3月',],
		
		chart3_option_title_text: '按不良内容统计不良占有率',
		chart3_option_legend_data: [
			'连焊','引脚焊锡量少','CHIP部品焊锡少','焊锡球',
			'1005部品浮起.竖立','CHIP部品横立','部品浮起.竖立','欠品','焊锡未熔解','位置偏移','部品打反','部品错误','多余部品',
			'异物',
			'极性错误','炉后部品破损','引脚弯曲','基板/部品变形后引脚浮起',
			'引脚不上锡','基板不上锡','CHIP部品不上锡','基板不良','部品不良',
			'其他',
		],
		// 以下按不良内容设定数组，下标24
		chart3_option_series_data: [
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
			[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0]
		],

		chart3_option_series_data_huizong: [0,0,0,0,0,0,0,0,0,0,0,0,0],
		chart3_option_series_data_hejidianshu: [0,0,0,0,0,0,0,0,0,0,0,0,0],
		chart3_option_series_data_ppm: [],
		
		disabled_create: false,

		//分页
		pagecurrent: 1,
		pagetotal: 0,
		pagepagesize: 5,
		pagelast: 1,
		
		// 编辑
		modal_qcreport_edit: false,
		modal_qcreport_edit_sub: false,
		id_edit: '',
		subid_edit: '',

		wangbanbufan_edit: '',
		wangbanzuochengriqi_edit: '',
		jizhongming_edit: '',
		pinming_edit: '',
		gongxu_edit: '',
		xilie_edit: '',
		bianhao_edit: '',
		wangbanhoudu_edit: '',
		teshugongyi_edit: '',
		zhangli1_edit: '',
		zhangli2_edit: '',
		zhangli3_edit: '',
		zhangli4_edit: '',
		zhangli5_edit: '',
		luruzhe_edit: '',
		bianjizhe_edit: '',

		created_at_edit: '',
		updated_at_edit: '',


		// jianchajileixing_edit: '',
		// buliangneirong_edit: '',
		// weihao_edit: '',
		// shuliang_edit: [0, 0], //第一下标为原始值，第二下标为变化值
		// jianchazhe_edit: '',
		// dianmei_edit: '',
		// meishu_edit: '',
		// hejidianshu_edit: '',
		// bushihejianshuheji_edit: '',
		// ppm_edit: '',
		
		// 追加
		modal_qcreport_append: false,
		id_append: '',
		jizhongming_append: '',
		pinming_append: '',
		gongxu_append: '',
		created_at_append: '',
		updated_at_append: '',
		jianchajileixing_append: '',
		buliangneirong_append: '',
		weihao_append: '',
		shuliang_append: ['', ''], //第一下标为原始值，第二下标为变化值
		jianchazhe_append: '',
		count_of_buliangxinxi_append: 0,

		// 上传，批量导入
		file: null,
		loadingStatus: false,
		uploaddisabled: false,

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
		
		// 切换当前页
		oncurrentpagechange (currentpage) {
			this.wbglgets(currentpage, this.pagelast);
		},
		
		// 把laravel返回的结果转换成select能接受的格式
		json2select (value) {
			var arr = value.split(/[\s\n]/);
			var arr_result = [];

			arr.map(function (v, i) {
				arr_result.push({ value: v, label: v });
			});

			return arr_result;
		},
		
		
		configgets () {
			var _this = this;

			var url = "{{ route('smt.configgetswbgl') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					response.data.map(function (v, i) {
						
						if (v.name == 'xianti') {
							_this.option_xianti = _this.json2select(v.value);
						}
						else if (v.name == 'banci') {
							_this.option_banci = _this.json2select(v.value);
						}
						else if (v.name == 'gongxu') {
							_this.option_gongxu = _this.json2select(v.value);
						}
						else if (v.name == 'jianchajileixing') {
							_this.option_jianchajileixing = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong1') {
							_this.option_buliangneirong1 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong2') {
							_this.option_buliangneirong2 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong3') {
							_this.option_buliangneirong3 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong4') {
							_this.option_buliangneirong4 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong5') {
							_this.option_buliangneirong5 = _this.json2select(v.value);
						}
						else if (v.name == 'buliangneirong6') {
							_this.option_buliangneirong6 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe1') {
							_this.option_jianchazhe1 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe2') {
							_this.option_jianchazhe2 = _this.json2select(v.value);
						}
						else if (v.name == 'jianchazhe3') {
							_this.option_jianchazhe3 = _this.json2select(v.value);
						}
						else if (v.name == 'pinming') {
							_this.option_pinming = _this.json2select(v.value);
						}
					
					});

				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},		
		
		// wbgl列表
		wbglgets (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var bianhao_filter = _this.bianhao_filter;
			var wangbanbufan_filter = _this.wangbanbufan_filter;
			var teshugongyi_filter = _this.teshugongyi_filter;
			// var pinming_filter = _this.pinming_filter;
			// var gongxu_filter = _this.gongxu_filter;
			// var buliangneirong_filter = _this.buliangneirong_filter;

			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				
				// 日期范围优先
				_this.tabledata1 = [];
				// if (xianti_filter == '' && banci_filter == '' && jizhongming_filter == '' &&  pinming_filter == '' && gongxu_filter == '' && buliangneirong_filter == '') {
				// if (xianti_filter == '' || xianti_filter == undefined && banci_filter == '' || banci_filter != undefined && jizhongming_filter == '' || jizhongming_filter == undefined &&  pinming_filter == '' || pinming_filter == undefined && gongxu_filter == '' || gongxu_filter == undefined && buliangneirong_filter == '' || buliangneirong_filter == undefined) {
				// 	|| xianti_filter != undefined || banci_filter != undefined || jizhongming_filter != undefined ||  pinming_filter != undefined || gongxu_filter != undefined || buliangneirong_filter != undefined) {
					// _this.warning(false, '警告', '请先选择日期范围！');
				// }

				_this.pagecurrent = 1;
				_this.pagetotal = 0;
				_this.pagelast = 1;

				_this.warning(false, '警告', '请先选择日期范围！');
				return false;

				// 日期范围不需要优先
				/*
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
					qcdate_filter = [start, end];
				}
				*/
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];
			
			var url = "{{ route('smt.wbgl.wbglgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					qcdate_filter: qcdate_filter,
					bianhao_filter: bianhao_filter,
					wangbanbufan_filter: wangbanbufan_filter,
					teshugongyi_filter: teshugongyi_filter,
					// pinming_filter: pinming_filter,
					// gongxu_filter: gongxu_filter,
					// buliangneirong_filter: buliangneirong_filter,
				}
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					// console.log(response.data);return false;

					_this.pagecurrent = response.data.current_page;
					_this.pagetotal = response.data.total;
					_this.pagelast = response.data.last_page;
					
					_this.tabledata1 = response.data.data;
					
					// console.log(_this.tabledata1);
					
				} else {
					_this.tabledata1 = [];
				}
				
				// 合计
				// _this.buliangjianshuheji = 0;
				// for (var i in _this.tabledata1) {
				// 	_this.buliangjianshuheji += _this.tabledata1[i].shuliang;
				// }
				
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
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

		// 编号远程筛选
		onchange_bianhao () {
			var _this = this;

			if (_this.bianhao.length == 3 || _this.bianhao.length == 4) {

				var url = "{{ route('smt.wbgl.bianhaogets') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						bianhao: _this.bianhao,
					}
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						// console.log(response.data);return false;

						_this.wangbanbufan = response.data.wangbanbufan;
						_this.pinming = response.data.pinming;
						_this.jizhongming = response.data.jizhongming;
						_this.xilie = response.data.xilie;
						_this.wangbanzuochengriqi = response.data.wangbanzuochengriqi;
						_this.wangbanbianhao = response.data.wangbanbianhao;
						_this.wangbanhoudu = response.data.wangbanhoudu;
						_this.teshugongyi = response.data.teshugongyi;
						
					} else {
						_this.wangbanbufan = '';
						_this.pinming = '';
						_this.jizhongming = '';
						_this.xilie = '';
						_this.wangbanzuochengriqi = '';
						_this.wangbanbianhao = '';
						_this.wangbanhoudu = '';
						_this.teshugongyi = '';
					}
					
				})
				.catch(function (error) {
					_this.loadingbarerror();
					_this.error(false, 'Error', error);
				})

			} else {
				_this.wangbanbufan = '';
				_this.pinming = '';
				_this.jizhongming = '';
				_this.xilie = '';
				_this.wangbanzuochengriqi = '';
				_this.wangbanbianhao = '';
				_this.wangbanhoudu = '';
				_this.teshugongyi = '';
			}

		},
		

		// 加载扫描相应信息（暂未使用）
		load_saomiao: function () {
			var _this = this;
			if (_this.saomiao.trim() == '') {
				_this.saomiao = '';
				return false;
			}
			
			var saomiao = _this.saomiao;
			
			var url = "{{ route('smt.qcreport.getsaomiao') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					saomiao: _this.saomiao
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
					_this.saomiao_data = response.data;
				} else {
					_this.saomiao_data = '';
					_this.warning(false, '警告', '输入内容不正确！未找到相应记录！');
					_this.$refs.saomiao.focus();
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
			})				
		},
		
		// onclear
		onclear() {
			var _this = this;

			_this.wangbanbufan = '';
			_this.pinming = '';
			_this.jizhongming = '';
			_this.xilie = '';
			_this.wangbanzuochengriqi = '';
			_this.wangbanbianhao = '';
			_this.bianhao = '';
			_this.wangbanhoudu = '';
			_this.teshugongyi = '';
			_this.zhangli1 = 0;
			_this.zhangli2 = 0;
			_this.zhangli3 = 0;
			_this.zhangli4 = 0;
			_this.zhangli5 = 0;

			// _this.$refs.saomiao.focus();
			_this.$refs.ref_bianhao.focus();
		},
		
		// oncreate
		oncreate () {
			var _this = this;

			_this.disabled_create = true;

			var wangbanbufan = _this.wangbanbufan;
			var pinming = _this.pinming;
			var jizhongming = _this.jizhongming;
			var xilie = _this.xilie;
			var wangbanzuochengriqi = _this.wangbanzuochengriqi;
			var wangbanbianhao = _this.wangbanbianhao;
			var bianhao = _this.bianhao;
			var wangbanhoudu = _this.wangbanhoudu;
			var teshugongyi = _this.teshugongyi;
			var zhangli1 = _this.zhangli1;
			var zhangli2 = _this.zhangli2;
			var zhangli3 = _this.zhangli3;
			var zhangli4 = _this.zhangli4;
			var zhangli5 = _this.zhangli5;


			// 基本信息不能为空
			if (wangbanbufan == '' || wangbanbufan == undefined
				|| jizhongming == '' || jizhongming == undefined
				|| pinming == '' || pinming == undefined) {
				_this.warning(false, '警告', '基本信息输入内容为空或不正确！');
				_this.disabled_create = false;
				return false;
			}


			var url = "{{ route('smt.wbgl.wbglcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				// jianchariqi: jianchariqi.Format("yyyy-MM-dd 00:00:00"),
				wangbanbufan: wangbanbufan,
				pinming: pinming,
				jizhongming: jizhongming,
				xilie: xilie,
				wangbanzuochengriqi: wangbanzuochengriqi.Format("yyyy-MM-dd 00:00:00"),
				wangbanbianhao: wangbanbianhao,
				bianhao: bianhao,
				wangbanhoudu: wangbanhoudu,
				teshugongyi: teshugongyi,
				zhangli1: zhangli1,
				zhangli2: zhangli2,
				zhangli3: zhangli3,
				zhangli4: zhangli4,
				zhangli5: zhangli5,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.onclear();
					_this.success(false, '成功', '记入成功！');
				} else {
					_this.error(false, '失败', '记入失败！');
				}

				window.setTimeout(function () {
					_this.disabled_create = false;
				}, 500);
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				window.setTimeout(function () {
					_this.disabled_create = false;
				}, 1000);
			})
		},		
		
		// ondelete
		ondelete () {
			var _this = this;
			
			var tableselect1 = _this.tableselect1;
			
			if (tableselect1[0] == undefined) return false;

			var url = "{{ route('smt.wbgl.wbgldelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect1: tableselect1
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.boo_delete = true;
					_this.tableselect1 = [];
					_this.wbglgets(_this.pagecurrent, _this.pagelast);
					
					// var t = [];
					// for (var i in tableselect1) {
						// t.push({id: tableselect1[i]});
					// }
					// _this.onselectchange1(t);
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},
		
		
		// exportData_table 当前表数据导出
		exportData_table: function () {
			var _this = this;
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			_this.$refs.table1.exportCsv({
				filename: 'smt_qc_report_currentdata',
				original: false
			});
		},


		// exportData_db 当前表数据导出
		exportData_db () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}

			var queryfilter_datefrom = _this.qcdate_filter[0].Format("yyyy-MM-dd 00:00:00");
			var queryfilter_dateto = _this.qcdate_filter[1].Format("yyyy-MM-dd 23:59:59");
			
			var url = "{{ route('smt.wbgl.wbglexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto;
				
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);

		},
		
		//
		// 生成piliangluru
		piliangluru_generate (counts) {
			if (counts == undefined) counts = 1;
			var len = this.piliangluru.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru.push({value: 'piliangluru'+parseInt(len+i+1)});
					this.piliangluru.push(
						{
							jianchajileixing: '',
							buliangneirong: '',
							weihao: '',
							shuliang: '',
							jianchazhe: ''
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
		chart1_function () {
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
					var myChart = ec.init(document.getElementById('chart1'), 'macarons'); 
					
					var option = {
						title : {
							text: '工程内不良记录（PPM）',
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd'), //'2018.06-2018-07'
							x: 'center'
						},
						tooltip: {
							// show: vm_app.chart1_option_tooltip_show,
							show: true,
							trigger: 'axis'
						},
						legend: {
							data: vm_app.chart1_option_legend_data,
							x: 'left'
						},
						grid: {
							y: 80
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
					var myChart = ec.init(document.getElementById('chart2'), 'macarons'); 
					
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
							orient: 'vertical',
							x: 'left',
							// data:['直达','营销广告','搜索引擎','邮件营销','联盟广告','视频广告','百度','谷歌','必应','其他']
							data: vm_app.chart2_option_legend_data
						},
						toolbox: {
							show: true,
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								// magicType : {
									// show: true, 
									// type: ['pie', 'funnel']
								// },
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						calculable : true,
						series : [
							{
								// name:'访问来源',
								name: vm_app.chart2_option_title_text_huizong,
								type:'pie',
								selectedMode: 'multiple',
								radius: [0, 70],
								center: ['50%', '66%'],
								
								itemStyle: {
									normal: {
										label: {
											position: 'inner',
											distance: 0.6,
											formatter: function (params) {                         
												return (params.percent - 0).toFixed(0) + '%'
											}
										},
										labelLine: {
											show: false
										}
									},
									emphasis: {
										label: {
											show: true,
											formatter: "{b}\n{d}%"
										}
									}
								},
								data: vm_app.chart2_option_series_data_huizong,
								// data:[
									// {value:335, name:'直达'},
									// {value:679, name:'营销广告'},
									// {value:1548, name:'搜索引擎', selected:true}
								// ]
							},
							{
								// name:'访问来源',
								name: vm_app.chart2_option_title_text,
								type:'pie',
								radius : [100, 140],
								center : ['50%', '66%'],
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
								// data:[
									// {value:335, name:'直达'},
									// {value:310, name:'邮件营销'},
									// {value:234, name:'联盟广告'},
									// {value:135, name:'视频广告'},
									// {value:1048, name:'百度'},
									// {value:251, name:'谷歌'},
									// {value:147, name:'必应'},
									// {value:102, name:'其他'}
								// ]
							}
						]
					};
					
			
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},

		chart3_function: function () {
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
				],
				function (ec) {
					// 基于准备好的dom，初始化echarts图表
					var myChart = ec.init(document.getElementById('chart3'), 'macarons'); 
					
					var option = {
						title: {
							text: '按月份对比不良率和PPM',
							x:'center'
						},
						tooltip: {
							trigger: 'axis',
							axisPointer: {            // 坐标轴指示器，坐标轴触发有效
								type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
							}
						},
						legend: {
							// data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎','百度','谷歌','必应','其他']
							data: vm_app.chart3_option_legend_data,
							x: 'left',
							padding: [40,5,5,5]
						},
						grid: {
							y: 120,
						},
						toolbox: {
							show: true,
							// orient: 'vertical',
							orient: 'horizontal',
							x: 'right',
							y: 'top',
							padding: [65, 5, 5, 5],
							feature: {
								mark: {show: true},
								dataView: {show: true, readOnly: false},
								// magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
								restore: {show: true},
								saveAsImage: {show: true}
							}
						},
						calculable: true,
						xAxis: [
							{
								type: 'category',
								// data: ['周一','周二','周三','周四','周五','周六','周日']
								data: vm_app.chart3_option_xAxis_data
							}
						],
						yAxis: [
							{
								type: 'value',
								name: '件数',
								axisLabel: {
									formatter: '{value} 件'
								}
							},
							{
								type: 'value',
								name: 'PPM',
								axisLabel: {
									formatter: '{value} ppm'
								}
							}
						],
						series: [
							{
								name:'连焊',
								type:'bar',
								// barWidth: 30,
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								// data:[620, 732, 701, 734, 1090, 1130, 1120, 620, 732, 701, 734, 620, 732]
								data: vm_app.chart3_option_series_data[0]
							},
							{
								name:'引脚焊锡量少',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								// data:[120, 132, 101, 134, 290, 230, 220, 210, 120, 132, 801, 134, 222]
								data: vm_app.chart3_option_series_data[1]
							},
							{
								name:'CHIP部品焊锡少',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[2]
							},
							{
								name:'焊锡球',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[3]
							},
							{
								name:'1005部品浮起.竖立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[4]
							},
							{
								name:'CHIP部品横立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[5]
							},
							{
								name:'部品浮起.竖立',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[6]
							},
							{
								name:'欠品',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[7]
							},
							{
								name:'焊锡未熔解',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[8]
							},
							{
								name:'位置偏移',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[9]
							},
							{
								name:'部品打反',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[10]
							},
							{
								name:'部品错误',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[11]
							},
							{
								name:'多余部品',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[12]
							},
							{
								name:'异物',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[13]
							},
							{
								name:'极性错误',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[14]
							},
							{
								name:'炉后部品破损',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[15]
							},
							{
								name:'引脚弯曲',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[16]
							},
							{
								name:'基板/部品变形后引脚浮起',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[17]
							},
							{
								name:'引脚不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[18]
							},
							{
								name:'基板不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[19]
							},
							{
								name:'CHIP部品不上锡',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[20]
							},
							{
								name:'基板不良',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[21]
							},
							{
								name:'部品不良',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[22]
							},
							{
								name:'其他',
								type:'bar',
								stack: '不良汇总',
								itemStyle: { normal: {label: {show: true, position: 'inside'}}},
								data: vm_app.chart3_option_series_data[23]
							},
							{
								name:'汇总',
								type:'line',
								stack: '不良汇总',
								itemStyle: {
									normal: {
										label: {
											show: true,
											position: 'top',
											// formatter: function (params) {
												// var d = 0;
												// for (var i = 0, l = option.xAxis[0].data.length; i < l; i++) {
													// if (option.xAxis[0].data[i] == params.name) {
														// return option.series[0].data[i] + params.value;
														// d += option.series[0].data[i];
													// }
												// }
												// return d;
											// },
											textStyle: {
												fontSize: '18',
												fontFamily: '微软雅黑',
												// fontWeight: 'bold'
											}
										},
										lineStyle: {
											type: 'dashed',
											width: 1
										}
									}
								},
								// data:[5060, 6672, 6671, 6674, 10190, 10130, 10110]
								data: vm_app.chart3_option_series_data_huizong
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
								// data: [10.5, 7.2, 7.1, 7.4, 5.9, 13.0, 11.0, 3.8, 7.7, 8.1, 19.0, 11.9, 4.9]
								data: vm_app.chart3_option_series_data_ppm
							}
						]
					};
						
					// 为echarts对象加载数据 
					myChart.setOption(option, false); 
				}
			);
		},
		
		
		// ajax返回后显示图表
		onchart1 () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			var hejidianshu = [];
			var bushihejianshuheji = [];
			var shuliang = [];
			var ppm = [];
			
			var i = 0;
			for (i=0;i<10;i++) {
				hejidianshu[i] = 0;
				bushihejianshuheji[i] = 0;
				shuliang[i] = 0;
				ppm[i] = 0;
			}
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;
			
			// 图表按当前表格中最大记录数重新查询
			
			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.tabledata1 = [];
				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];

			_this.disabled_chart1 = true;
			var url = "{{ route('smt.qcreport.qcreportgetschart1') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				// console.log(response.data.data);return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata1 = response.data.data;
					// console.log(chartdata1);return false;
					chartdata1.map(function (v,j) {
						switch(v.xianti.trim())
						{
							case 'SMT-1':
								i = 0;break;
							case 'SMT-2':
								i = 1;break;
							case 'SMT-3':
								i = 2;break;
							case 'SMT-4':
								i = 3;break;
							case 'SMT-5':
								i = 4;break;
							case 'SMT-6':
								i = 5;break;
							case 'SMT-7':
								i = 6;break;
							case 'SMT-8':
								i = 7;break;
							case 'SMT-9':
								i = 8;break;
							case 'SMT-10':
								i = 9;break;
							default:
							  
						}

						hejidianshu[i] += Number(v.hejidianshu);
						bushihejianshuheji[i] += Number(v.bushihejianshuheji);
						shuliang[i] += Number(v.shuliang);

						// let tt = 0;
						// tt = v.hejidianshu == null || v.hejidianshu == '' ? 0 : v.hejidianshu;
						// // hejidianshu[i] += v.hejidianshu;
						// hejidianshu[i] += tt;
						
						// tt = v.bushihejianshuheji == null || v.bushihejianshuheji == '' ? 0 : v.bushihejianshuheji;
						// // bushihejianshuheji[i] += v.bushihejianshuheji;
						// bushihejianshuheji[i] += tt;

						// tt = v.shuliang == null || v.shuliang == '' ? 0 : v.shuliang;
						// // shuliang[i] += v.shuliang;
						// shuliang[i] += tt;
						// console.log(shuliang);return false;

						// if (hejidianshu[i] == 0) {
							// ppm[i] = 0;
						// } else {
							// ppm[i] = bushihejianshuheji[i] / hejidianshu[i] * 1000000;
						// }
						// ppm[i] += v.ppm;

					});
					// console.log(shuliang);
					// console.log(bushihejianshuheji);

					// console.log(hejidianshu);
					// ppm计算
					hejidianshu.map(function (v,i) {

						// ppm[i] += v.ppm;
						// hejidianshu[i] += v.hejidianshu;
						// bushihejianshuheji[i] += v.bushihejianshuheji;

						if (hejidianshu[i] == 0) {
							ppm[i] = 0;
						} else {
							// ppm[i] = (shuliang[i] / hejidianshu[i] * 1000000).toFixed(2);
							ppm[i] = (bushihejianshuheji[i] / hejidianshu[i] * 1000000).toFixed(2);
						}

					});
					
					// console.log(bushihejianshuheji);
					// console.log(hejidianshu);
					// console.log(ppm);
					// return false;
					
					// bushihejianshuheji
					var a1 = [{
						// name: '不适合件数合计',
						name: '不良件数',
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
						// data: shuliang
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
				
				}

				window.setTimeout(function () {
					_this.disabled_chart1 = false;
				}, 2000);
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},
		
		
		onchart2 () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			// var shuliang = [];
			// for (var i=0;i<24;i++) {
			// 	shuliang[i] = 0;
			// }

			// var shuliang_huizong = [];
			// for (var i=0;i<6;i++) {
			// 	shuliang_huizong[i] = 0;
			// }
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			// 图表按当前表格中最大记录数重新查询
			
			var qcdate_filter = [];

			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.tabledata1 = [];
				_this.warning(false, '警告', '请先选择日期范围！');
				return false;
			} else {
				qcdate_filter =  _this.qcdate_filter;
			}
			
			qcdate_filter = [qcdate_filter[0].Format("yyyy-MM-dd 00:00:00"), qcdate_filter[1].Format("yyyy-MM-dd 23:59:59")];
			
			_this.disabled_chart2 = true;
			var url = "{{ route('smt.qcreport.qcreportgetschart2') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				// console.log(response.data);return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata2 = response.data;			
			
			
					var shuliang = [];
					for (var i=0;i<24;i++) {
						shuliang[i] = 0;
					}

					var shuliang_huizong = [];
					for (var i=0;i<6;i++) {
						shuliang_huizong[i] = 0;
					}

					chartdata2.map(function (v,j) {
						switch(v.buliangneirong)
						{
							case '连焊':
								i = 0;j = 0;break;
							case '引脚焊锡量少':
								i = 1;j = 0;break;
							case 'CHIP部品焊锡少':
								i = 2;j = 0;break;
							case '焊锡球':
								i = 3;j = 0;break;
							case '1005部品浮起.竖立':
								i = 4;j = 1;break;
							case 'CHIP部品横立':
								i = 5;j = 1;break;
							case '部品浮起.竖立':
								i = 6;j = 1;break;
							case '欠品':
								i = 7;j = 1;break;
							case '焊锡未熔解':
								i = 8;j = 1;break;
							case '位置偏移':
								i = 9;j = 1;break;
							case '部品打反':
								i = 10;j = 1;break;
							case '部品错误':
								i = 11;j = 1;break;
							case '多余部品':
								i = 12;j = 1;break;
							case '异物':
								i = 13;j = 2;break;
							case '极性错误':
								i = 14;j = 3;break;
							case '炉后部品破损':
								i = 15;j = 3;break;
							case '引脚弯曲':
								i = 16;j = 3;break;
							case '基板/部品变形后引脚浮起':
								i = 17;j = 3;break;
							case '引脚不上锡':
								i = 18;j = 4;break;
							case '基板不上锡':
								i = 19;j = 4;break;
							case 'CHIP部品不上锡':
								i = 20;j = 4;break;
							case '基板不良':
								i = 21;j = 4;break;
							case '部品不良':
								i = 22;j = 4;break;
							case '其他':
								i = 23;j = 5;break;
							default:
							  
						}
						// bushihejianshuheji[i] += v.bushihejianshuheji;
						// let tt = 0;
						// tt = v.shuliang == null || v.shuliang == '' ? 0 : v.shuliang;
						shuliang[i] += Number(v.shuliang);
						shuliang_huizong[j] += Number(v.shuliang);
						// shuliang[i] += tt;
						// shuliang_huizong[j] += tt;
					});
					// console.log(shuliang);return false;
					// var data = 
					// [
					// 	{value: shuliang[0], name:'连焊'},
					// 	{value: shuliang[1], name:'引脚焊锡量少'},
					// 	{value: shuliang[2], name:'CHIP部品焊锡少'},
					// 	{value: shuliang[3], name:'焊锡球'},
					// 	{value: shuliang[4], name:'1005部品浮起.竖立'},
					// 	{value: shuliang[5], name:'CHIP部品横立'},
					// 	{value: shuliang[6], name:'部品浮起.竖立'},
					// 	{value: shuliang[7], name:'欠品'},
					// 	{value: shuliang[8], name:'焊锡未熔解'},
					// 	{value: shuliang[9], name:'位置偏移'},
					// 	{value: shuliang[10], name:'部品打反'},
					// 	{value: shuliang[11], name:'部品错误'},
					// 	{value: shuliang[12], name:'多余部品'},
					// 	{value: shuliang[13], name:'异物'},
					// 	{value: shuliang[14], name:'极性错误'},
					// 	{value: shuliang[15], name:'炉后部品破损'},
					// 	{value: shuliang[16], name:'引脚弯曲'},
					// 	{value: shuliang[17], name:'基板/部品变形后引脚浮起'},
					// 	{value: shuliang[18], name:'引脚不上锡'},
					// 	{value: shuliang[19], name:'基板不上锡'},
					// 	{value: shuliang[20], name:'CHIP部品不上锡'},
					// 	{value: shuliang[21], name:'基板不良'},
					// 	{value: shuliang[22], name:'部品不良'},
					// 	{value: shuliang[23], name:'其他'},
					// ];

					var data = [];

					if (shuliang[0]!=0) {data.push({value: shuliang[0], name:'连焊'});}
					if (shuliang[1]!=0) {data.push({value: shuliang[1], name:'引脚焊锡量少'});}
					if (shuliang[2]!=0) {data.push({value: shuliang[2], name:'CHIP部品焊锡少'});}
					if (shuliang[3]!=0) {data.push({value: shuliang[3], name:'焊锡球'});}
					if (shuliang[4]!=0) {data.push({value: shuliang[4], name:'1005部品浮起.竖立'});}
					if (shuliang[5]!=0) {data.push({value: shuliang[5], name:'CHIP部品横立'});}
					if (shuliang[6]!=0) {data.push({value: shuliang[6], name:'部品浮起.竖立'});}
					if (shuliang[7]!=0) {data.push({value: shuliang[7], name:'欠品'});}
					if (shuliang[8]!=0) {data.push({value: shuliang[8], name:'焊锡未熔解'});}
					if (shuliang[9]!=0) {data.push({value: shuliang[9], name:'位置偏移'});}
					if (shuliang[10]!=0) {data.push({value: shuliang[10], name:'部品打反'});}
					if (shuliang[11]!=0) {data.push({value: shuliang[11], name:'部品错误'});}
					if (shuliang[12]!=0) {data.push({value: shuliang[12], name:'多余部品'});}
					if (shuliang[13]!=0) {data.push({value: shuliang[13], name:'异物'});}
					if (shuliang[14]!=0) {data.push({value: shuliang[14], name:'极性错误'});}
					if (shuliang[15]!=0) {data.push({value: shuliang[15], name:'炉后部品破损'});}
					if (shuliang[16]!=0) {data.push({value: shuliang[16], name:'引脚弯曲'});}
					if (shuliang[17]!=0) {data.push({value: shuliang[17], name:'基板/部品变形后引脚浮起'});}
					if (shuliang[18]!=0) {data.push({value: shuliang[18], name:'引脚不上锡'});}
					if (shuliang[19]!=0) {data.push({value: shuliang[19], name:'基板不上锡'});}
					if (shuliang[20]!=0) {data.push({value: shuliang[20], name:'CHIP部品不上锡'});}
					if (shuliang[21]!=0) {data.push({value: shuliang[21], name:'基板不良'});}
					if (shuliang[22]!=0) {data.push({value: shuliang[22], name:'部品不良'});}
					if (shuliang[23]!=0) {data.push({value: shuliang[23], name:'其他'});}

					// console.log(data);return false;
					
					// var data_huizong = 
					// [
					// 	{value: shuliang_huizong[0], name:'印刷系'},
					// 	{value: shuliang_huizong[1], name:'装着系'},
					// 	{value: shuliang_huizong[2], name:'异物系'},
					// 	{value: shuliang_huizong[3], name:'人系'},
					// 	{value: shuliang_huizong[4], name:'部品系'},
					// 	{value: shuliang_huizong[5], name:'其他系'},
					// ];

					var data_huizong = [];
					if (shuliang_huizong[0]!=0) {data_huizong.push({value: shuliang_huizong[0], name:'印刷系'});}
					if (shuliang_huizong[1]!=0) {data_huizong.push({value: shuliang_huizong[1], name:'装着系'});}
					if (shuliang_huizong[2]!=0) {data_huizong.push({value: shuliang_huizong[2], name:'异物系'});}
					if (shuliang_huizong[3]!=0) {data_huizong.push({value: shuliang_huizong[3], name:'人系'});}
					if (shuliang_huizong[4]!=0) {data_huizong.push({value: shuliang_huizong[4], name:'部品系'});}
					if (shuliang_huizong[5]!=0) {data_huizong.push({value: shuliang_huizong[5], name:'其他系'});}
					
					// console.log(data);return false;

					_this.chart2_option_series_data = data;
					_this.chart2_option_series_data_huizong = data_huizong;
					_this.chart2_function();
			
				}

				window.setTimeout(function () {
					_this.disabled_chart2 = false;
				}, 2000);

			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},		
		
		
		onchart3 () {
			var _this = this;

			// 2018-12-31
			var current_datetime = new Date();
			var current_date = current_datetime.getFullYear() + '-12-31';
			
			
			// 2017-01-01
			var last_year = current_datetime.getFullYear() - 1;
			var before_last_year = current_datetime.getFullYear() - 2;
			var last_date = last_year + '-01-01';
			
			// 查询去年到今年的日期范围
			var qcdate_filter = [last_date, current_date];
			
			// 修正表X轴文字，去年“FY2017平均”字样。
			var current_month = current_datetime.getMonth();
			if (current_month > 2) {
				_this.chart3_option_xAxis_data[0] = 'FY' + last_year + '';
			} else {
				_this.chart3_option_xAxis_data[0] = 'FY' + before_last_year + '';
			}
			
			var xianti_filter = _this.xianti_filter;
			var banci_filter = _this.banci_filter;
			var jizhongming_filter = _this.jizhongming_filter;
			var pinming_filter = _this.pinming_filter;
			var gongxu_filter = _this.gongxu_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;
			
			_this.disabled_chart3 = true;
			var url = "{{ route('smt.qcreport.qcreportgetschart3') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					xianti_filter: xianti_filter,
					banci_filter: banci_filter,
					jizhongming_filter: jizhongming_filter,
					pinming_filter: pinming_filter,
					gongxu_filter: gongxu_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				// console.log(response.data);return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var chartdata3 = response.data;
					// console.log(chartdata3);			
			
					_this.chart3_option_series_data = [
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0,0,0,0,0]
					];

					_this.chart3_option_series_data_huizong = [0,0,0,0,0,0,0,0,0,0,0,0,0];
					_this.chart3_option_series_data_hejidianshu = [0,0,0,0,0,0,0,0,0,0,0,0,0];
					
					_this.chart3_option_series_data_ppm = [0,0,0,0,0,0,0,0,0,0,0,0,0];

					// 去年和今年的日期范围
					var dd = new Date();
					var current_year = dd.getFullYear();
					var current_month = dd.getMonth();
					var last_year = dd.getFullYear() - 1;
					var before_last_year = dd.getFullYear() - 2;

					// var last_date_range = [new Date(last_year + '-01-01 00:00:00'), new Date(last_year + '-12-31 23:59:59')];
					// var current_date_range = [new Date(current_year + '-01-01 00:00:00'), new Date(current_year + '-12-31 23:59:59')];

					// 判断是否过了3月
					if (current_month > 2) {
						// 如果当前是2020-04-01，则日期范围为
						// 去年：2019-04-01 ~ 2020-03-31
						// 今年：2020-04-01 ~ 2020-12-31
						var last_date_range = [new Date(last_year + '-04-01 00:00:00'), new Date(current_year + '-03-31 23:59:59')];
						var current_date_range = [new Date(current_year + '-04-01 00:00:00'), new Date(current_year + '-12-31 23:59:59')];
					} else {
						// 如果当前是2020-03-30，则日期范围为
						// 去年：2018-04-01 ~ 2019-03-31
						// 今年：2019-04-01 ~ 2020-03-31
						var last_date_range = [new Date(before_last_year + '-04-01 00:00:00'), new Date(last_year + '-03-31 23:59:59')];
						var current_date_range = [new Date(last_year + '-04-01 00:00:00'), new Date(current_year + '-03-31 23:59:59')];
					}

					// console.log(last_date_range);
					// console.log(current_date_range);
					// return false;

					// console.log('原始：');
					// console.log(chartdata3);return false;

					chartdata3.buliangxinxi.map(function (v,k) {
						switch(v.buliangneirong)
						{
							case '连焊':
								i = 0;break;
							case '引脚焊锡量少':
								i = 1;break;
							case 'CHIP部品焊锡少':
								i = 2;break;
							case '焊锡球':
								i = 3;break;
							case '1005部品浮起.竖立':
								i = 4;break;
							case 'CHIP部品横立':
								i = 5;break;
							case '部品浮起.竖立':
								i = 6;break;
							case '欠品':
								i = 7;break;
							case '焊锡未熔解':
								i = 8;break;
							case '位置偏移':
								i = 9;break;
							case '部品打反':
								i = 10;break;
							case '部品错误':
								i = 11;break;
							case '多余部品':
								i = 12;break;
							case '异物':
								i = 13;break;
							case '极性错误':
								i = 14;break;
							case '炉后部品破损':
								i = 15;break;
							case '引脚弯曲':
								i = 16;break;
							case '基板/部品变形后引脚浮起':
								i = 17;break;
							case '引脚不上锡':
								i = 18;break;
							case '基板不上锡':
								i = 19;break;
							case 'CHIP部品不上锡':
								i = 20;break;
							case '基板不良':
								i = 21;break;
							case '部品不良':
								i = 22;break;
							case '其他':
								i = 23;break;
							default:
								i = 24;	
						}
					
						// 按不良内容汇总数量，共24种
						if (i >= 0 && i < 24) {
							// 按检查日期分类
							var riqi = new Date(v.jianchariqi);
							// var riqi = new Date(v.created_at);
							// var riqi = v.jianchariqi.split('-');

							// 日期在去年的，统一保存到下标为0的数组中
							if (riqi >= last_date_range[0] && riqi <= last_date_range[1]) {
								j = 0;
							
							// 日期在今年的，按月份保存
							} else if (riqi >= current_date_range[0] && riqi <= current_date_range[1]) {
								// console.log(riqi.Format('MM'));
								switch(riqi.Format('MM')) //月份
								{
									case '01':
										j = 10;break;
									case '02':
										j = 11;break;
									case '03':
										j = 12;break;
									case '04':
										j = 1;break; // 注意0下标
									case '05':
										j = 2;break;
									case '06':
										j = 3;break;
									case '07':
										j = 4;break;
									case '08':
										j = 5;break;
									case '09':
										j = 6;break;
									case '10':
										j = 7;break;
									case '11':
										j = 8;break;
									case '12':
										j = 9;break;
									default:
									  
								}
							}
								
							// i为不良内容分类，j为月份
							// console.log(v.shuliang + '|' + i + '|' + j);
							if (v.shuliang != null || v.shuliang != '') {
								_this.chart3_option_series_data[i][j] += Number(v.shuliang);
							
								// 每月份的汇总
								_this.chart3_option_series_data_huizong[j] += Number(v.shuliang);
							}
							
						}

					});
					// console.log(_this.chart3_option_series_data);return false;
					// console.log(_this.chart3_option_series_data_huizong);return false;
					// _this.chart3_function();return false;

					chartdata3.jibenxinxi.map(function (v,k) {
						// 合计点数之和，用于计算总的PPM

						// 按检查日期分类
						var riqi = new Date(v.jianchariqi);

						// 日期在去年的，统一保存到下标为0的数组中
						if (riqi >= last_date_range[0] && riqi <= last_date_range[1]) {
							j = 0;
						
						// 日期在今年的，按月份保存
						} else if (riqi >= current_date_range[0] && riqi <= current_date_range[1]) {
							switch(riqi.Format('MM')) //月份
							{
								case '01':
									j = 10;break;
								case '02':
									j = 11;break;
								case '03':
									j = 12;break;
								case '04':
									j = 1;break; // 注意0下标
								case '05':
									j = 2;break;
								case '06':
									j = 3;break;
								case '07':
									j = 4;break;
								case '08':
									j = 5;break;
								case '09':
									j = 6;break;
								case '10':
									j = 7;break;
								case '11':
									j = 8;break;
								case '12':
									j = 9;break;
								default:
									
							}
						}
						// console.log(v.hejidianshu);
						_this.chart3_option_series_data_hejidianshu[j] += Number(v.hejidianshu);

					});
					// console.log(_this.chart3_option_series_data_hejidianshu);return false;

					// ppm计算
					_this.chart3_option_series_data_huizong.map(function (v,i) {
						if (_this.chart3_option_series_data_hejidianshu[i] == 0) {
							_this.chart3_option_series_data_ppm[i] = 0;
						} else {
							_this.chart3_option_series_data_ppm[i] = (_this.chart3_option_series_data_huizong[i] / _this.chart3_option_series_data_hejidianshu[i] * 1000000).toFixed(2);
						}
					});
					
					_this.chart3_function();
			
				}

				window.setTimeout(function () {
					_this.disabled_chart3 = false;
				}, 2000);

			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},
		
		
		// upload
		handleUpload (file) {
			this.file = file;
			return false;
		},
		upload () {
			this.loadingStatus = true;
			
			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('smt.qcreport.qcreportimport') }}";
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
				console.log(response.data);
				alert(response.data);
			})
			.catch(function (error) {
				this.error(false, 'Error', error);
			})
			
			
			
			setTimeout(() => {
				this.file = null;
				this.loadingStatus = false;
				this.$Message.success('Success')
			}, 1500);
		},
		
		
		//
		onchangegongxu () {
			var _this = this;
			
			var saomiao = _this.saomiao;
			var gongxu = _this.gongxu;
			
			if (saomiao == '' || saomiao == undefined || gongxu == '' || gongxu == undefined ) {
				_this.dianmei = '';
				return false;
			}
			
			var url = "{{ route('smt.qcreport.getsaomiao') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					saomiao: saomiao,
					gongxu: gongxu
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.lotshu = response.data.lotshu;
				_this.meishu_max = response.data.lotshu;

				_this.xianti = response.data.xianti;
				_this.banci = response.data.banci;
				_this.dianmei = response.data.dianmei;

				// 生产日报中的机种生产日期，暂保留，无用（返回但没用上）
				_this.jianchariqi = response.data.jianchariqi;
			})
			.catch(function (error) {
				this.error(false, 'Error', error);
			})
		},
		
		
		// 主编辑前查看
		wbgl_edit (row) {
			var _this = this;
			
			_this.id_edit = row.id;
			_this.wangbanbufan_edit = row.wangbanbufan;
			_this.wangbanzuochengriqi_edit = row.wangbanzuochengriqi.substring(0, 10);
			_this.jizhongming_edit = row.jizhongming;
			_this.pinming_edit = row.pinming;
			_this.gongxu_edit = row.gongxu;
			_this.xilie_edit = row.xilie;
			_this.wangbanbianhao_edit = row.wangbanbianhao;
			_this.bianhao_edit = row.bianhao;
			_this.wangbanhoudu_edit = row.wangbanhoudu;
			_this.teshugongyi_edit = row.teshugongyi;
			_this.zhangli1_edit = row.zhangli1;
			_this.zhangli2_edit = row.zhangli2;
			_this.zhangli3_edit = row.zhangli3;
			_this.zhangli4_edit = row.zhangli4;
			_this.zhangli5_edit = row.zhangli5;
			
			_this.created_at_edit = row.created_at;
			_this.updated_at_edit = row.updated_at;

			// _this.jianchajileixing_edit = row.jianchajileixing;
			// _this.buliangneirong_edit = row.buliangneirong;
			// _this.weihao_edit = row.weihao;
			// _this.shuliang_edit[0] = row.shuliang;
			// _this.shuliang_edit[1] = row.shuliang;
			// _this.jianchazhe_edit = row.jianchazhe;
			// _this.dianmei_edit = row.dianmei;
			// _this.meishu_edit = row.meishu;
			// _this.hejidianshu_edit = row.hejidianshu;
			// _this.bushihejianshuheji_edit = row.bushihejianshuheji;
			// _this.ppm_edit = row.ppm;

			_this.modal_qcreport_edit = true;
		},
		
		

		
		
		// 主编辑后保存
		wbgl_edit_ok() {
			var _this = this;
			
			var id = _this.id_edit;
			// var jizhongming = _this.jizhongming_edit;
			var created_at = _this.created_at_edit;
			var updated_at = _this.updated_at_edit;
			// var jianchajileixing = _this.jianchajileixing_edit;
			// var buliangneirong = _this.buliangneirong_edit;
			// var weihao = _this.weihao_edit;
			// var shuliang = _this.shuliang_edit;
			var zhangli1 = _this.zhangli1_edit;
			var zhangli2 = _this.zhangli2_edit;
			var zhangli3 = _this.zhangli3_edit;
			var zhangli4 = _this.zhangli4_edit;
			var zhangli5 = _this.zhangli5_edit;

			var url = "{{ route('smt.wbgl.wbglupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				// jizhongming: jizhongming,
				created_at: created_at,
				updated_at: updated_at,
				// jianchajileixing: jianchajileixing,
				// buliangneirong: buliangneirong,
				// weihao: weihao,
				// shuliang: shuliang[1],
				// meishu: meishu,
				// hejidianshu: hejidianshu,
				// bushihejianshuheji: bushihejianshuheji,
				zhangli1: zhangli1,
				zhangli2: zhangli2,
				zhangli3: zhangli3,
				zhangli4: zhangli4,
				zhangli5: zhangli5,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.wbglgets(_this.pagecurrent, _this.pagelast);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.id_edit = '';
					_this.jizhongming_edit = '';
					// _this.created_at_edit = '';
					_this.updated_at_edit = '';
					// _this.jianchajileixing_edit = '';
					// _this.buliangneirong_edit = '';
					// _this.weihao_edit = '';
					// _this.shuliang_edit = [0, 0];
					_this.zhangli1_edit = '';
					_this.zhangli2_edit = '';
					_this.zhangli3_edit = '';
					_this.zhangli4_edit = '';
					_this.zhangli5_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请确认录入是否正确！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},
		

		// 不良追加前查看
		qcreport_append (row) {
			var _this = this;
			_this.id_append = row.id;
			_this.jizhongming_append = row.jizhongming;
			_this.pinming_append = row.pinming;
			_this.gongxu_append = row.gongxu;
			_this.created_at_append = row.created_at;
			_this.updated_at_append = row.updated_at;

			// _this.jianchajileixing_append = row.jianchajileixing;
			// _this.buliangneirong_append = row.buliangneirong;
			// _this.weihao_append = row.weihao;
			// _this.shuliang_append[0] = row.shuliang;
			// _this.shuliang_append[1] = row.shuliang;
			// _this.jianchazhe_append = row.jianchazhe;

			if (row.buliangxinxi != null) {
				_this.count_of_buliangxinxi_append = row.buliangxinxi.length;
			} else {
				_this.count_of_buliangxinxi_append = 0;
			}

			_this.modal_qcreport_append = true;

		},

		// 不良追加保存
		qcreport_append_ok () {
			var _this = this;

			var id = _this.id_append;
			var jianchajileixing = _this.jianchajileixing_append;
			var buliangneirong = _this.buliangneirong_append;
			var weihao = _this.weihao_append;
			var shuliang = _this.shuliang_append[1];
			var jianchazhe = _this.jianchazhe_append;
			var count_of_buliangxinxi_append = _this.count_of_buliangxinxi_append;
			
			var flag = true;

			// 任何一行记录，只要有一项填写，就必须都填写
			// if (jianchajileixing == '' || jianchajileixing == undefined) {
			// 	flag = false;
			// } else if (buliangneirong == '' || buliangneirong == undefined) {
			// 	flag = false;
			// } else if (weihao == '' || weihao == undefined) {
			// 	flag = false;
			// } else if (shuliang == '' || shuliang == undefined) {
			// 	flag = false;
			// } else if (jianchazhe == '' || jianchazhe == undefined) {
			// 	flag = false;
			// }

			// 全部不为空，则OK
			if (jianchajileixing != '' && jianchajileixing != undefined
				&& buliangneirong != '' && buliangneirong != undefined
				&& weihao != '' && weihao != undefined
				&& shuliang != '' && shuliang != undefined
				&& jianchazhe != '' && jianchazhe != undefined) {
			}
			// 全部为空，也为OK
			else if ((jianchajileixing == '' || jianchajileixing == undefined)
				&& (buliangneirong == '' || buliangneirong == undefined)
				&& (weihao == '' || weihao == undefined)
				&& (shuliang == '' || shuliang == undefined)
				&& (jianchazhe == '' || jianchazhe == undefined)) {
			}
			// 任何一行记录，检查机类型和检查者必须同时填写，其他可为空
			else if ((jianchajileixing != '' || jianchajileixing != undefined)
				&& (jianchazhe == '' || jianchazhe == undefined)) {
				flag = false;
			} else if ((jianchazhe != '' || jianchazhe != undefined)
				&& (jianchajileixing == '' || jianchajileixing == undefined)) {
				flag = false;
			}

			if (flag == false) {
				_this.warning(false, '警告', '不良内容不正确！');
				return false;
			}

			// console.log(flag);return false;
			var url = "{{ route('smt.qcreport.qcreportappend') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				jianchajileixing: jianchajileixing,
				buliangneirong: buliangneirong,
				weihao: weihao,
				shuliang: shuliang,
				jianchazhe: jianchazhe,
				count_of_buliangxinxi_append: count_of_buliangxinxi_append,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.id_append = '';
					_this.jianchajileixing_append = '';
					_this.buliangneirong_append = '';
					_this.weihao_append = '';
					_this.created_at_append = '';
					_this.updated_at_append = '';
					_this.shuliang_append = ['', ''];
					_this.jianchazhe_append = '';

					_this.success(false, '成功', '追加成功！');
					// if (_this.qcdate_filter[0] != '' && _this.qcdate_filter != undefined) {
						_this.wbglgets(_this.pagecurrent, _this.pagelast);
					// }

				} else {
					_this.error(false, '失败', '追加失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '追加失败！');
			})
		},

		// 不良内容删除
		qcreport_remove_sub (row, item, index) {
			var _this = this;

			var id = row.id;
			var subid = index;
			var shuliang = item.shuliang;

			if (id == undefined || subid == undefined) {
				_this.warning(false, '警告', '不良内容选择不正确！');
				return false;
			}

			var url = "{{ route('smt.qcreport.qcreportremovesub') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				subid: subid,
				shuliang: shuliang,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					// if (_this.qcdate_filter[0] != '' && _this.qcdate_filter != undefined) {
						_this.wbglgets(_this.pagecurrent, _this.pagelast);
					// }
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},

		
		// 统计列表查询
		tongjigets (year) {
			var _this = this;

			if (year == undefined || year == '') {
				_this.tabledata2 = [];
				return false;
			}
			
			var myyear = year.Format("yyyy");
			var tongji_date_filter = [year.Format("yyyy-01-01 00:00:00"), year.Format("yyyy-12-31 23:59:59")];
			// console.log(year);return false;

			var url = "{{ route('smt.qcreport.tongjigets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					tongji_date_filter: tongji_date_filter,
				}
			})
			.then(function (response) {
				// console.log(response.data);return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var tongji = response.data;
					
					var res = [
						{
							'jianchariqi': myyear+'-01',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-02',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-03',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-04',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-05',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-06',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-07',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-08',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-09',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-10',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-11',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': myyear+'-12',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
						{
							'jianchariqi': '合计',
							'hejidianshu1': 0, 'buliangjianshu1': 0, 'hejitaishu1': 0, 'ppm1': 0,
							'hejidianshu2': 0, 'buliangjianshu2': 0, 'hejitaishu2': 0, 'ppm2': 0,
							'hejidianshu3': 0, 'buliangjianshu3': 0, 'hejitaishu3': 0,  'ppm3': 0,
							'hejidianshu4': 0, 'buliangjianshu4': 0, 'hejitaishu4': 0, 'ppm4': 0,
							'hejidianshu5': 0, 'buliangjianshu5': 0, 'hejitaishu5': 0, 'ppm5': 0,
							'hejidianshu6': 0, 'buliangjianshu6': 0, 'hejitaishu6': 0, 'ppm6': 0,
							'hejidianshu7': 0, 'buliangjianshu7': 0, 'hejitaishu7': 0, 'ppm7': 0,
							'hejidianshu8': 0, 'buliangjianshu8': 0, 'hejitaishu8': 0, 'ppm8': 0,
							'hejidianshu9': 0, 'buliangjianshu9': 0, 'hejitaishu9': 0, 'ppm9': 0,
							'hejidianshu10': 0, 'buliangjianshu10': 0, 'hejitaishu10': 0, 'ppm10': 0,
							'hejidianshuqiuhe': 0, 'buliangjianshuqiuhe': 0, 'hejitaishuqiuhe': 0, 'ppm': 0,
						},
					];
					var i = 0; // 月份

					tongji.map(function (v,k) {
						// 按检查日期分类
						// var riqi = new Date(v.jianchariqi);
						// console.log(v.jianchariqi.substring(5,7));
						let riqi = v.jianchariqi.substring(5,7);
						switch(riqi) //月份
						{
							case '01':
								i = 0;break;
							case '02':
								i = 1;break;
							case '03':
								i = 2;break;
							case '04':
								i = 3;break;
							case '05':
								i = 4;break;
							case '06':
								i = 5;break;
							case '07':
								i = 6;break;
							case '08':
								i = 7;break;
							case '09':
								i = 8;break;
							case '10':
								i = 9;break;
							case '11':
								i = 10;break;
							case '12':
								i = 11;break;
							default:
								
						}
						// console.log(i);
						// console.log(v.xianti.trim());

						switch(v.xianti.trim())
						{
							case 'SMT-1':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu1 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu1 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu1 += v.hejitaishu;}
								break;
							case 'SMT-2':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu2 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu2 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu2 += v.hejitaishu;}
								break;
							case 'SMT-3':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu3 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu3 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu3 += v.hejitaishu;}
								break;
							case 'SMT-4':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu4 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu4 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu4 += v.hejitaishu;}
								break;
							case 'SMT-5':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu5 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu5 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu5 += v.hejitaishu;}
								break;
							case 'SMT-6':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu6 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu6 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu6 += v.hejitaishu;}
								break;
							case 'SMT-7':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu7 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu7 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu7 += v.hejitaishu;}
								break;
							case 'SMT-8':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu8 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu8 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu8 += v.hejitaishu;}
								break;
							case 'SMT-9':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu9 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu9 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu9 += v.hejitaishu;}
								break;
							case 'SMT-10':
								if (v.hejidianshu!=undefined) {res[i].hejidianshu10 += v.hejidianshu;}
								if (v.bushihejianshuheji!=undefined) {res[i].buliangjianshu10 += v.bushihejianshuheji;}
								if (v.hejitaishu!=undefined) {res[i].hejitaishu10 += v.hejitaishu;}
								break;
							default:
						}
					});


					// console.log(res);return false;


					// 汇总 按相同月份不同线体求和（纵向）
					for(var j=0;j<12;j++) {
						res[12].hejidianshu1 += res[j].hejidianshu1;
						res[12].buliangjianshu1 += res[j].buliangjianshu1;
						res[12].hejitaishu1 += res[j].hejitaishu1;

						res[12].hejidianshu2 += res[j].hejidianshu2;
						res[12].buliangjianshu2 += res[j].buliangjianshu2;
						res[12].hejitaishu2 += res[j].hejitaishu2;

						res[12].hejidianshu3 += res[j].hejidianshu3;
						res[12].buliangjianshu3 += res[j].buliangjianshu3;
						res[12].hejitaishu3 += res[j].hejitaishu3;

						res[12].hejidianshu4 += res[j].hejidianshu4;
						res[12].buliangjianshu4 += res[j].buliangjianshu4;
						res[12].hejitaishu4 += res[j].hejitaishu4;

						res[12].hejidianshu5 += res[j].hejidianshu5;
						res[12].buliangjianshu5 += res[j].buliangjianshu5;
						res[12].hejitaishu5 += res[j].hejitaishu5;

						res[12].hejidianshu6 += res[j].hejidianshu6;
						res[12].buliangjianshu6 += res[j].buliangjianshu6;
						res[12].hejitaishu6 += res[j].hejitaishu6;

						res[12].hejidianshu7 += res[j].hejidianshu7;
						res[12].buliangjianshu7 += res[j].buliangjianshu7;
						res[12].hejitaishu7 += res[j].hejitaishu7;

						res[12].hejidianshu8 += res[j].hejidianshu8;
						res[12].buliangjianshu8 += res[j].buliangjianshu8;
						res[12].hejitaishu8 += res[j].hejitaishu8;

						res[12].hejidianshu9 += res[j].hejidianshu9;
						res[12].buliangjianshu9 += res[j].buliangjianshu9;
						res[12].hejitaishu9 += res[j].hejitaishu9;

						res[12].hejidianshu10 += res[j].hejidianshu10;
						res[12].buliangjianshu10 += res[j].buliangjianshu10;
						res[12].hejitaishu10 += res[j].hejitaishu10;

						// 汇总 按相同线体不同月份求和（横向）
						res[j].hejidianshuqiuhe = res[j].hejidianshu1 + res[j].hejidianshu2 + res[j].hejidianshu3 + res[j].hejidianshu4 + res[j].hejidianshu5 + res[j].hejidianshu6 + res[j].hejidianshu7 + res[j].hejidianshu8 + res[j].hejidianshu9 + res[j].hejidianshu10;
						res[j].buliangjianshuqiuhe = res[j].buliangjianshu1 + res[j].buliangjianshu2 + res[j].buliangjianshu3 + res[j].buliangjianshu4 + res[j].buliangjianshu5 + res[j].buliangjianshu6 + res[j].buliangjianshu7 + res[j].buliangjianshu8 + res[j].buliangjianshu9 + res[j].buliangjianshu10
						res[j].hejitaishuqiuhe = res[j].hejitaishu1 + res[j].hejitaishu2 + res[j].hejitaishu3 + res[j].hejitaishu4 + res[j].hejitaishu5 + res[j].hejitaishu6 + res[j].hejitaishu7 + res[j].hejitaishu8 + res[j].hejitaishu9 + res[j].hejitaishu10;
						res[j].ppm = res[j].buliangjianshuqiuhe > 0 ? (res[j].buliangjianshuqiuhe / res[j].hejidianshuqiuhe * 1000000).toFixed(2) : 0;
					
						res[12].hejidianshuqiuhe += res[j].hejidianshuqiuhe;
						res[12].buliangjianshuqiuhe += res[j].buliangjianshuqiuhe;
						res[12].hejitaishuqiuhe += res[j].hejitaishuqiuhe;

						// 各项PPM计算
						res[j].ppm1 = res[j].buliangjianshu1 > 0 ? (res[j].buliangjianshu1 / res[j].hejidianshu1 * 1000000).toFixed(2) : 0;
						res[j].ppm2 = res[j].buliangjianshu2 > 0 ? (res[j].buliangjianshu2 / res[j].hejidianshu2 * 1000000).toFixed(2) : 0;
						res[j].ppm3 = res[j].buliangjianshu3 > 0 ? (res[j].buliangjianshu3 / res[j].hejidianshu3 * 1000000).toFixed(2) : 0;
						res[j].ppm4 = res[j].buliangjianshu4 > 0 ? (res[j].buliangjianshu4 / res[j].hejidianshu4 * 1000000).toFixed(2) : 0;
						res[j].ppm5 = res[j].buliangjianshu5 > 0 ? (res[j].buliangjianshu5 / res[j].hejidianshu5 * 1000000).toFixed(2) : 0;
						res[j].ppm6 = res[j].buliangjianshu6 > 0 ? (res[j].buliangjianshu6 / res[j].hejidianshu6 * 1000000).toFixed(2) : 0;
						res[j].ppm7 = res[j].buliangjianshu7 > 0 ? (res[j].buliangjianshu7 / res[j].hejidianshu7 * 1000000).toFixed(2) : 0;
						res[j].ppm8 = res[j].buliangjianshu8 > 0 ? (res[j].buliangjianshu8 / res[j].hejidianshu8 * 1000000).toFixed(2) : 0;
						res[j].ppm9 = res[j].buliangjianshu9 > 0 ? (res[j].buliangjianshu9 / res[j].hejidianshu9 * 1000000).toFixed(2) : 0;
						res[j].ppm10 = res[j].buliangjianshu10 > 0 ? (res[j].buliangjianshu10 / res[j].hejidianshu10 * 1000000).toFixed(2) : 0;

					}

					// 合计项ppm计算
					res[12].ppm1 = res[12].buliangjianshu1 > 0 ? (res[12].buliangjianshu1 / res[12].hejidianshu1 * 1000000).toFixed(2) : 0;
					res[12].ppm2 = res[12].buliangjianshu2 > 0 ? (res[12].buliangjianshu2 / res[12].hejidianshu2 * 1000000).toFixed(2) : 0;
					res[12].ppm3 = res[12].buliangjianshu3 > 0 ? (res[12].buliangjianshu3 / res[12].hejidianshu3 * 1000000).toFixed(2) : 0;
					res[12].ppm4 = res[12].buliangjianshu4 > 0 ? (res[12].buliangjianshu4 / res[12].hejidianshu4 * 1000000).toFixed(2) : 0;
					res[12].ppm5 = res[12].buliangjianshu5 > 0 ? (res[12].buliangjianshu5 / res[12].hejidianshu5 * 1000000).toFixed(2) : 0;
					res[12].ppm6 = res[12].buliangjianshu6 > 0 ? (res[12].buliangjianshu6 / res[12].hejidianshu6 * 1000000).toFixed(2) : 0;
					res[12].ppm7 = res[12].buliangjianshu7 > 0 ? (res[12].buliangjianshu7 / res[12].hejidianshu7 * 1000000).toFixed(2) : 0;
					res[12].ppm8 = res[12].buliangjianshu8 > 0 ? (res[12].buliangjianshu8 / res[12].hejidianshu8 * 1000000).toFixed(2) : 0;
					res[12].ppm9 = res[12].buliangjianshu9 > 0 ? (res[12].buliangjianshu9 / res[12].hejidianshu9 * 1000000).toFixed(2) : 0;
					res[12].ppm10 = res[12].buliangjianshu10 > 0 ? (res[12].buliangjianshu10 / res[12].hejidianshu10 * 1000000).toFixed(2) : 0;
					
					res[12].ppm = res[12].buliangjianshuqiuhe > 0 ? (res[12].buliangjianshuqiuhe / res[12].hejidianshuqiuhe * 1000000).toFixed(2) : 0;

					_this.tabledata2 = res;
					
				} else {
					_this.tabledata2 = [];
				}
				
			})
			.catch(function (error) {
			})
		},

		exportData_tongji () {
			var _this = this;

			var tabledata = this.tabledata2;
			// 第二行title
			var titledata = 
				{
					jianchariqi: '作成日期',
					hejidianshu1: '合计点数', buliangjianshu1: '不良件数', hejitaishu1: '合计台数', ppm1: 'PPM',
					hejidianshu2: '合计点数', buliangjianshu2: '不良件数', hejitaishu2: '合计台数', ppm2: 'PPM',
					hejidianshu3: '合计点数', buliangjianshu3: '不良件数', hejitaishu3: '合计台数', ppm3: 'PPM',
					hejidianshu4: '合计点数', buliangjianshu4: '不良件数', hejitaishu4: '合计台数', ppm4: 'PPM',
					hejidianshu5: '合计点数', buliangjianshu5: '不良件数', hejitaishu5: '合计台数', ppm5: 'PPM',
					hejidianshu6: '合计点数', buliangjianshu6: '不良件数', hejitaishu6: '合计台数', ppm6: 'PPM',
					hejidianshu7: '合计点数', buliangjianshu7: '不良件数', hejitaishu7: '合计台数', ppm7: 'PPM',
					hejidianshu8: '合计点数', buliangjianshu8: '不良件数', hejitaishu8: '合计台数', ppm8: 'PPM',
					hejidianshu9: '合计点数', buliangjianshu9: '不良件数', hejitaishu9: '合计台数', ppm9: 'PPM',
					hejidianshu10:'合计点数', buliangjianshu10:'不良件数', hejitaishu10: '合计台数', ppm10: 'PPM',
					hejidianshuqiuhe: '合计点数求和', buliangjianshuqiuhe: '不良件数求和', hejitaishuqiuhe: '合计台数求和', ppm: 'PPM',
				};

			var finaldata = [];
			finaldata.push(titledata);
			for (let n = 0, l = tabledata.length; n < l; n++) {
				finaldata.push(tabledata[n]);
			}

			_this.$refs.table2.exportCsv({
				filename: '插件点数及不良件数统计' + new Date().Format("yyyyMMddhhmmss"),
				// columns: this.tablecolumns2,
				// data: this.tabledata2,

				columns: [
					{title: '', key: 'jianchariqi'},
					// {title: 'SMT-1'}, {title: ''}, {title: ''}, {title: 'SMT-2'}, {title: ''}, {title: ''}, {title: 'SMT-3'}, {title: ''}, {title: ''}, {title: 'SMT-4'}, {title: ''}, {title: ''}, {title: 'SMT-5'}, {title: ''}, {title: ''},
					// {title: 'SMT-6'}, {title: ''}, {title: ''}, {title: 'SMT-7'}, {title: ''}, {title: ''}, {title: 'SMT-8'}, {title: ''}, {title: ''}, {title: 'SMT-9'}, {title: ''}, {title: ''}, {title: 'SMT-10'}, {title: ''}, {title: ''},
					// {title: '\r\n'},
					{title: 'SMT-1', key: 'hejidianshu1'}, {title: '', key: 'buliangjianshu1'}, {title: '', key: 'hejitaishu1'}, {title: '', key: 'ppm1'}, {title: 'SMT-2', key: 'hejidianshu2'}, {title: '', key: 'buliangjianshu2'}, {title: '', key: 'hejitaishu2'}, {title: '', key: 'ppm2'},{title: 'SMT-3', key: 'hejidianshu3'}, {title: '', key: 'buliangjianshu3'}, {title: '', key: 'hejitaishu3'}, {title: '', key: 'ppm3'},
					{title: 'SMT-4', key: 'hejidianshu4'}, {title: '', key: 'buliangjianshu4'}, {title: '', key: 'hejitaishu4'}, {title: '', key: 'ppm4'}, {title: 'SMT-4', key: 'hejidianshu5'}, {title: '', key: 'buliangjianshu5'}, {title: '', key: 'hejitaishu5'}, {title: '', key: 'ppm5'},{title: 'SMT-6', key: 'hejidianshu6'}, {title: '', key: 'buliangjianshu6'}, {title: '', key: 'hejitaishu6'}, {title: '', key: 'ppm6'},
					{title: 'SMT-7', key: 'hejidianshu7'}, {title: '', key: 'buliangjianshu7'}, {title: '', key: 'hejitaishu7'}, {title: '', key: 'ppm7'}, {title: 'SMT-8', key: 'hejidianshu8'}, {title: '', key: 'buliangjianshu8'}, {title: '', key: 'hejitaishu8'}, {title: '', key: 'ppm8'},{title: 'SMT-9', key: 'hejidianshu9'}, {title: '', key: 'buliangjianshu9'}, {title: '', key: 'hejitaishu9'}, {title: '', key: 'ppm9'},
					{title: 'SMT-10', key: 'hejidianshu10'}, {title: '', key: 'buliangjianshu10'}, {title: '', key: 'hejitaishu10'}, {title: '', key: 'ppm10'},
					{title: '', key: 'hejidianshuqiuhe'}, {title: '', key: 'buliangjianshuqiuhe'}, {title: '', key: 'hejitaishuqiuhe'}, {title: '', key: 'ppm'},
					// {title: '\r\n'}, {title: '\r\n'},
				],
				// data: [{'aaa': '111'}, {'bbb': '222'}, {'ccc': '333'}, {'ddd': '444'}],
				// data: this.tabledata2,
				data: finaldata,
			});
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
		uploadstart_wbglbase: function (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled = true;
			_this.loadingStatus = true;

			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',_this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('smt.wbgl.wbglbaseimport') }}";
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
					_this.success(false, '成功', '导入成功！');
				} else {
					_this.error(false, '失败', '注意内容文本格式并且内容不能为空！');
				}
				
				setTimeout( function () {
					_this.file = null;
					_this.loadingStatus = false;
					_this.uploaddisabled = false;
				}, 1000);
				
			})
			.catch(function (error) {
				_this.error(false, '错误', error);
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
		
		// 计划上传模板下载
		download_wbglbase: function () {
			var url = "{{ route('smt.wbgl.wbglbasedownload') }}";
			window.setTimeout(function () {
				window.location.href = url;
			}, 1000);
		},

			
	},
	mounted () {
		@hasanyrole('role_smt_wbgl_write|role_super_admin')
		var id_bianhao = document.getElementById("id_bianhao");
		id_bianhao.style.background = "#FFF8E1";

		// var id_wangbanbufan = document.getElementById("id_wangbanbufan");
		// id_wangbanbufan.style.background = "#FFF8E1";

		// var id_pinming = document.getElementById("id_pinming");
		// id_pinming.style.background = "#FFF8E1";

		@endhasanyrole

		// var _this = this;
		// _this.configgets();
	}
})
</script>
@endsection