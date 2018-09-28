<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>
@section('my_title')

@show
</title>
<link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">
<style type="text/css">
	/* 解决闪烁问题的CSS */
	[v-cloak] {	display: none; }
</style>
<style type="text/css">
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-header-bar{
	background: #fff;
	box-shadow: 0 1px 1px rgba(0,0,0,.1);
}
.layout-logo{
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    left: 20px;
}
.layout-breadcrumb{
	<!-- padding: 10px 15px 0; -->
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 5px;
    left: 20px;
}
.layout-nav{
	float: right;
	position: relative;
    width: 420px;
    margin: 0 auto;
    margin-right: 10px;
}
.layout-footer-center{
    text-align: center;
}
.ivu-table-cell{
	font-size: 12px;
}
</style>
@yield('my_style')

<script src="{{ asset('js/functions.js') }}"></script>
@yield('my_js')
</head>
<body>
<div id="app" v-cloak>

    <div class="layout">
        <Layout>
			
			<Layout>
            <!--头部导航-->
			<div style="z-index: 999;">
            <Header :style="{position: 'fixed', width: '100%', marginLeft: '0px'}">
                <Layout>
				<i-menu mode="horizontal" theme="light" active-name="3">
					
					<!--面包屑-->
					<div class="layout-breadcrumb">
					@section('my_project')

					@show
					
					</div>

                </i-menu>
				</Layout>

            </Header>
			</div>
			</Layout>
            
			
			<div><br><br><br><br></div>
			<Layout :style="{padding: '0 12px 24px', marginLeft: '0px'}">
				<!--内容主体-->
				<Content :style="{padding: '24px 12px', minHeight: '280px', background: '#fff'}">
				<!-- 主体 -->
				@section('my_body')
				@show
				<!-- /主体 -->

				</Content>
			</Layout>

 			<!-- 底部 -->
			<Footer class="layout-footer-center">
			@section('my_footer')
			<a href="">AOTA Management System Beta</a>&nbsp;|&nbsp;Copyright &copy; 2018 AOTA All Rights Reserved.
			@show
			</Footer>
			<!-- /底部 -->
			
        </Layout>
		<!-- 返回顶部 -->
		<Back-top></Back-top>
    </div>

</div>

<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>
<script src="{{ asset('statics/echarts/echarts.js') }}"></script>
@yield('my_js_others')
</body>
</html>
