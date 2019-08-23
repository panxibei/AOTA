<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>404 error</title>
	<style type="text/css">
		/* 解决闪烁问题的CSS */
		[v-cloak] {	display: none; }
	</style>
	<script src="{{ asset('js/vue.min.js') }}"></script>
	<script src="{{ asset('js/axios.min.js') }}"></script>
	<script src="{{ asset('js/bluebird.min.js') }}"></script>
</head>
<body>

<div style="text-align:center;" id="app" v-cloak>

	<font color="#035C98" style="font-size:36px;"><strong>ALPSALPINE</strong></font><br>
	<font color="#035C98" style="font-size:18px;">ALPS ALPINE GROUP</font><br><br>
	<font color="#ed4014" style="font-size:128px;"><strong>404</strong></font>
	<h3>很抱歉，您要访问的页面不存在！</h3>
	<span>@{{ time }} 秒后返回上一页</span>

</div>

<script>
var count = 3;
var vm_app = new Vue({
    el: '#app',
    data: {
		time: count
    },
	mounted: function(){
		var _this = this;
		setInterval(function () {
			_this.time=count;
			Vue.set([_this.time],'time',count);
			count--;
			if (count<=0) {
				count=0;
				window.history.go(-1);
			}
		},1000)
	}
});
</script>
</body>
</html>