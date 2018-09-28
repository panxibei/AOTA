@extends('smt.layouts.mainbase')

@section('my_title')
SMT - QC report 
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
<strong>SMT QC Report</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Divider orientation="left">工程内不良记录</Divider>

	<i-row :gutter="16">
		<i-col span="8">
			* <strong>扫描</strong>&nbsp;&nbsp;
			<i-input ref="saomiao" v-model.lazy="saomiao" @on-keyup="saomiao=saomiao.toUpperCase()" placeholder="例：MRAP808A/5283600121-51/MAIN/900" size="large" clearable autofocus style="width: 320px"></i-input>
		</i-col>
		<i-col span="3">
			* 线体&nbsp;&nbsp;
			<i-select v-model.lazy="xianti" clearable style="width:80px" placeholder="">
				<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="3">
			* 班次&nbsp;&nbsp;
			<i-select v-model.lazy="banci" clearable style="width:80px" placeholder="">
				<i-option v-for="item in option_banci" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="3">
			* 工序&nbsp;&nbsp;
			<i-select v-model.lazy="gongxu" @on-change="onchangegongxu" clearable style="width:80px" placeholder="">
				<i-option v-for="item in option_gongxu" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="3">
			* 点/枚&nbsp;&nbsp;
			<Input-number v-model.lazy="dianmei" :min="1" readonly style="width: 80px"></Input-number>
		</i-col>
		<i-col span="4">
			* 枚数&nbsp;&nbsp;
			<Input-number v-model.lazy="meishu" :min="1" style="width: 80px"></Input-number>
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
			* 检查机类型&nbsp;&nbsp;
			<i-select v-model.lazy="item.jianchajileixing" size="small" clearable style="width:120px" placeholder="">
				<i-option v-for="item in option_jianchajileixing" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="6">
			* 不良内容&nbsp;&nbsp;
			<i-select v-model.lazy="item.buliangneirong" multiple size="small" clearable style="width:200px" placeholder="例：部品不良">
				<Option-group label="****** 印刷系 ******">
					<i-option v-for="item in option_buliangneirong1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 装着系 ******">
					<i-option v-for="item in option_buliangneirong2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 装着系/人系 ******">
					<i-option v-for="item in option_buliangneirong3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 设备系 ******">
					<i-option v-for="item in option_buliangneirong4" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 异物系 ******">
					<i-option v-for="item in option_buliangneirong5" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 人系 ******">
					<i-option v-for="item in option_buliangneirong6" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 部品系/人系 ******">
					<i-option v-for="item in option_buliangneirong7" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 部品系 ******">
					<i-option v-for="item in option_buliangneirong8" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 其他 ******">
					<i-option v-for="item in option_buliangneirong9" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
			</i-select>
		</i-col>
		<i-col span="4">
			* 位号&nbsp;&nbsp;
			<i-input v-model.lazy="item.weihao" @on-keyup="item.weihao=item.weihao.toUpperCase()" placeholder="例：IC801" size="small" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="3">
			* 数量&nbsp;&nbsp;
			<Input-number v-model.lazy="item.shuliang" :min="1" size="small" style="width: 80px"></Input-number>
		</i-col>
		<i-col span="5">
			* 检查者&nbsp;&nbsp;
			<i-select v-model.lazy="item.jianchazhe" size="small" clearable style="width:140px" placeholder="">
				<Option-group label="****** 一组 ******">
					<i-option v-for="item in option_jianchazhe1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 二组 ******">
					<i-option v-for="item in option_jianchazhe2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 三组 ******">
					<i-option v-for="item in option_jianchazhe3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
			</i-select>
		</i-col>
		
	</i-row>
	<br>
	</span>

	<br>

	<i-row :gutter="16">
		<i-col span="24">
			&nbsp;&nbsp;<i-button @click="oncreate()" type="primary">记入</i-button>
			&nbsp;&nbsp;<i-button @click="onclear()">清除</i-button>
		</i-col>
	</i-row>

	
	
	<br><br><br>
	<Divider orientation="left">品质管理日报</Divider>

	<i-row :gutter="16">
		<i-col span="2">
			&nbsp;
		</i-col>
		<i-col span="7">
			查询：&nbsp;&nbsp;&nbsp;&nbsp;*日期范围&nbsp;&nbsp;
			<Date-picker v-model.lazy="qcdate_filter" @on-change="qcreportgets();onselectchange1();" type="daterange" size="small" placement="top" style="width:200px"></Date-picker>
		</i-col>
		<i-col span="3">
			线体&nbsp;&nbsp;
			<i-select v-model.lazy="xianti_filter" @on-change="qcreportgets();onselectchange1();" clearable size="small" style="width:80px" placeholder="">
				<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="4">
			机种名&nbsp;&nbsp;
			<i-input v-model.lazy="jizhongming_filter" @on-change="qcreportgets()" @on-keyup="jizhongming_filter=jizhongming_filter.toUpperCase()" size="small" clearable style="width: 120px"></i-input>
		</i-col>
		<i-col span="6">
			不良内容&nbsp;&nbsp;
			<i-select v-model.lazy="buliangneirong_filter" @on-change="qcreportgets();onselectchange1();" size="small" clearable style="width:200px" placeholder="例：部品不良">
				<Option-group label="****** 印刷系 ******">
					<i-option v-for="item in option_buliangneirong1" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 装着系 ******">
					<i-option v-for="item in option_buliangneirong2" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 装着系/人系 ******">
					<i-option v-for="item in option_buliangneirong3" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 设备系 ******">
					<i-option v-for="item in option_buliangneirong4" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 异物系 ******">
					<i-option v-for="item in option_buliangneirong5" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 人系 ******">
					<i-option v-for="item in option_buliangneirong6" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 部品系/人系 ******">
					<i-option v-for="item in option_buliangneirong7" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 部品系 ******">
					<i-option v-for="item in option_buliangneirong8" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
				<Option-group label="****** 其他 ******">
					<i-option v-for="item in option_buliangneirong9" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
				</Option-group>
			</i-select>
		</i-col>
		<i-col span="2">
		&nbsp;
		</i-col>
	</i-row>
	<br><br>
	<i-row :gutter="16">
		<i-col span="2">
			<i-button @click="ondelete()" :disabled="boo_delete" type="warning" size="small">Delete</i-button>&nbsp;&nbsp;
		</i-col>
		<i-col span="8">
			导出：&nbsp;&nbsp;&nbsp;&nbsp;
			<i-button type="default" size="small" @click="exportData_table()"><Icon type="ios-download-outline"></Icon> 导出当前显示数据</i-button>
			&nbsp;&nbsp;
			<i-button type="default" size="small" @click="exportData_db()"><Icon type="ios-download-outline"></Icon> 导出全部后台数据</i-button>
		</i-col>
		<i-col span="10">
			&nbsp;
		</i-col>
		<i-col span="4">
			&nbsp;&nbsp;&nbsp;<strong>不良件数小计：@{{ buliangjianshuxiaoji.toLocaleString() }} </strong>&nbsp;&nbsp;
		</i-col>
	</i-row>

	<br><br>
	<i-table ref="table1" height="400" size="small" border :columns="tablecolumns1" :data="tabledata1" @on-selection-change="selection => onselectchange1(selection)"></i-table>
	<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total></Page>
	
	<br>
	<Divider orientation="left">品质管理图表</Divider>
	
	<br>
	&nbsp;&nbsp;&nbsp;<i-button @click="onchart1()" type="info" size="small">刷新图表一</i-button>&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;
	<!--
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	<Upload
		:before-upload="handleUpload"
		action="{{ route('smt.qcreport.qcreportimport') }}">
        <i-button icon="ios-cloud-upload-outline">Upload files</i-button>
    </Upload>
	<div v-if="file !== null">Upload file: @{{ file.name }} <i-button @click="upload" :loading="loadingStatus" size="small">@{{ loadingStatus ? 'Uploading' : 'Click to upload' }}</i-button></div>
	-->
	
	<br><br>
	<i-row :gutter="16">
		<i-col span="24">
			<div id="chart1" style="height:400px"></div>
		</i-col>
	</i-row>

	<Divider></Divider>
	<br>
	&nbsp;&nbsp;&nbsp;<i-button @click="onchart2()" type="info" size="small">刷新图表二</i-button>&nbsp;&nbsp;
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
var vm_app = new Vue({
	el: '#app',
	data: {
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

		
		// 扫描
		saomiao: 'MRAP808A/5283600121-51/MAIN/900',
		
		// 线体
		xianti: '',
		
		// 班次
		banci: '',
		
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
		
		// 表头1
		tablecolumns1: [
			{
				type: 'selection',
				width: 50,
				align: 'center'
			},
			{
				type: 'index',
				width: 60,
				align: 'center'
			},
			{
				title: '日期',
				key: 'created_at',
				align: 'center',
				width: 160,
			},
			{
				title: '线体',
				key: 'xianti',
				align: 'center',
				width: 80,
				filters: [
					{
						label: 'SMT-1',
						value: 'SMT-1'
					},
					{
						label: 'SMT-2',
						value: 'SMT-2'
					},
					{
						label: 'SMT-3',
						value: 'SMT-3'
					},
					{
						label: 'SMT-4',
						value: 'SMT-4'
					},
					{
						label: 'SMT-5',
						value: 'SMT-5'
					},
					{
						label: 'SMT-6',
						value: 'SMT-6'
					},
					{
						label: 'SMT-7',
						value: 'SMT-7'
					},
					{
						label: 'SMT-8',
						value: 'SMT-8'
					},
					{
						label: 'SMT-9',
						value: 'SMT-9'
					},
					{
						label: 'SMT-10',
						value: 'SMT-10'
					},
				],
				filterMultiple: false,
				filterMethod: function (value, row) {
					if (value === 'SMT-1') {
						return row.xianti === 'SMT-1';
					} else if (value === 'SMT-2') {
						return row.xianti === 'SMT-2';
					} else if (value === 'SMT-3') {
						return row.xianti === 'SMT-3';
					} else if (value === 'SMT-4') {
						return row.xianti === 'SMT-4';
					} else if (value === 'SMT-5') {
						return row.xianti === 'SMT-5';
					} else if (value === 'SMT-6') {
						return row.xianti === 'SMT-6';
					} else if (value === 'SMT-7') {
						return row.xianti === 'SMT-7';
					} else if (value === 'SMT-8') {
						return row.xianti === 'SMT-8';
					} else if (value === 'SMT-9') {
						return row.xianti === 'SMT-9';
					} else if (value === 'SMT-10') {
						return row.xianti === 'SMT-10';
					}
				}
			},
			{
				title: '班次',
				key: 'banci',
				align: 'center',
				width: 80,
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
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 120,
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
				title: '工序',
				key: 'gongxu',
				align: 'center',
				width: 80
			},
			{
				title: 'SP NO.',
				key: 'spno',
				align: 'center',
				width: 140,
				sortable: true
			},
			{
				title: 'LOT数',
				key: 'lotshu',
				align: 'center',
				width: 100,
				sortable: true,
				render: (h, params) => {
					return h('div', [
						params.row.lotshu.toLocaleString()
					]);
				}
			},
			{
				title: '点/枚',
				key: 'dianmei',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.dianmei.toLocaleString()
					]);
				}
			},
			{
				title: '枚数',
				key: 'meishu',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						params.row.meishu.toLocaleString()
					]);
				}
			},
			{
				title: '合计点数',
				key: 'hejidianshu',
				align: 'center',
				width: 100,
				render: (h, params) => {
					return h('div', [
						// parseFloat(params.row.hejidianshu * 100) + '%'
						params.row.hejidianshu.toLocaleString()
					]);
				}
			},
			{
				title: '不适合件数合计',
				key: 'bushihejianshuheji',
				align: 'center',
				width: 100
			},
			{
				title: 'PPM',
				key: 'ppm',
				align: 'center',
				width: 80
			},
			{
				title: '不良内容',
				key: 'buliangneirong',
				align: 'center',
				width: 120,
				filters: [
					{value: '连焊', label: '连焊'}, 
					{value: '引脚焊锡量少/无', label: '引脚焊锡量少/无'},
					{value: 'chip部品焊锡少/无', label: 'chip部品焊锡少/无'},
					{value: '焊锡球', label: '焊锡球'},
					{value: '部品浮起竖立', label: '部品浮起竖立'},
					{value: 'chip部品横立', label: 'chip部品横立'},
					{value: '欠品', label: '欠品'},
					{value: '焊锡未熔解', label: '焊锡未熔解'},
					{value: '部品错误', label: '部品错误'},
					{value: '部品多余', label: '部品多余'},
					{value: '异物', label: '异物'},
					{value: '极性错误', label: '极性错误'},
					{value: '炉后部品破损', label: '炉后部品破损'}, 
					{value: '引脚弯曲', label: '引脚弯曲'},
					{value: '基板/部品变形后引脚浮起', label: '基板/部品变形后引脚浮起'},
					{value: '引脚不上锡', label: '引脚不上锡'},
					{value: '基板不上锡', label: '基板不上锡'},
					{value: 'chip部品不上锡', label: 'chip部品不上锡'},
					{value: '部品不良', label: '部品不良'},
					{value: '其他', label: '其他'},
				],
				filterMultiple: false,
				filterMethod: function (value, row) {
					var result = '';
					if (value === '连焊') {
						result = row.buliangneirong === '连焊';
					} else if (value === '引脚焊锡量少/无') {
						result = row.buliangneirong === '引脚焊锡量少/无';
					} else if (value === 'chip部品焊锡少/无') {
						result = row.buliangneirong === 'chip部品焊锡少/无';
					} else if (value === '焊锡球') {
						result = row.buliangneirong === '焊锡球';
					} else if (value === '部品浮起竖立') {
						result = row.buliangneirong === '部品浮起竖立';
					} else if (value === 'chip部品横立') {
						result = row.buliangneirong === 'chip部品横立';
					} else if (value === '欠品') {
						result = row.buliangneirong === '欠品';
					} else if (value === '焊锡未熔解') {
						result = row.buliangneirong === '焊锡未熔解';
					} else if (value === '部品错误') {
						result = row.buliangneirong === '部品错误';
					} else if (value === '部品多余') {
						result = row.buliangneirong === '部品多余';
					} else if (value === '异物') {
						result = row.buliangneirong === '异物';
					} else if (value === '极性错误') {
						result = row.buliangneirong === '极性错误';
					} else if (value === '炉后部品破损') {
						result = row.buliangneirong === '炉后部品破损';
					} else if (value === '引脚弯曲') {
						result = row.buliangneirong === '引脚弯曲';
					} else if (value === '基板/部品变形后引脚浮起') {
						result = row.buliangneirong === '基板/部品变形后引脚浮起';
					} else if (value === '引脚不上锡') {
						result = row.buliangneirong === '引脚不上锡';
					} else if (value === '基板不上锡') {
						result = row.buliangneirong === '基板不上锡';
					} else if (value === 'chip部品不上锡') {
						result = row.buliangneirong === 'chip部品不上锡';
					} else if (value === '部品不良') {
						result = row.buliangneirong === '部品不良';
					} else if (value === '其他') {
						result = row.buliangneirong === '其他';
					}
					
					return result;
					
				}				
			},
			{
				title: '位号',
				key: 'weihao',
				align: 'center',
				width: 120
			},
			{
				title: '数量',
				key: 'shuliang',
				align: 'center',
				width: 80
			},
			{
				title: '检查机类型',
				key: 'jianchajileixing',
				align: 'center',
				width: 120
			},
			{
				title: '检查者',
				key: 'jianchazhe',
				align: 'center',
				width: 120
			}
		],
		tabledata1: [],
		tableselect1: [],
		

		
		// 日期范围过滤
		qcdate_filter: [], //new Date(),
		
		// 机种名
		jizhongming_filter: '',
		
		// 线体过滤
		xianti_filter: '',
		
		// 不良内容过滤
		buliangneirong_filter: '',
		
		// 删除disabled
		boo_delete: true,
		
		// dailyproductionreport 暂未用到
		saomiao_data: '',
		
		// 不良件数小计
		buliangjianshuxiaoji: 0,
		
		
		// echarts ajax使用 这个才是实际使用的
		chart1_type: 'bar',
		
		chart1_option_tooltip_show: true,
		
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
		
		//分页
		pagecurrent: 1,
		pagetotal: 1,
		pagepagesize: 10,
		pagelast: 1,
		
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
		
		// 切换当前页
		oncurrentpagechange: function (currentpage) {
			this.qcreportgets(currentpage, this.pagelast);
		},
		
		// qcreport列表
		qcreportgets: function (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
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
			
			var jizhongming_filter = _this.jizhongming_filter;
			var xianti_filter = _this.xianti_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					qcdate_filter: qcdate_filter,
					jizhongming_filter: jizhongming_filter,
					xianti_filter: xianti_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data) {
					_this.pagecurrent = response.data.current_page;
					_this.pagetotal = response.data.total;
					_this.pagelast = response.data.last_page
					
					_this.tabledata1 = response.data.data;
					// console.log(_this.tabledata1);
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
		
		// 表1选择
		onselectchange1: function (selection) {
			var _this = this;
			_this.tableselect1 = [];

			for (var i in selection) {
				_this.tableselect1.push(selection[i].id);
			}
			
			_this.boo_delete = _this.tableselect1[0] == undefined ? true : false;
			
		},
		

		// 加载扫描相应信息（暂未用到）
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
		onclear: function () {
			var _this = this;
			_this.saomiao = '';
			
			_this.piliangluru.map(function (v,i) {
				v.jianchajileixing = '';
				v.buliangneirong = '';
				v.weihao = '';
				v.shuliang = '';
				v.jianchazhe = '';
			});
			
			_this.$refs.saomiao.focus();
		},
		
		// oncreate
		oncreate: function () {
			var _this = this;
			var saomiao = _this.saomiao;
			var xianti = _this.xianti;
			var banci = _this.banci;
			var gongxu = _this.gongxu;
			var dianmei = _this.dianmei;
			var meishu = _this.meishu;
			
			if (saomiao == '' || saomiao == undefined || xianti == '' || xianti == undefined
				|| banci == '' || banci == undefined || gongxu == '' || gongxu == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}
			
			_this.piliangluru.map(function (v,i) {
				// console.log(v.jianchajileixing);
				// console.log(v.buliangneirong);
				// console.log(v.weihao);
				// console.log(v.shuliang);
				// console.log(v.jianchazhe);
				
				if (v.jianchajileixing == '' || v.buliangneirong == '' || v.weihao == ''  || v.shuliang == '' || v.jianchazhe == ''
					|| v.jianchajileixing == undefined || v.buliangneirong == undefined || v.weihao == undefined || v.shuliang == undefined || v.jianchazhe == undefined) {
					_this.warning(false, '警告', '输入内容为空或不正确！');
					return false;
				}
			});
			
			var piliangluru = _this.piliangluru;
			
			var tableselect1 = _this.tableselect1;

			var url = "{{ route('smt.qcreport.qcreportcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				saomiao : saomiao,
				xianti: xianti,
				banci: banci,
				gongxu: gongxu,
				dianmei: dianmei,
				meishu: meishu,
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
					_this.qcreportgets();

					// var t = [];
					// for (var i in tableselect1) {
						// t.push({id: tableselect1[i]});
					// }
					// _this.onselectchange1(t);
				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},		
		
		// ondelete
		ondelete: function () {
			var _this = this;
			
			var tableselect1 = _this.tableselect1;
			
			if (tableselect1[0] == undefined) return false;

			var url = "{{ route('smt.qcreport.qcreportdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect1: tableselect1
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, '成功', '删除成功！');
					_this.tableselect1 = [];
					_this.qcreportgets();
					
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
		exportData_db: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter == undefined) {
				_this.warning(false, '警告', '请选择日期范围！');
				return false;
			}
			
			var queryfilter_datefrom = _this.qcdate_filter[0].Format("yyyy-MM-dd");
			var queryfilter_dateto = _this.qcdate_filter[1].Format("yyyy-MM-dd");
			
			var url = "{{ route('smt.qcreport.qcreportexport') }}"
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
							subtext: vm_app.qcdate_filter[0].Format('yyyy-MM-dd') + ' - ' + vm_app.qcdate_filter[1].Format('yyyy-MM-dd'), //'2018.06-2018-07'
							x: 'center'
						},
						tooltip: {
							show: vm_app.chart1_option_tooltip_show,
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
			var shuliang = [];
			var ppm = [];
			
			var i = 0;
			for (i=0;i<10;i++) {
				hejidianshu[i] = 0;
				bushihejianshuheji[i] = 0;
				shuliang[i] = 0;
				ppm[i] = 0;
			}
			
			// 图表按当前表格中最大记录数重新查询
			
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
			
			var jizhongming_filter = _this.jizhongming_filter;
			var xianti_filter = _this.xianti_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					jizhongming_filter: jizhongming_filter,
					xianti_filter: xianti_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data) {
					var chartdata1 = response.data.data;
					// console.log(chartdata1);
					chartdata1.map(function (v,j) {
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
						shuliang[i] += v.shuliang;

						// if (hejidianshu[i] == 0) {
							// ppm[i] = 0;
						// } else {
							// ppm[i] = bushihejianshuheji[i] / hejidianshu[i] * 1000000;
						// }
						// ppm[i] += v.ppm;

					});
					// console.log(shuliang);

					// ppm计算
					hejidianshu.map(function (v,i) {

					
						// ppm[i] += v.ppm;
						// hejidianshu[i] += v.hejidianshu;
						// bushihejianshuheji[i] += v.bushihejianshuheji;

						if (hejidianshu[i] == 0) {
							ppm[i] = 0;
						} else {
							ppm[i] = (shuliang[i] / hejidianshu[i] * 1000000).toFixed(2);
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
						// data: bushihejianshuheji
						data: shuliang
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
				
			})
			.catch(function (error) {
				// _this.loadingbarerror();
				// _this.error(false, 'Error', error);
			})

		},
		
		
		onchart2: function () {
			var _this = this;
			
			if (_this.qcdate_filter[0] == '' || _this.qcdate_filter[1] == '') {
				_this.warning(false, '警告', '请先选择查询条件！');
				return false;
			}
			
			// var bushihejianshuheji = [];
			var shuliang = [];
			for (var i=0;i<10;i++) {
				// bushihejianshuheji[i] = 0;
				shuliang[i] = 0;
			}
			
			// 图表按当前表格中最大记录数重新查询
			
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
			
			var jizhongming_filter = _this.jizhongming_filter;
			var xianti_filter = _this.xianti_filter;
			var buliangneirong_filter = _this.buliangneirong_filter;

			var url = "{{ route('smt.qcreport.qcreportgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagetotal,
					page: 1,
					qcdate_filter: qcdate_filter,
					jizhongming_filter: jizhongming_filter,
					xianti_filter: xianti_filter,
					buliangneirong_filter: buliangneirong_filter
				}
			})
			.then(function (response) {
				if (response.data) {
					var chartdata2 = response.data.data;			
			
					chartdata2.map(function (v,j) {
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
					
						// bushihejianshuheji[i] += v.bushihejianshuheji;
						shuliang[i] += v.shuliang;
					});
					
					var data = 
					[
						// {value: bushihejianshuheji[0], name:'SMT-1'},
						// {value: bushihejianshuheji[1], name:'SMT-2'},
						// {value: bushihejianshuheji[2], name:'SMT-3'},
						// {value: bushihejianshuheji[3], name:'SMT-4'},
						// {value: bushihejianshuheji[4], name:'SMT-5'},
						// {value: bushihejianshuheji[5], name:'SMT-6'},
						// {value: bushihejianshuheji[6], name:'SMT-7'},
						// {value: bushihejianshuheji[7], name:'SMT-8'},
						// {value: bushihejianshuheji[8], name:'SMT-9'},
						// {value: bushihejianshuheji[9], name:'SMT-10'},
						{value: shuliang[0], name:'SMT-1'},
						{value: shuliang[1], name:'SMT-2'},
						{value: shuliang[2], name:'SMT-3'},
						{value: shuliang[3], name:'SMT-4'},
						{value: shuliang[4], name:'SMT-5'},
						{value: shuliang[5], name:'SMT-6'},
						{value: shuliang[6], name:'SMT-7'},
						{value: shuliang[7], name:'SMT-8'},
						{value: shuliang[8], name:'SMT-9'},
						{value: shuliang[9], name:'SMT-10'},
					];
					
					_this.chart2_option_series_data = data;
					_this.chart2_function();
			
				}
				
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
		onchangegongxu: function () {
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
				_this.dianmei = response.data;


			})
			.catch(function (error) {
				this.error(false, 'Error', error);
			})

			
		},
		
		
		
		
			
			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
		// _this.qcreportgets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection