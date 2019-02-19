@extends('home.layouts.homebase')

@section('my_title')
Login - 
@parent
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_logo_and_title')
@parent
@endsection


@section('my_body')
@parent

<br><br><br>

<i-row :gutter="16">
	<i-col span="10">
		&nbsp;
	</i-col>
	<i-col span="4">
	
		<i-form ref="formInline" :model="formInline" :rules="ruleInline" @submit.native.prevent>
			<Form-item prop="username">
				<i-input ref="ref_username" type="text" v-model="formInline.username" @on-enter="handleSubmit('formInline')" placeholder="用户">
					<Icon type="ios-person-outline" slot="prepend"></Icon>
				</i-input>
			</Form-item>
		
			<Form-item prop="password">
				<i-input ref="ref_password" type="password" v-model="formInline.password" @on-enter="handleSubmit('formInline')" placeholder="密码">
					<Icon type="ios-lock-outline" slot="prepend"></Icon>
				</i-input>
			</Form-item>

			<i-row>
				<i-col span="16">
					<Form-item prop="captcha">
						<i-input ref="ref_captcha" type="text" v-model="formInline.captcha" @on-enter="handleSubmit('formInline')" placeholder="验证码" style="width:120px">
							<Icon type="ios-key-outline" slot="prepend"></Icon>
						</i-input>
					</Form-item>
				</i-col>
				<i-col span="8">
					<img ref="captcha" src="{{captcha_src('flatxz')}}" @click="captchaclick" style="cursor:pointer;vertical-align:top;">
				</i-col>
			</i-row>
			
			
			<br><br>
			
			<i-row>
				<i-col span="16">
					保持登录状态&nbsp;
					<i-switch ref="ref_rememberme" v-model="formInline.rememberme" size="small">
						<span slot="open"></span>
						<span slot="close"></span>
					</i-switch>
				</i-col>
				<i-col span="8">
				&nbsp;
				<!--待完成功能
					<a href="#">Forget?</a>
				-->
				</i-col>
			</i-row>
			
			
			<br><br><br>
			<Form-item>
			<i-button :disabled="disabled_login_submit" type="primary" @click="handleSubmit('formInline')">登 录</i-button>&nbsp;&nbsp;
			<i-button :disabled="disabled_login_reset" @click="handleReset('formInline')" style="margin-left: 8px">重 置</i-button>
			</Form-item>
			
			<div v-html="formInline.loginmessage">@{{ formInline.loginmessage }}</div>
		
		
		</i-form>
	</i-col>
	<i-col span="10">
		&nbsp;
	</i-col>
</i-row>

<br><br><br>

@endsection

@section('my_footer')
<br><br>
@parent
<br><br><br><br>
@endsection

@section('my_js_others')
<script>
// ajax 获取数据
var vm_app = new Vue({
    el: '#app',
    data: {
		
			formInline: {
				username: '',
				password: '',
				captcha: '',
				rememberme: false,
				loginmessage: ''
			},
			ruleInline: {
				username: [
					{ required: true, message: '请填写用户名', trigger: 'blur' }
				],
				password: [
					{ required: true, message: '请填写密码', trigger: 'blur' },
					{ type: 'string', min: 3, message: 'Password length is more than 3 bits', trigger: 'blur' }
				],
				captcha: [
					{ required: true, message: '请填写验证码', trigger: 'blur' },
					{ type: 'string', min: 3, message: 'The captcha length is 3 bits', trigger: 'blur' }
				]
			},

			disabled_login_submit: false,
			disabled_login_reset: false,
		
    },
	methods: {
		handleSubmit(name) {
			this.$refs[name].validate((valid) => {
				if (valid) {
					var _this = this;

					_this.logindisabled(true);
					_this.formInline.loginmessage = '<div class="text-info">正在验证... 稍等...</div>';

					if (_this.formInline.username == undefined || _this.formInline.password == undefined || _this.formInline.captcha == undefined ||
						_this.formInline.username == '' || _this.formInline.password == '' || _this.formInline.captcha == '') {
						_this.formInline.loginmessage = '<div class="text-warning">Please full the item</div>';
						_this.logindisabled(false);
						return false;
					}

					var url = "{{ route('login.checklogin') }}";
					axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
					axios.post(url, {
						name: _this.formInline.username,
						password: _this.formInline.password,
						captcha: _this.formInline.captcha,
						rememberme: _this.formInline.rememberme
					})
					.then(function (response) {
						// console.log(response.data);
						// return false;
						
						if (response.data) {

							if (response.data=='nosingleuser') {
								_this.formInline.loginmessage = '<font color="red">用户已在其他地方登录！ 请注销再试！</font>';
								_this.logindisabled(false);
								return false;
							}



							_this.formInline.password = '**********';
							_this.formInline.loginmessage = '<font color="blue">登录成功！ 请稍等...</font>';
							window.setTimeout(function(){
								_this.loginreset;
								var url = "{{ route('portal') }}";
								window.location.href = url;
								_this.formInline.loginmessage = '';
							}, 1000);
						} else {
							_this.formInline.loginmessage = '<font color="red">验证码错误或登录失败！</font>';
							_this.logindisabled(false);
						}
					})
					.catch(function (error) {
						// console.log(error);
						_this.formInline.loginmessage = '<font color="red">用户过期或未知错误！</font>';
						_this.logindisabled(false);
					})
					_this.captchaclick();
				} else {
					// this.$Message.error('Fail!');
				}
			})
		},
		handleReset (name) {
			this.$refs[name].resetFields();
		},
		captchaclick: function(){
			this.$refs.captcha.src+=Math.random().toString().substr(-1);
		},
		logindisabled: function (value) {
			var _this = this;
			if (value) {
				_this.$refs.ref_username.disabled = true;
				_this.$refs.ref_password.disabled = true;
				_this.$refs.ref_captcha.disabled = true;
				_this.$refs.ref_rememberme.disabled = true;
				_this.disabled_login_submit = true;
				_this.disabled_login_reset = true;
			} else {
				_this.$refs.ref_username.disabled = false;
				_this.$refs.ref_password.disabled = false;
				_this.$refs.ref_captcha.disabled = false;
				_this.$refs.ref_rememberme.disabled = false;
				_this.disabled_login_submit = false;
				_this.disabled_login_reset = false;
			}
		},
	}
});
</script>
@endsection