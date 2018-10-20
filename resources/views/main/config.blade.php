@extends('main.layouts.mainbase')

@section('my_title')
AOTA Management System Beta - Config 
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
<strong>AOTA Management System Beta - Portal</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="9">

			<Card>
				<p slot="title">
					SMT管理系统配置（Beta版）
				</p>
					<p v-for="item in CardListSmt">
						&nbsp;&nbsp;@{{ item.title }}&nbsp;&nbsp;
						
						<i-select v-model.lazy="item.select" clearable size="small" style="width:80px" placeholder="">
							<i-option v-for="item in item.option" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
						</i-select>
			
						&nbsp;&nbsp;&nbsp;&nbsp;

						<span style="float:right">
							<i-input v-model.lazy="item.input" size="small" clearable style="width: 80px"></i-input>

							&nbsp;&nbsp;
							<Input-number v-model.lazy="item.position" :min="1" :max="50" size="small" style="width: 50px"></Input-number>
							<i-button type="default" size="small" @click="oninsert(item.position, item.name, item.input)" v-if="item.type=='select'">插入</i-button>
							&nbsp;
							<i-button type="default" size="small" @click="onupdate">更新</i-button>
						
						</span>
						<br><br>
					</p>
			</Card>

		</i-col>
		
		<i-col span="1">
		&nbsp;
		</i-col>
		
		<i-col span="6">
		
			<Card>
				<p slot="title">
					部品加工管理系统（Beta版）
				</p>
					<p v-for="item in CardListBupinjiagong">
						<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
						<span style="float:right">
							Hits: @{{ item.hits }}
						</span>
					</p>
			</Card>
		
		</i-col>
		<i-col span="2">
		&nbsp;
		</i-col>
	</i-row>

	<br><br><br>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	<p><br></p><p><br></p><p><br></p>
	


</div>
@endsection

@section('my_js_others')
@parent	
<script>
var vm_app = new Vue({
	el: '#app',
	data: {
		
		// 线体

		
		CardListSmt: [
			{
				title: '线体',
				name: 'xianti',
				type: 'select',
				position: 1,
				select: '',
				option: [
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
				input: '',
			},
			{
				title: '班次',
				name: 'banci',
				type: 'select',
				position: 1,
				select: '',
				option: [
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
				input: '',
			},
			{
				title: '品质日报',
				name: '品质日报',
				type: 'select',
				position: 1,
				select: '',
				option: [
					{
						value: 'SMT-1',
						label: 'SMT-1'
					},
				],
				input: '',
			},
		],


		CardListBupinjiagong: [
			{
				name: '中日程分析',
				url: '#',
				hits: '???'
			},
		],
		
		
		
			
			
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
		
		
		configgets: function () {
			var _this = this;


			var url = "{{ route('config.configgets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
				}
			})
			.then(function (response) {
				if (response.data) {
					console.log(response.data);
					
					var a = response.data;
					
					a.map(function (v,i) {
						v.name = '';
						v.value = '';
						v.type = '';
					});
					
				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},
		
		
		oninsert: function (position, name, value) {
			var _this = this;
			
			if (name == '' || name == undefined
				|| value == '' || value == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}

			var url = "{{ route('config.create') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				position: position,
				name : name,
				value: value
			})
			.then(function (response) {
				console.log(response.data);
				return false;
				
				if (response.data) {
					_this.success(false, '成功', '记入成功！');

				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},
		

		onupdate: function (name, value) {
			var _this = this;
		
		},
		
		
			
			
	},
	mounted: function () {
		var _this = this;
		_this.configgets();
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
		// _this.qcreportgets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection