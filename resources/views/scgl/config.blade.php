@extends('scgl.layouts.mainbase')

@section('my_title')
生产管理课 (Config) - 
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
<strong>AOTA Management System - 生产管理课 Config</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="9">

			<Card>
				<p slot="title">
					耗材管理配置
				</p>
				<p v-for="item in CardListHcgl">
					&nbsp;&nbsp;@{{ item.title }}&nbsp;&nbsp;
					
					
					<i-input v-model.lazy="item.value" type="textarea" size="small" style="width: 160px"></i-input>
		
					&nbsp;&nbsp;&nbsp;&nbsp;

					

					&nbsp;&nbsp;
					<i-button type="default" size="small" @click="onupdate(item.name, item.value)">更新</i-button>
					
					<span style="float:right">
					&nbsp;

					</span>
					<br><br>
				</p>
			</Card>

		</i-col>
		
		<i-col span="1">
		&nbsp;
		</i-col>
		
		<i-col span="6">
		&nbsp;
		
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
		
		CardListHcgl: [
			// {
			// 	title: '线体',
			// 	name: 'xianti',
			// 	value: '',
			// },
			// {
			// 	title: '班次',
			// 	name: 'banci',
			// 	value: '',
			// },
			// {
			// 	title: '品质日报',
			// 	name: '品质日报',
			// 	value: '',
			// },
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
		
		alert_logout () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},
				
		configgets () {
			var _this = this;

			var url = "{{ route('scgl.configgets') }}";
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
					_this.CardListHcgl = response.data;
				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},


		onupdate (name, value) {
			var _this = this;

			if (name == '' || name == undefined
				|| value == '' || value == undefined) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}

			var url = "{{ route('scgl.configupdate') }}";
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
			})
		},		
		
		
			
			
	},
	mounted () {
		var _this = this;
		_this.configgets();
	}
})
</script>
@endsection