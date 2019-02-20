@extends('main.layouts.mainbase')

@section('my_title')
Main(Releases) - 
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
<strong>AOTA Management System - Releases</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="11">

			<Card>
				<p slot="title">
					系统日志
				</p>
                <Scroll :on-reach-bottom="handleReachBottom" distance-to-edge=5 height="400">
                    <Collapse simple v-for="(item, index) in list_release">
                        <Panel name="@{{ index }}">
                            @{{ item.title }}
                            <p slot="content">
                            @{{ item.content }}
                            </p>
                        </Panel>
                    </Collapse>
                    <br><div style='text-align:center;'>@{{ release_message }}</div>
                </Scroll>
			</Card>

		</i-col>
		
		<i-col span="1">
		&nbsp;
		</i-col>
		
		<i-col span="6">
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
		
        list_release: [],
		
        release_offset: 0,
        release_message: '向下滚动加载更多',
			
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

		// release列表
		releasegets: function () {
			var _this = this;
			var url = "{{ route('release.releasegets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					offset: _this.release_offset,
				}
			})
			.then(function (response) {
				// console.log(response.data[0]);
				// console.log(response.data[0]=='');
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

                if (response.data[0]==undefined) {
                    _this.release_message = '-------- 我是有底线的 --------';
                }
				
				if (response.data) {
					// _this.list_release = response.data;
					_this.list_release = _this.list_release.concat(response.data);
				} else {
					_this.list_release = [];
				}
				
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},

		handleReachBottom () {
            var _this = this;
			return new Promise(resolve => {
				setTimeout(() => {
					// const last = this.list1[this.list1.length - 1];
					// for (let i = 1; i < 21; i++) {
					// 	this.list1.push(last + i);
					// }
                    _this.release_offset+=10;
                    _this.releasegets();
					resolve();
				}, 2000);
			});
		},







	},
	mounted: function () {
		var _this = this;
		_this.releasegets();
	}
})
</script>
@endsection