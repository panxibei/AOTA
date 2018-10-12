@extends('smt.layouts.mainbase')

@section('my_title')
SMT - MPoint 
@parent
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_project')
<strong>SMT MPoint</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<Divider orientation="left">信息录入</Divider>

	<i-row :gutter="16">
		<i-col span="5">
			* 机种名&nbsp;&nbsp;
			<i-input v-model.lazy="jizhongming" @on-keyup="jizhongming=jizhongming.toUpperCase()" size="small" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="5">
			* 品名&nbsp;&nbsp;
			<i-input v-model.lazy="pinming" size="small" clearable style="width: 160px"></i-input>
		</i-col>
		<i-col span="4">
			* 面&nbsp;&nbsp;
			<i-select v-model.lazy="mian_select" placeholder="" clearable size="small" style="width:120px">
				<i-option v-for="item in mian_option" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
			</i-select>
		</i-col>
		<i-col span="10">
			&nbsp;
		</i-col>
	</i-row>

	<br><br>
	
	<i-row :gutter="16">
		<i-col span="5">
			* 点/台&nbsp;&nbsp;
			<Input-number v-model.lazy="diantai" :min="1" size="small"></Input-number>
		</i-col>
		<i-col span="5">
			* 拼板&nbsp;&nbsp;
			<Input-number v-model.lazy="pinban" :min="1" size="small"></Input-number>
		</i-col>
		<i-col span="4">
			<i-button @click="oncreate()" type="primary">记入</i-button>&nbsp;&nbsp;&nbsp;
			<i-button @click="onupdate()" :disabled="boo_update">更新</i-button>&nbsp;&nbsp;&nbsp;
			<i-button @click="onclear()">清除</i-button>&nbsp;&nbsp;&nbsp;
		</i-col>
		<i-col span="10">
			<Upload
				:before-upload="uploadstart"
				:show-upload-list="false"
				:format="['xls','xlsx']"
				:on-format-error="handleFormatError"
				:max-size="2048"
				action="/">
				<i-button icon="ios-cloud-upload-outline" :loading="loadingStatus" :disabled="uploaddisabled">@{{ loadingStatus ? '上传中' : '批量导入' }}</i-button>
			</Upload>
			<!--
			<div v-if="file !== null">等待上传: @{{ file.name }} &nbsp;&nbsp;
				<i-button @click="uploadstart" :loading="loadingStatus" size="small">@{{ loadingStatus ? '上传中' : '上传' }}</i-button>
				<i-button @click="uploadcancel" size="small">取消</i-button>
			</div>
			-->

		</i-col>
	</i-row>

	<br><br>
	<!--<div style="background-color: rgb(201, 226, 179); height: 1px;"></div>-->
	<Divider orientation="left">数据查询</Divider>
	
	<div>
	
		<i-row :gutter="16">
			<i-col span="3">
				<i-button @click="ondelete()" :disabled="boo_delete" type="warning" size="small">Delete</i-button>&nbsp;&nbsp;
			</i-col>
			<i-col span="5">
				日期&nbsp;&nbsp;
				<Date-picker v-model.lazy="dailydate_filter" @on-change="dailydate_filter=datepickerchange(dailydate_filter);mpointgets(1,1);" type="date" size="small" placement="top" style="width:160px"></Date-picker>
			</i-col>
			<i-col span="5">
				机种名&nbsp;&nbsp;
				<i-input v-model.lazy="jizhongming_filter" @on-change="mpointgets(1,1)" size="small" clearable style="width: 160px"></i-input>
			</i-col>
			<i-col span="11">
			</i-col>
		</i-row>
	
		<br><br>
		<i-table stripe height="300" size="small" border :columns="tablecolumns" :data="tabledata" @on-selection-change="selection => onselectchange(selection)"></i-table>
		<br><Page :current="pagecurrent" :total="pagetotal" :page-size="pagepagesize" @on-change="currentpage => oncurrentpagechange(currentpage)" show-total></Page>
	</div>					

</div>
@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	data: {
		// 日期
		dailydate: '',
		
		// id
		mpointid: '',
		
		// 机种名
		jizhongming: '',

		//品名
		pinming: '',

		// 面
		mian_select: '',
		mian_option: [
			{
				value: 'A',
				label: 'A'
			},
			{
				value: 'B',
				label: 'B'
			}
		],

		//点/台
		diantai: '',

		//拼板
		pinban: '',

		// 表头
		tablecolumns: [
			{
				type: 'selection',
				width: 50,
				align: 'center'
			},
			// 1
			{
				type: 'index',
				width: 60,
				align: 'center'
			},
			// 2
			{
				title: '机种名',
				key: 'jizhongming',
				align: 'center',
				width: 200,
				sortable: true

			},
			// 3
			{
				title: '品名',
				key: 'pinming',
				align: 'center',
				width: 150,
				sortable: true
			},
			// 4
			{
				title: '面',
				key: 'gongxu',
				align: 'center',
				width: 120,
				filters: [
					{
						label: 'A',
						value: 'A'
					},
					{
						label: 'B',
						value: 'B'
					}
				],
				filterMultiple: false,
				filterMethod: function (value, row) {
					if (value === 'A') {
						return row.gongxu === 'A';
					} else if (value === 'B') {
						return row.gongxu === 'B';
					}
				}
			},
			// 5
			{
				title: '点/台',
				key: 'diantai',
				align: 'center',
				width: 150
			},
			// 6
			{
				title: '拼板',
				key: 'pinban',
				align: 'center',
				width: 120
			},
			// 7
			{
				title: '创建日期',
				key: 'created_at',
				align: 'center',
				width: 200
			},
			{
				title: 'Action',
				key: 'action',
				align: 'center',
				width: 100,
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
									vm_app.viewmpoint(params.row)
								}
							}
						}, 'View')
					]);
				}
			}
		],
		tabledata: [],
		
		// 表格选择的项目
		tableselect: [],

		// 删除disabled
		boo_delete: true,

		// 更新disabled
		boo_update: true,

		// 日期过滤
		dailydate_filter: '',
		
		// 机种名过滤
		jizhongming_filter: '',
		
		//分页
		pagecurrent: 1,
		pagetotal: 1,
		pagepagesize: 10,
		pagelast: 1,
		
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
		
		datepickerchange: function (date) {
			if (typeof(date)=='string') {
				return date;
			} else {
				return date.Format("yyyy-MM-dd");
			}
		},
		
		// 切换当前页
		oncurrentpagechange: function (currentpage) {
			this.mpointgets(currentpage, this.pagelast);
		},
		
		// mpoint列表
		mpointgets: function(page, last_page){
			var _this = this;
			var url = "{{ route('smt.pdreport.mpointgets') }}";
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.pagepagesize,
					page: page,
					dailydate_filter : _this.dailydate_filter,
					jizhongming_filter : _this.jizhongming_filter
				}
			})
			.then(function (response) {
				if (response.data) {
					_this.pagecurrent = response.data.current_page;
					_this.pagetotal = response.data.total;
					_this.pagelast = response.data.last_page
					
					_this.tabledata = response.data.data;
				} else {
					_this.tabledata1 = [];
				}
			})
			.catch(function (error) {
				console.log(error);
				alert(error);
				_this.loadingbarerror();
			})
		},
		
		
		//
		onclear: function () {
			var _this = this;
			_this.jizhongming = '';
			_this.pinming = '';
			_this.mian_select = '';
			_this.diantai = '';
			_this.pinban = '';
			_this.boo_update = true;
		},
		
		// oncreate
		oncreate: function () {
			var _this = this;
			
			var jizhongming = _this.jizhongming;
			var pinming = _this.pinming;
			var gongxu = _this.mian_select;
			var diantai = _this.diantai;
			var pinban = _this.pinban;
			
			// var created_at = new Date().Format("yyyy-MM-dd");
			
			if (jizhongming == '' || pinming == '' || gongxu == '' || diantai == '' || pinban == ''
				|| jizhongming == undefined || pinming == undefined || gongxu == undefined || diantai == undefined || pinban == undefined) {
				_this.warning(false, 'Warning', 'Values are incorrect!');
				return false;
			}

			var url = "{{ route('smt.pdreport.mpointcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				jizhongming: jizhongming,
				pinming: pinming,
				gongxu: gongxu,
				diantai: diantai,
				pinban: pinban
				// created_at: created_at
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, 'Success', 'Created successfully!');
					_this.onclear();
					_this.mpointgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, 'Fail', 'Created failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Created error!');
				// console.log(error);
			})
		},
		
		// onupdate
		onupdate: function () {
			var _this = this;
			
			if (_this.mpointid == '') return false;
			
			var jizhongming = _this.jizhongming;
			var pinming = _this.pinming;
			var gongxu = _this.mian_select;
			var diantai = _this.diantai;
			var pinban = _this.pinban;
			
			var id = _this.mpointid;
			
			if (jizhongming == '' || pinming == '' || gongxu == '' || diantai == '' || pinban == ''
				|| jizhongming == undefined || pinming == undefined || gongxu == undefined || diantai == undefined || pinban == undefined) {
				_this.warning(false, 'Warning', 'Values are incorrect!');
				return false;
			}

			_this.boo_update = true;
			var url = "{{ route('smt.pdreport.mpointupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				jizhongming: jizhongming,
				pinming: pinming,
				gongxu: gongxu,
				diantai: diantai,
				pinban: pinban,
				id: id
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, 'Success', 'Updated successfully!');
					_this.onclear();
					_this.mpointgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, 'Fail', 'Updated failed!');
				}
				_this.mpointid = '';
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Updated error!');
				// console.log(error);
				_this.mpointid = '';
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
		},
		
		//
		ondelete: function (selection) {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) {
				return false;
			}

			var url = "{{ route('smt.pdreport.mpointdelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect: tableselect
			})
			.then(function (response) {
				if (response.data) {
					_this.success(false, 'Success', 'Deleted successfully!');
					_this.tableselect = [];
					_this.mpointgets(_this.pagecurrent, _this.pagelast);
				} else {
					_this.error(false, 'Fail', 'Deleted failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Deleted error!');
				// console.log(error);
			})
		},
		// 查看
		viewmpoint: function (row) {
			var _this = this;
			_this.jizhongming = row.jizhongming;
			_this.pinming = row.pinming;
			_this.mian_select = row.gongxu;
			_this.diantai = row.diantai;
			_this.pinban = row.pinban;
			_this.mpointid = row.id
			_this.boo_update = false;
		},
		
		//
		onimport: function () {
			alert();
		},
		
		// upload
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
		uploadstart: function (file) {
			var _this = this;
			_this.file = file;
			_this.uploaddisabled = true;
			_this.loadingStatus = true;

			
			let formData = new FormData()
			// formData.append('file',e.target.files[0])
			formData.append('myfile',_this.file)
			// console.log(formData.get('file'));
			
			// return false;
			
			var url = "{{ route('smt.pdreport.mpointimport') }}";
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
				// console.log(response.data);
				if (response.data == 1) {
					_this.success(false, 'Success', '导入成功！');
				} else {
					_this.error(false, 'Error', '导入失败！');
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
			
			// setTimeout( function () {
				// _this.file = null;
				// _this.loadingStatus = false;
				// _this.uploaddisabled = false;
			// }, 1500);
			
			// setTimeout(() => {
				// _this.file = null;
				// this.loadingStatus = false;
				// this.$Message.success('Success')
			// }, 1500);
		},
		uploadcancel: function () {
			this.file = null;
			// this.loadingStatus = false;
		},





		
	},
	mounted: function () {
		var _this = this;
		_this.mpointgets(1, 1); // page: 1, last_page: 1

	}
})
</script>
@endsection