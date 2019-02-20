@extends('main.layouts.mainbase')

@section('my_title')
Main(Config) - 
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
<strong>AOTA Management System - Config</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="9">

			<Card>
				<p slot="title">
					SMT管理系统配置
				</p>
				<p v-for="item in CardListSmt">
					&nbsp;&nbsp;@{{ item.title }}&nbsp;&nbsp;
					
					
					<i-input v-model.lazy="item.value" type="textarea" size="small" style="width: 160px"></i-input>
		
					&nbsp;&nbsp;&nbsp;&nbsp;

					

					&nbsp;&nbsp;
					<i-button type="default" size="small" @click="onupdate(item.name, item.value)">更新</i-button>
					
					<span style="float:right">
					&nbsp;
					<!--
					<i-select v-model.lazy="xianti" clearable style="width:80px" placeholder="">
						<i-option v-for="item in option_xianti" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
					-->

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
					部品加工管理系统配置
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
				value: '',
			},
			{
				title: '班次',
				name: 'banci',
				value: '',
			},
			{
				title: '品质日报',
				name: '品质日报',
				value: '',
			},
		],


		CardListBupinjiagong: [
			{
				name: '中日程分析',
				url: '#',
				hits: '???'
			},
		],
		
		
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
				
		// 把laravel返回的结果转换成select能接受的格式
		json2select: function (value) {
			var arr = value.split(/[\s\n]/);
			var arr_result = [];

			arr.map(function (v, i) {
				arr_result.push({ value: v, label: v });
			});

			return arr_result;
		},
		
		
		configgets: function () {
			var _this = this;


			var url = "{{ route('smt.configgets') }}";
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
					_this.CardListSmt = response.data;
					
					// 用于select形式显示
					// _this.CardListSmt.map(function (v, i) {
						// if (v.name == 'xianti') {
							// _this.option_xianti = _this.json2select(v.value);
						// }
						// else if (v.name == 'banci') {
							// _this.option_banci = _this.json2select(v.value);
						// }
						// else if (v.name == 'gongxu') {
							// _this.option_gongxu = _this.json2select(v.value);
						// }
						// else if (v.name == 'jianchajileixing') {
							// _this.option_jianchajileixing = _this.json2select(v.value);
						// }
						// else if (v.name == 'buliangneirong') {
							// _this.option_buliangneirong = _this.json2select(v.value);
						// }
						// else if (v.name == 'jianchazhe') {
							// _this.option_jianchazhe = _this.json2select(v.value);
						// }
					// });

				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},


		onupdate: function (name, value) {
			var _this = this;

			if (name == '' || name == undefined
				|| value == '' || value == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}

			var url = "{{ route('smt.configupdate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				name : name,
				value: value
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					_this.success(false, '成功', '更新成功！');
				} else {
					_this.error(false, '失败', '更新失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
				// console.log(error);
			})
		},		
		
		
			
			
	},
	mounted: function () {
		var _this = this;
		_this.configgets();
	}
})
</script>
@endsection