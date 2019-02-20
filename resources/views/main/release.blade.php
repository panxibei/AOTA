@extends('main.layouts.mainbase')

@section('my_title')
Main(Logs) - 
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
<strong>AOTA Management System - Logs</strong>
@endsection

@section('my_body')
@parent

<div id="app" v-cloak>

	<i-row :gutter="16">
		<i-col span="11">

			<Card>
				<p slot="title">
					SMT管理系统配置
				</p>
                <Scroll :on-reach-bottom="handleReachBottom" distance-to-edge=5 height="200">
                    <Collapse simple v-for="(item, index) in list_log">
                        <Panel name="@{{ index }}">
                            @{{ item.label }}
                            <p slot="content">
                            @{{ item.value }}
                            </p>
                        </Panel>
                    </Collapse>
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
		
        list_log: [
            {
                label: '拙政园1',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园2',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园3',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园4',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园5',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园6',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园7',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园8',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园9',
                value: 'zhuozhengyuan',
            },
            {
                label: '拙政园10',
                value: 'zhuozhengyuan',
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
		
		alert_logout: function () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},		



		handleReachBottom () {
			return new Promise(resolve => {
				setTimeout(() => {
					const last = this.list1[this.list1.length - 1];
					for (let i = 1; i < 21; i++) {
						this.list1.push(last + i);
					}
					resolve();
				}, 2000);
			});
		},







	},
	mounted: function () {
	}
})
</script>
@endsection