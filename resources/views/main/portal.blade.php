@extends('main.layouts.mainbase')

@section('my_title')
Main(Portal) - 
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
<strong>AOTA Management System - Portal</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="6">

			<Card>
				<p slot="title">
					SMT实装课
					@hasanyrole('role_smt_config_write|role_super_admin')
					<span style="float:right">
						<a href="{{ route('smt.config') }}" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;Config</a>
					</span>
					@endhasanyrole
				</p>
				<p v-for="item in CardListSmt">
					<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
					<span style="float:right">
						Progress: @{{ item.percent }}%
					</span>
				</p>
			</Card>

		</i-col>
		
		<i-col span="1">
		&nbsp;
		</i-col>
		
		<i-col span="6">
		
			<Card>
				<p slot="title">
					部品加工课
				</p>
					<p v-for="item in CardListBupinjiagong">
						<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
						<span style="float:right">
							Progress: @{{ item.percent }}%
						</span>
					</p>
			</Card>
		
		</i-col>
		
		<i-col span="1">
		&nbsp;
		</i-col>
		
		<i-col span="6">
		
			<Card>
				<p slot="title">
					生产管理课
					@hasanyrole('role_scgl_config|role_super_admin')
					<span style="float:right">
						<a href="{{ route('scgl.config') }}" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;Config</a>
					</span>
					@endhasanyrole
				</p>
					<p v-for="item in CardListShengchanguanli">
						<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
						<span style="float:right">
							Progress: @{{ item.percent }}%
						</span>
					</p>
			</Card>
		
		</i-col>

		<i-col span="4">
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

		CardListSmt: [
			{
				name: 'Mpoint (GA)',
				url: "{{ route('smt.pdreport.mpoint') }}", //'http://172.22.15.199:8888/smt/mpoint',
				percent: 100,
			},
			{
				name: '生产日报 (GA)',
				url: "{{ route('smt.pdreport.index') }}",
				percent: 100,
			},
			{
				name: '品质日报 (RC)',
				url: "{{ route('smt.qcreport.index') }}",
				percent: 98,
			},
			{
				name: '网板管理 (RC)',
				url: "{{ route('smt.wbgl.index') }}",
				percent: 60,
			},
		],

		CardListBupinjiagong: [
			{
				name: '中日程分析 (GA)',
				url: "{{ route('bpjg.zrcfx.index') }}",
				percent: 100,
			},
		],

		CardListShengchanguanli: [
			{
				name: '耗材分析 (GA)',
				url: "{{ route('scgl.hcfx.index') }}",
				percent: 100,
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
		
		

		

		
		
		
		
		
			
			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
	}
})
</script>
@endsection