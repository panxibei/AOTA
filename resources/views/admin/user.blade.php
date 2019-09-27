@extends('admin.layouts.adminbase')

@section('my_title')
Admin(User) - 
@parent
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_body')
@parent

<Divider orientation="left">User Management</Divider>

<Tabs type="card" v-model="currenttabs">
	<Tab-pane label="User List">
	
		<Collapse v-model="collapse_query">
			<Panel name="1">
				User Query Filter
				<p slot="content">
				
					<i-row :gutter="16">
						<i-col span="7">
							* login time&nbsp;&nbsp;
							<Date-picker v-model.lazy="queryfilter_logintime" @on-change="usergets(page_current, page_last);onselectchange();" type="daterange" size="small" placement="top" style="width:180px"></Date-picker>
						</i-col>
						<i-col span="5">
							login name&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_name" @on-change="usergets(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="4">
							email&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_email" @on-change="usergets(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="4">
							login ip&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_loginip" @on-change="usergets(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="4">
							&nbsp;
						</i-col>
					</i-row>

					<br><br>
					<i-row :gutter="16">
						<i-col span="6">
							display name&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_displayname" @on-change="usergets(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="6">
							department&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_department" @on-change="usergets(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="12">
							<i-switch v-model.lazy="queryfilter_disableduser" @on-change="usergets(page_current, page_last)" size="small"></i-switch>&nbsp;&nbsp;disabled user(s)
						</i-col>
					</i-row>
				
				
				&nbsp;
				</p>
			</Panel>
		</Collapse>
		<br>
		
		<i-row :gutter="16">
			<br>
			<i-col span="3">
				<i-button @click="ondelete_user()" :disabled="delete_disabled_user" type="warning" size="small">Delete</i-button>&nbsp;<br>&nbsp;
			</i-col>
			<i-col span="2">
				<i-button type="default" size="small" @click="oncreate_user()"><Icon type="ios-color-wand-outline"></Icon> 新建用户</i-button>
			</i-col>
			<i-col span="2">
				<i-button type="default" size="small" @click="onexport_user()"><Icon type="ios-download-outline"></Icon> 导出用户</i-button>
			</i-col>
			<i-col span="2">
				&nbsp;
			</i-col>
			<i-col span="15">
				<Tooltip content="输入角色选择" placement="top">
					<i-select v-model.lazy="give_role_select" filterable remote :remote-method="remoteMethod_sync_role" :loading="give_role_loading" @on-change="" clearable placeholder="输入角色" :disabled="disabled_give_role_input" style="width: 200px;" size="small">
						<i-option v-for="item in give_role_options" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</Tooltip>
				&nbsp;&nbsp;
				<i-button type="default" size="small" @click="giveroletouser()" :disabled="disabled_give_role_button"><Icon type="ios-sync"></Icon> 赋予角色到用户</i-button>
			</i-col>
		</i-row>
		
		<i-row :gutter="16">
			<i-col span="24">
	
				<i-table height="300" size="small" border :columns="tablecolumns" :data="tabledata" @on-selection-change="selection => onselectchange(selection)"></i-table>
				<br><Page :current="page_current" :total="page_total" :page-size="page_size" @on-change="currentpage => oncurrentpagechange(currentpage)" @on-page-size-change="pagesize => onpagesizechange(pagesize)" :page-size-opts="[5, 10, 20, 50]" show-total show-elevator show-sizer></Page>
			
				<Modal v-model="modal_user_add" @on-ok="oncreate_user_ok" ok-text="新建" title="Create - User" width="460">
					<div style="text-align:left">
						
						<p>
							name&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_name" placeholder="" size="small" clearable style="width: 120px"></i-input>

							&nbsp;&nbsp;&nbsp;&nbsp;

							email&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_email" placeholder="" size="small" clearable style="width: 200px" type="email"></i-input>
							
							<br><br>

<!-- 							ldapname&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_ldapname" placeholder="" size="small" clearable style="width: 120px"></i-input>

							&nbsp;&nbsp;&nbsp;&nbsp;
 -->							
							displayname&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_displayname" placeholder="" size="small" clearable style="width: 120px"></i-input>
							
							&nbsp;&nbsp;&nbsp;&nbsp;

							department&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_department" placeholder="" size="small" clearable style="width: 120px"></i-input>
							<br><br>

							password&nbsp;&nbsp;
							<i-input v-model.lazy="user_add_password" placeholder="" size="small" clearable style="width: 120px" type="password"></i-input>
							&nbsp;*默认密码为12345678

						</p>
						
						&nbsp;
					
					</div>	
				</Modal>
				
				<Modal v-model="modal_user_edit" @on-ok="user_edit_ok" ok-text="保存" title="Edit - User" width="460">
					<div style="text-align:left">
						
						<p>
							name&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_name" placeholder="" size="small" clearable style="width: 120px"></i-input>

							&nbsp;&nbsp;&nbsp;&nbsp;

							email&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_email" placeholder="" size="small" clearable style="width: 200px" type="email"></i-input>
							
							<br><br>

<!-- 							ldapname&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_ldapname" placeholder="" size="small" clearable style="width: 120px"></i-input>

							&nbsp;&nbsp;&nbsp;&nbsp;
 -->
							displayname&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_displayname" placeholder="" size="small" clearable style="width: 120px"></i-input>
 
							&nbsp;&nbsp;&nbsp;&nbsp;

							department&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_department" placeholder="" size="small" clearable style="width: 120px"></i-input>
							
							<br><br>

							password&nbsp;&nbsp;
							<i-input v-model.lazy="user_edit_password" placeholder="不修改密码请留空" size="small" clearable style="width: 120px" type="password"></i-input>

						</p>
						
						&nbsp;
					
					</div>	
				</Modal>
		
			</i-col>
		</i-row>

	
	</Tab-pane>

	<Tab-pane label="Create/Edit Template">
	


	</Tab-pane>

</Tabs>

<!-- <my-passwordchange :modal_password_edit.sync="modal_password_edit"></my-passwordchange> -->
<my-passwordchange></my-passwordchange>

@endsection

@section('my_footer')
@parent

@endsection

@section('my_js_others')
@parent
<script type="text/javascript">
// Vue.config.devtools = true;
var vm_app = new Vue({
    el: '#app',
	components: {
		'my-passwordchange': httpVueLoader("{{ asset('components/my-passwordchange.vue') }}")
	},
    data: {
		current_nav: '',
		current_subnav: '',
		
		sideractivename: '3-1',
		sideropennames: ['3'],

		// 修改密码界面
		modal_password_edit: false,

		tablecolumns: [
			{
				type: 'selection',
				width: 60,
				align: 'center',
				fixed: 'left'
			},
			{
				type: 'index',
				align: 'center',
				width: 60,
			},
			{
				title: 'id',
				key: 'id',
				sortable: true,
				width: 80
			},
			{
				title: 'name',
				key: 'name',
				width: 130
			},
			{
				title: 'ldapname',
				key: 'ldapname',
				width: 130
			},
			{
				title: 'email',
				key: 'email',
				width: 240
			},
			{
				title: 'displayname',
				key: 'displayname',
				width: 180
			},
			{
				title: 'department',
				key: 'department',
				width: 180
			},
			{
				title: 'login TTL',
				key: 'login_ttl',
				align: 'center',
				width: 100
			},
			{
				title: 'login IP',
				key: 'login_ip',
				width: 130
			},
			{
				title: 'counts',
				key: 'login_counts',
				align: 'center',
				sortable: true,
				width: 100
			},
			{
				title: 'login time',
				key: 'login_time',
				width: 160
			},
			{
				title: 'status',
				key: 'deleted_at',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						// params.row.deleted_at.toLocaleString()
						// params.row.deleted_at ? '禁用' : '启用'
						
						h('i-switch', {
							props: {
								type: 'primary',
								size: 'small',
								value: ! params.row.deleted_at
							},
							style: {
								marginRight: '5px'
							},
							on: {
								'on-change': (value) => {//触发事件是on-change,用双引号括起来，
									//参数value是回调值，并没有使用到
									vm_app.trash_user(params.row.id) //params.index是拿到table的行序列，可以取到对应的表格值
								}
							}
						}, 'Edit')
						
					]);
				}
			},
			{
				title: 'created_at',
				key: 'created_at',
				width: 160
			},
			{
				title: 'Action',
				key: 'action',
				align: 'center',
				width: 140,
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'primary',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.user_edit(params.row)
								}
							}
						}, 'Edit'),
						h('Button', {
							props: {
								type: 'primary',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.user_clsttl(params.row)
								}
							}
						}, 'ClsTTL')
					]);
				},
				fixed: 'right'
			}
		],
		tabledata: [],
		tableselect: [],
		
		//分页
		page_current: 1,
		page_total: 1, // 记录总数，非总页数
		page_size: {{ $config['PERPAGE_RECORDS_FOR_USER'] }},
		page_last: 1,		
		
		// 创建
		modal_user_add: false,
		user_add_id: '',
		user_add_name: '',
		user_add_ldapname: '',
		user_add_email: '',
		user_add_displayname: '',
		user_add_department: '',
		user_add_password: '',
		
		// 编辑
		modal_user_edit: false,
		user_edit_id: '',
		user_edit_name: '',
		user_edit_ldapname: '',
		user_edit_email: '',
		user_edit_displayname: '',
		user_edit_department: '',
		user_edit_password: '',
		
		// 删除
		delete_disabled_user: true,
		
		// 赋予权限到指定用户
		disabled_give_role_input: true,
		disabled_give_role_button: true,

		// tabs索引
		currenttabs: 0,
		
		// 查询过滤器
		queryfilter_name: "{{ $config['FILTERS_USER_NAME'] }}",
		queryfilter_email: "{{ $config['FILTERS_USER_EMAIL'] }}",
		queryfilter_logintime: "{{ $config['FILTERS_USER_LOGINTIME'] }}" || [],
		queryfilter_loginip: "{{ $config['FILTERS_USER_LOGINIP'] }}",
		queryfilter_displayname: '',
		queryfilter_department:  "{{ $config['FILTERS_USER_DEPARTMENT'] }}",
		queryfilter_disableduser:  "{{ $config['FILTERS_USER_DISABLEDUSER'] }}" || false,
		
		// 查询过滤器下拉
		collapse_query: '',
		
		// 用户赋予角色
		give_role_select: '',
		give_role_options: [],
		give_role_loading: false,

		
    },
	methods: {
		menuselect: function (name) {
			navmenuselect(name);
		},
		// 1.加载进度条
		loadingbarstart () {
			this.$Loading.start();
		},
		loadingbarfinish () {
			this.$Loading.finish();
		},
		loadingbarerror () {
			this.$Loading.error();
		},
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

		// 把laravel返回的结果转换成select能接受的格式
		json2selectvalue: function (json) {
			var arr = [];
			for (var key in json) {
				// alert(key);
				// alert(json[key]);
				// arr.push({ obj.['value'] = key, obj.['label'] = json[key] });
				arr.push({ value: key, label: json[key] });
			}
			return arr;
			// return arr.reverse();
		},

		// 移至 public/components/my-passwordchange.vue
		// password_edit_ok () {
		// 	var _this = this;
		// 	var password_old = _this.password_old;
		// 	var password_new = _this.password_new;
		// 	var password_confirm = _this.password_confirm;

		// 	var flag = false;
		// 	if (password_old == '' || password_new == '' || password_confirm == '' ||
		// 		password_old == null || password_new == null || password_confirm == null ||
		// 		password_old == undefined || password_new == undefined || password_confirm == undefined) {
		// 		_this.warning(false, '警告', '内容不能为空！');
		// 		flag = true;
		// 	} else if (password_new.length < 8) {
		// 		_this.warning(false, '警告', '新密码不能小于8位！');
		// 		flag = true;
		// 	} else if (password_new != password_confirm) {
		// 		_this.warning(false, '警告', '新密码与再确认密码不同！');
		// 		flag = true;
		// 	} else if (password_old == password_new) {
		// 		_this.warning(false, '警告', '新密码与旧密码相同！');
		// 		flag = true;
		// 	}

		// 	if (flag == true) {
		// 		_this.password_old = '';
		// 		_this.password_new = '';
		// 		_this.password_confirm = '';
		// 		return false;
		// 	}

		// 	var url = "{{ route('admin.password.change') }}";
		// 	axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
		// 	axios.post(url, {
		// 		password_old: password_old,
		// 		password_new: password_new,
		// 		password_confirm: password_confirm,
		// 	})
		// 	.then(function (response) {
		// 		if (response.data['jwt'] == 'logout') {
		// 			_this.alert_logout();
		// 			return false;
		// 		}
				
		// 		if (response.data) {
		// 			_this.success(false, '成功', '修改密码成功！');
		// 		} else {
		// 			_this.warning(false, '失败', '修改密码失败！');
		// 		}
		// 		_this.password_old = '';
		// 		_this.password_new = '';
		// 		_this.password_confirm = '';

		// 	})
		// 	.catch(function (error) {
		// 		_this.error(false, '错误', '修改密码失败！');
		// 	})

		// },
		
		// 切换当前页
		oncurrentpagechange (currentpage) {
			this.usergets(currentpage, this.page_last);
		},
		// 切换页记录数
		onpagesizechange (pagesize) {
			
			var _this = this;
			var cfg_data = {};
			cfg_data['PERPAGE_RECORDS_FOR_USER'] = pagesize;
			var url = "{{ route('admin.config.change') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				cfg_data: cfg_data
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.page_size = pagesize;
					_this.usergets(1, _this.page_last);
				} else {
					_this.warning(false, 'Warning', 'failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'failed!');
			})
		},
		
		usergets: function(page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var queryfilter_logintime = [];

			for (var i in _this.queryfilter_logintime) {
				if (typeof(_this.queryfilter_logintime[i])!='string') {
					queryfilter_logintime.push(_this.queryfilter_logintime[i].Format("yyyy-MM-dd"));
				} else if (_this.queryfilter_logintime[i] == '') {
					// queryfilter_logintime.push(new Date().Format("yyyy-MM-dd"));
					// _this.tabledata = [];
					// return false;
					queryfilter_logintime = ['1970-01-01', '9999-12-31'];
					break;
				} else {
					queryfilter_logintime.push(_this.queryfilter_logintime[i]);
				}
			}
			// console.log(queryfilter_logintime);

			var queryfilter_name = _this.queryfilter_name;
			var queryfilter_email = _this.queryfilter_email;
			var queryfilter_displayname = _this.queryfilter_displayname;
			var queryfilter_loginip = _this.queryfilter_loginip;
			var queryfilter_department = _this.queryfilter_department;
			var queryfilter_disableduser = _this.queryfilter_disableduser ? 1 : 0;

			_this.loadingbarstart();
			var url = "{{ route('admin.user.list') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.page_size,
					page: page,
					queryfilter_name: queryfilter_name,
					queryfilter_logintime: queryfilter_logintime,
					queryfilter_email: queryfilter_email,
					queryfilter_displayname: queryfilter_displayname,
					queryfilter_loginip: queryfilter_loginip,
					queryfilter_department: queryfilter_department,
					queryfilter_disableduser: queryfilter_disableduser,
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.delete_disabled_user = true;
					_this.tableselect = [];

					_this.page_current = response.data.current_page;
					_this.page_total = response.data.total;
					_this.page_last = response.data.last_page;
					_this.tabledata = response.data.data;
				}
				
				_this.loadingbarfinish();
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},		
		
		// 表user选择
		onselectchange: function (selection) {
			var _this = this;
			_this.tableselect = [];

			for (var i in selection) {
				_this.tableselect.push(selection[i].id);
			}
			
			_this.delete_disabled_user = _this.tableselect[0] == undefined ? true : false;
			_this.disabled_give_role_input = _this.tableselect[0] == undefined ? true : false;
			_this.disabled_give_role_button = _this.tableselect[0] == undefined ? true : false;

		},
		
		// user编辑前查看
		user_edit: function (row) {
			var _this = this;
			
			_this.user_edit_id = row.id;
			_this.user_edit_name = row.name;
			// _this.user_edit_ldapname = row.ldapname;
			_this.user_edit_email = row.email;
			_this.user_edit_displayname = row.displayname;
			_this.user_edit_department = row.department;
			// _this.user_edit_password = row.password;
			// _this.relation_xuqiushuliang_edit[0] = row.xuqiushuliang;
			// _this.relation_xuqiushuliang_edit[1] = row.xuqiushuliang;
			// _this.user_created_at_edit = row.created_at;
			// _this.user_updated_at_edit = row.updated_at;

			_this.modal_user_edit = true;
		},		
		

		// user编辑后保存
		user_edit_ok: function () {
			var _this = this;
			
			var id = _this.user_edit_id;
			var name = _this.user_edit_name;
			// var ldapname = _this.user_edit_ldapname;
			var email = _this.user_edit_email;
			var displayname = _this.user_edit_displayname;
			var department = _this.user_edit_department;
			var password = _this.user_edit_password;
			// var created_at = _this.relation_created_at_edit;
			// var updated_at = _this.relation_updated_at_edit;
			
			if (name == '' || name == null || name == undefined
				// || ldapname == '' || ldapname == null || ldapname == undefined
				|| email == '' || email == null || email == undefined
				|| displayname == '' || displayname == null || displayname == undefined
				|| department == '' || department == null || department == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			var regexp = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/;
			if (! regexp.test(email)) {
				_this.warning(false, 'Warning', 'Email is incorrect!');
				return false;
			}
			
			var url = "{{ route('admin.user.update') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				name: name,
				// ldapname: ldapname,
				email: email,
				displayname: displayname,
				department: department,
				password: password,
				// xuqiushuliang: xuqiushuliang[1],
				// created_at: created_at,
				// updated_at: updated_at
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.usergets(_this.page_current, _this.page_last);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.user_edit_id = '';
					_this.user_edit_name = '';
					// _this.user_edit_ldapname = '';
					_this.user_edit_email = '';
					_this.user_edit_displayname = '';
					_this.user_edit_department = '';
					_this.user_edit_password = '';
					
					// _this.relation_xuqiushuliang_edit = [0, 0];
					// _this.relation_created_at_edit = '';
					// _this.relation_updated_at_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},		
		
		// ondelete_user
		ondelete_user: function () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('admin.user.delete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect: tableselect
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.usergets(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！请确认用户与角色或权限的关系！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！请确认用户与角色或权限的关系！');
			})
		},
		
		trash_user: function (userid) {
			var _this = this;
			
			if (userid == undefined || userid.length == 0) return false;
			var url = "{{ route('admin.user.trash') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				userid: userid
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', 'User 禁用/启用 successfully!');
					_this.usergets(_this.page_current, _this.page_last);
				} else {
					_this.error(false, '失败', '禁用/启用失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '禁用/启用失败！');
			})			
		},
		
		// 显示新建用户
		oncreate_user: function () {
			// 默认密码为12345678
			this.user_add_password = '12345678';
			this.modal_user_add = true;
		},
		
		// 新建用户
		oncreate_user_ok: function () {
			var _this = this;
			var name = _this.user_add_name;
			// var ldapname = _this.user_add_ldapname;
			var email = _this.user_add_email;
			var displayname = _this.user_add_displayname;
			var department = _this.user_add_department;
			var password = _this.user_add_password;
			
			if (name == '' || name == null || name == undefined
				// || ldapname == '' || ldapname == null || ldapname == undefined
				|| email == '' || email == null || email == undefined
				|| displayname == '' || displayname == null || displayname == undefined
				|| department == '' || department == null || department == undefined
				|| password == '' || password == null || password == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			// var re = new RegExp(“a”);  //RegExp对象。参数就是我们想要制定的规则。有一种情况必须用这种方式，下面会提到。
			// var re = /a/;   // 简写方法 推荐使用 性能更好  不能为空 不然以为是注释 ，
			var regexp = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/;
			if (! regexp.test(email)) {
				_this.warning(false, 'Warning', 'Email is incorrect!');
				return false;
			}

			var url = "{{ route('admin.user.create') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				name: name,
				// ldapname: ldapname,
				email: email,
				displayname: displayname,
				department: department,
				password: password
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, '成功', '用户创建成功！');
					_this.user_add_name = '';
					// _this.user_add_ldapname = '';
					_this.user_add_email = '';
					_this.user_add_displayname = '';
					_this.user_add_department = '';
					_this.user_add_password = '';
					_this.usergets(_this.page_current, _this.page_last);
				} else {
					_this.error(false, '失败', '用户创建失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '用户创建失败！');
			})
		},		
		
		// 导出用户
		onexport_user: function(){
			var url = "{{ route('admin.user.excelexport') }}";
			window.setTimeout(function(){
				window.location.href = url;
			}, 1000);
			return false;
		},
		
		// ClearTTL
		user_clsttl: function (row) {
			var _this = this;
			var id = row.id;

			var url = "{{ route('admin.user.clsttl') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
 				if (response.data) {
					_this.success(false, '成功', '清除用户登录TTL成功！');
				} else {
					_this.error(false, '失败', '清除用户登录TTL失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '清除用户登录TTL失败！');
			})

		},
		
		// 远程查询角色（同步）
		remoteMethod_sync_role (query) {
			var _this = this;
			if (query !== '') {
				_this.give_role_loading = true;
				var queryfilter_name = query;
				var url = "{{ route('admin.role.rolelist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						var json = response.data;
						_this.give_role_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				setTimeout(() => {
					_this.give_role_loading = false;
				}, 200);
			} else {
				_this.give_role_options = [];
			}
		},
		
		// 赋予角色到指定用户
		giveroletouser: function () {
			var _this = this;

			var userid = _this.tableselect;
			if (userid[0] == undefined) return false;

			var roleid = _this.give_role_select;
			if (roleid == undefined || roleid == '' ||
				userid == undefined || userid == '') {
				_this.warning(false, 'Warning', '内容不能为空！');
				return false;
			}
			
			var url = "{{ route('admin.user.syncroletouser') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				userid: userid,
				roleid: roleid
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, 'Success', 'Role(s) sync successfully!');
				} else {
					_this.warning(false, 'Warning', 'Role(s) failed to sync!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Role(s) failed to sync!');
			})
		},
		
		
		
		


	},
	mounted: function(){
		var _this = this;
		_this.current_nav = '权限管理';
		_this.current_subnav = '用户';
		// 显示所有user
		_this.usergets(1, 1); // page: 1, last_page: 1
	}
});
</script>
@endsection