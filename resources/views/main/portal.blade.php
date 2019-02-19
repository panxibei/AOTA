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
					SMT管理系统（Beta版）
					@hasanyrole('role_smt_config|role_super_admin')
					<span style="float:right">
						<a href="{{ route('smt.config') }}" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;Config</a>
					</span>
					@endcan
				</p>
					<p v-for="item in CardListSmt">
						<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
						<span style="float:right">
							Percent: @{{ item.percent }}%
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
					部品加工管理系统（Beta版）
				</p>
					<p v-for="item in CardListBupinjiagong">
						<a :href="item.url" target="_blank"><Icon type="ios-link"></Icon>&nbsp;&nbsp;@{{ item.name }}</a>
						<span style="float:right">
							Percent: @{{ item.percent }}%
						</span>
					</p>
			</Card>
		
		</i-col>
		<i-col span="5">
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
		CardListSmt: [
			{
				name: 'Mpoint',
				url: "{{ route('smt.pdreport.mpoint') }}", //'http://172.22.15.199:8888/smt/mpoint',
				percent: 85,
			},
			{
				name: '生产日报',
				url: "{{ route('smt.pdreport.index') }}",
				percent: 75,
			},
			{
				name: '品质日报',
				url: "{{ route('smt.qcreport.index') }}",
				percent: 90,
			},
		],


		CardListBupinjiagong: [
			{
				name: '中日程分析',
				url: "{{ route('bpjg.zrcfx.index') }}",
				percent: 95,
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
		
		
		// qcreport列表
		qcreportgets: function(){
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

			var url = "{{ route('smt.qcreport.qcreportgets') }}";
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
		

		
		
		
		
		
			
			
	},
	mounted: function () {
		// var _this = this;
		// _this.qcdate_filter = new Date().Format("yyyy-MM-dd");
		// _this.qcreportgets(1, 1); // page: 1, last_page: 1
	}
})
</script>
@endsection