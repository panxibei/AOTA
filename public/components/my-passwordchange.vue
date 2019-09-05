<template>
	<div>
	
		<Modal ref="ref1" v-model="vm_app.modal_password_edit" @on-ok="password_edit_ok()" ok-text="更新" title="修改密码" width="280">
			<div style="text-align:left">
			<p>
				旧密码&nbsp;&nbsp;
				<i-input v-model.lazy="password_old" clearable style="width: 180px" maxlength="20" placeholder="" type="password"></i-input>

				<br><br>

				新密码&nbsp;&nbsp;
				<i-input v-model.lazy="password_new" clearable style="width: 180px" maxlength="20" placeholder="" type="password"></i-input>

				<br><br>

				再确认&nbsp;&nbsp;
				<i-input v-model.lazy="password_confirm" clearable style="width: 180px" maxlength="20" placeholder="" type="password"></i-input>
				
				<br><br>

				* 使用OA网用户登录，密码会同步覆盖本系统密码。

			</p>
			&nbsp;
			</div>	
		</Modal>

	</div>
</template>
 
<script>
	module.exports = {
		data: function () {
			return {
				// 修改密码界面
				// modal_password_edit: true,
				password_old: '',
				password_new: '',
				password_confirm: '',

			}
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

			password_edit_ok () {
				// alert('vue');return false;
				var _this = this;
				var password_old = _this.password_old;
				var password_new = _this.password_new;
				var password_confirm = _this.password_confirm;

				var flag = false;
				if (password_old == '' || password_new == '' || password_confirm == '' ||
					password_old == null || password_new == null || password_confirm == null ||
					password_old == undefined || password_new == undefined || password_confirm == undefined) {
					_this.warning(false, '警告', '内容不能为空！');
					flag = true;
				} else if (password_new.length < 8) {
					_this.warning(false, '警告', '新密码不能小于8位！');
					flag = true;
				} else if (password_new != password_confirm) {
					_this.warning(false, '警告', '新密码与再确认密码不同！');
					flag = true;
				} else if (password_old == password_new) {
					_this.warning(false, '警告', '新密码与旧密码相同！');
					flag = true;
				}

				if (flag == true) {
					_this.password_old = '';
					_this.password_new = '';
					_this.password_confirm = '';
					return false;
				}

				// tightenco/ziggy
				var url = route('admin.password.change').template;
				axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
				axios.post(url, {
					password_old: password_old,
					password_new: password_new,
					password_confirm: password_confirm,
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						_this.success(false, '成功', '修改密码成功！');
					} else {
						_this.warning(false, '失败', '修改密码失败！');
					}
					_this.password_old = '';
					_this.password_new = '';
					_this.password_confirm = '';

				})
				.catch(function (error) {
					_this.error(false, '错误', '修改密码失败！');
				})

			},
		}
	}
</script>

<style scoped>

</style>