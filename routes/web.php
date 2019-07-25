<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    // return view('index');
// });


// 生产管理课 配置页面
Route::group(['prefix'=>'scgl', 'namespace'=>'Scgl', 'middleware'=>['jwtauth','permission:permission_scgl_config|permission_super_admin']], function() {
	Route::get('config', 'configController@scglConfig')->name('scgl.config');
	Route::post('configcreate', 'configController@configCreate')->name('scgl.configcreate');
	Route::post('configupdate', 'configController@configUpdate')->name('scgl.configupdate');
	Route::get('configgets', 'configController@configGets')->name('scgl.configgets');
});

// 生产管理课 耗材分析页面
Route::group(['prefix'=>'scgl', 'namespace'=>'Scgl', 'middleware'=>['jwtauth','permission:permission_scgl_hcfx|permission_super_admin']], function() {
	Route::get('hcfx', 'hcfxController@hcfxIndex')->name('scgl.hcfx.index');
	Route::get('guigegets', 'hcfxController@guigeGets')->name('scgl.hcfx.guigegets');
	Route::get('relationgets', 'hcfxController@relationGets')->name('scgl.hcfx.relationgets');
	Route::post('guigeCreate', 'hcfxController@guigeCreate')->name('scgl.hcfx.guigecreate');
	Route::post('relationupdate', 'hcfxController@relationUpdate')->name('scgl.hcfx.relationupdate');
	Route::post('relationcreate', 'hcfxController@relationCreate')->name('scgl.hcfx.relationcreate');
	Route::post('relationdelete', 'hcfxController@relationDelete')->name('scgl.hcfx.relationdelete');
	Route::get('relationdownload', 'hcfxController@relationDownload')->name('scgl.hcfx.relationdownload');
	Route::post('relationimport', 'hcfxController@relationImport')->name('scgl.hcfx.relationimport');
	Route::get('relationexport', 'hcfxController@relationExport')->name('scgl.hcfx.relationexport');
	Route::get('zrcdownload', 'hcfxController@zrcDownload')->name('scgl.hcfx.zrcdownload');
	Route::post('zrcfximport', 'hcfxController@zrcfxImport')->name('scgl.hcfx.zrcfximport');
	Route::get('resultgets1', 'hcfxController@resultGets1')->name('scgl.hcfx.resultgets1');
	Route::get('resultgets2', 'hcfxController@resultGets2')->name('scgl.hcfx.resultgets2');
	Route::get('zrcfxfunction', 'hcfxController@zrcfxFunction')->name('scgl.hcfx.zrcfxfunction');
	Route::get('resultexport', 'hcfxController@resultExport')->name('scgl.hcfx.resultexport');
});

// 部品加工课 中日程分析页面
Route::group(['prefix'=>'bpjg', 'namespace'=>'Bpjg', 'middleware'=>['jwtauth','permission:permission_bpjg_zrcfx|permission_super_admin']], function() {
	Route::get('zrcfx', 'zrcfxController@zrcfxIndex')->name('bpjg.zrcfx.index');
	Route::post('zrcfximport', 'zrcfxController@zrcfxImport')->name('bpjg.zrcfx.zrcfximport');
	Route::get('zrcdownload', 'zrcfxController@zrcDownload')->name('bpjg.zrcfx.zrcdownload');
	Route::get('relationgets', 'zrcfxController@relationGets')->name('bpjg.zrcfx.relationgets');
	Route::post('relationupdate', 'zrcfxController@relationUpdate')->name('bpjg.zrcfx.relationupdate');
	Route::post('relationcreate', 'zrcfxController@relationCreate')->name('bpjg.zrcfx.relationcreate');
	Route::post('relationdelete', 'zrcfxController@relationDelete')->name('bpjg.zrcfx.relationdelete');
	Route::get('relationexport', 'zrcfxController@relationExport')->name('bpjg.zrcfx.relationexport');
	Route::post('relationimport', 'zrcfxController@relationImport')->name('bpjg.zrcfx.relationimport');
	Route::get('relationdownload', 'zrcfxController@relationDownload')->name('bpjg.zrcfx.relationdownload');
	Route::get('zrcfxfunction', 'zrcfxController@zrcfxFunction')->name('bpjg.zrcfx.zrcfxfunction');
	Route::get('resultgets', 'zrcfxController@resultGets')->name('bpjg.zrcfx.resultgets');
	Route::get('resultexport', 'zrcfxController@resultExport')->name('bpjg.zrcfx.resultexport');
});

// SMT 配置页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_config|permission_super_admin']], function() {
	Route::get('config', 'configController@smtConfig')->name('smt.config');
	// Route::post('configcreate', 'configController@configCreate')->name('smt.configcreate');
	Route::post('configUpdatePdreport', 'configController@configUpdatePdreport')->name('smt.configupdatepdreport');
	Route::post('configUpdateQcreport', 'configController@configUpdateQcreport')->name('smt.configupdateqcreport');
	Route::get('configGetsPdreport', 'configController@configGetsPdreport')->name('smt.configgetspdreport');
	Route::get('configGetsQcreport', 'configController@configGetsQcreport')->name('smt.configgetsqcreport');
});

// SMT 品质日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_qcreport|permission_super_admin']], function() {
	Route::get('qcreportIndex', 'qcreportController@qcreportIndex')->name('smt.qcreport.index');
	Route::get('qcreportgets', 'qcreportController@qcreportGets')->name('smt.qcreport.qcreportgets');
	Route::get('qcreportgetschart1', 'qcreportController@qcreportGetsChart1')->name('smt.qcreport.qcreportgetschart1');
	Route::get('qcreportgetschart2', 'qcreportController@qcreportGetsChart2')->name('smt.qcreport.qcreportgetschart2');
	Route::get('qcreportgetschart3', 'qcreportController@qcreportGetsChart3')->name('smt.qcreport.qcreportgetschart3');
	Route::get('bulianggets', 'qcreportController@buliangGets')->name('smt.qcreport.bulianggets');
	Route::get('getsaomiao', 'qcreportController@getSaomiao')->name('smt.qcreport.getsaomiao');
	Route::post('qcreportcreate', 'qcreportController@qcreportCreate')->name('smt.qcreport.qcreportcreate');
	Route::post('qcreportappend', 'qcreportController@qcreportAppend')->name('smt.qcreport.qcreportappend');
	Route::post('qcreportupdate', 'qcreportController@qcreportUpdate')->name('smt.qcreport.qcreportupdate');
	Route::post('qcreportupdatesub', 'qcreportController@qcreportUpdateSub')->name('smt.qcreport.qcreportupdatesub');
	Route::post('qcreportdelete', 'qcreportController@qcreportDelete')->name('smt.qcreport.qcreportdelete');
	Route::post('qcreportremovesub', 'qcreportController@qcreportRemoveSub')->name('smt.qcreport.qcreportremovesub');
	Route::get('qcreportexport', 'qcreportController@qcreportExport')->name('smt.qcreport.qcreportexport');
	Route::post('qcreportimport', 'qcreportController@qcreportImport')->name('smt.qcreport.qcreportimport');
	Route::get('chart1', 'qcreportController@chart1')->name('smt.qcreport.chart1');
	Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
	// Route::get('chart3', 'qcreportController@chart3')->name('smt.qcreport.chart3');
	Route::get('getdianmei', 'qcreportController@getDianmei')->name('smt.qcreport.getdianmei');
	Route::get('tongjigets', 'qcreportController@tongjiGets')->name('smt.qcreport.tongjigets');
});


// SMT 生产日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_pdreport|permission_super_admin']], function() {
	Route::get('pdreportIndex', 'pdreportController@pdreportIndex')->name('smt.pdreport.index');
	Route::get('dailyreportgets', 'pdreportController@dailyreportGets')->name('smt.pdreport.dailyreportgets');
	Route::get('getjizhongming', 'pdreportController@getJizhongming')->name('smt.pdreport.getjizhongming');
	Route::get('pdreportexport', 'pdreportController@pdreportExport')->name('smt.pdreport.pdreportexport');
	Route::post('dailyreportcreate', 'pdreportController@dailyreportCreate')->name('smt.pdreport.dailyreportcreate');
	Route::post('dailyreportdelete', 'pdreportController@dailyreportDelete')->name('smt.pdreport.dailyreportdelete');
	Route::post('dandangzhechange', 'pdreportController@dandangzheChange')->name('smt.pdreport.dandangzhechange');
	Route::post('querenzhechange', 'pdreportController@querenzheChange')->name('smt.pdreport.querenzhechange');
	Route::post('pdplanimport', 'pdreportController@pdplanImport')->name('smt.pdreport.pdplanimport');
	Route::get('pdplandownload', 'pdreportController@pdplanDownload')->name('smt.pdreport.pdplandownload');
	Route::get('pdplanrefresh', 'pdreportController@pdplanRefresh')->name('smt.pdreport.pdplandrefresh');
	Route::get('pdplantruncate', 'pdreportController@pdplanTruncate')->name('smt.pdreport.pdplandtruncate');
	Route::get('pdplangets', 'pdreportController@pdplanGets')->name('smt.pdreport.pdplangets');
	Route::get('pdplanresultgets', 'pdreportController@pdplanresultGets')->name('smt.pdreport.pdplanresultgets');

});


// MPoint页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_mpoint|permission_super_admin']], function() {
	Route::get('mpoint', 'pdreportController@mpoint')->name('smt.pdreport.mpoint');
	Route::get('mpointgets', 'pdreportController@mpointGets')->name('smt.pdreport.mpointgets');
	Route::post('mpointcreate', 'pdreportController@mpointCreate')->name('smt.pdreport.mpointcreate');
	Route::post('mpointupdate', 'pdreportController@mpointUpdate')->name('smt.pdreport.mpointupdate');
	Route::post('mpointdelete', 'pdreportController@mpointDelete')->name('smt.pdreport.mpointdelete');
	Route::post('mpointimport', 'pdreportController@mpointImport')->name('smt.pdreport.mpointimport');
	Route::get('mpointdownload', 'pdreportController@mpointDownload')->name('smt.pdreport.mpointdownload');
});


// AOTA门户页面
Route::group(['prefix'=>'', 'namespace'=>'Main', 'middleware'=>['jwtauth']], function() {
	Route::get('/', 'mainController@mainPortal')->name('portal');
	Route::get('portal', 'mainController@mainPortal')->name('portal');

	// logout
	Route::get('logout', 'mainController@logout')->name('main.logout');
});


// release页面
Route::group(['prefix'=>'release', 'namespace'=>'Main', 'middleware'=>['jwtauth']], function() {
	Route::get('/', 'mainController@mainRelease')->name('release');
	Route::get('releasegets', 'mainController@mainReleasegets')->name('release.releasegets');
	Route::post('releaseCreate', 'mainController@mainReleaseCreate')->name('release.releasecreate');
});


// home模块
Route::group(['prefix' => 'login', 'namespace' =>'Home'], function() {
	Route::get('/', 'LoginController@index')->name('login');
	Route::post('checklogin', 'LoginController@checklogin')->name('login.checklogin');
});


// AdminController路由
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_super_admin']], function() {
	// 显示config页面
	Route::get('configIndex', 'AdminController@configIndex')->name('admin.config.index');

	// 获取config数据信息
	Route::get('configList', 'AdminController@configList')->name('admin.config.list');

	// 获取group数据信息
	Route::get('groupList', 'AdminController@groupList')->name('admin.group.list');
	

	// 修改config数据
	Route::post('configChange', 'AdminController@configChange')->name('admin.config.change');

	// logout
	Route::get('logout', 'AdminController@logout')->name('admin.logout');

});


// UserController路由
Route::group(['prefix'=>'user', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_user|permission_super_admin']], function() {

	// 显示user页面
	Route::get('userIndex', 'UserController@userIndex')->name('admin.user.index');

	// 获取user数据信息
	Route::get('userList', 'UserController@userList')->name('admin.user.list');

	// 创建user
	Route::post('userCreate', 'UserController@userCreate')->name('admin.user.create');

	// 禁用user（软删除）
	Route::post('userTrash', 'UserController@userTrash')->name('admin.user.trash');

	// 删除user
	Route::post('userDelete', 'UserController@userDelete')->name('admin.user.delete');

	// 编辑user
	Route::post('userUpdate', 'UserController@userUpdate')->name('admin.user.update');

	// 测试excelExport
	Route::get('excelExport', 'UserController@excelExport')->name('admin.user.excelexport');

	// 清除user的ttl
	Route::post('userclsttl', 'UserController@userClsttl')->name('admin.user.clsttl');


});


// RoleController路由
Route::group(['prefix'=>'role', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_role|permission_super_admin']], function() {

	// 显示role页面
	Route::get('roleIndex', 'RoleController@roleIndex')->name('admin.role.index');

	// 列出所有用户
	Route::get('userList', 'RoleController@userList')->name('admin.role.userlist');

	// 列出所有角色
	Route::get('roleList', 'RoleController@roleList')->name('admin.role.rolelist');

	// 列出所有权限
	Route::get('permissionList', 'RoleController@permissionList')->name('admin.role.permissionlist');

	// 列出所有待删除的角色
	Route::get('roleListDelete', 'RoleController@roleListDelete')->name('admin.role.rolelistdelete');

	// 创建role
	Route::post('roleCreate', 'RoleController@roleCreate')->name('admin.role.create');

	// 编辑role
	Route::post('roleUpdate', 'RoleController@roleUpdate')->name('admin.role.update');
	
	// 删除角色
	Route::post('roleDelete', 'RoleController@roleDelete')->name('admin.role.roledelete');

	// 列出当前用户拥有的角色
	Route::get('userHasRole', 'RoleController@userHasRole')->name('admin.role.userhasrole');

	// 更新当前用户的角色
	Route::post('userUpdateRole', 'RoleController@userUpdateRole')->name('admin.role.userupdaterole');

	// 列出当前用户可追加的角色
	// Route::get('userGiveRole', 'RoleController@userGiveRole')->name('admin.role.usergiverole');

	// 赋予role
	Route::post('roleGive', 'RoleController@roleGive')->name('admin.role.give');
	// 移除role
	// Route::post('roleRemove', 'RoleController@roleRemove')->name('admin.role.remove');

	// 根据角色查看哪些用户
	Route::get('roleToViewUser', 'RoleController@roleToViewUser')->name('admin.role.roletoviewuser');

	// 权限同步到指定角色
	Route::post('syncPermissionToRole', 'RoleController@syncPermissionToRole')->name('admin.role.syncpermissiontorole');

	// 查询角色列表
	Route::get('roleGets', 'RoleController@roleGets')->name('admin.role.rolegets');
	
	// 测试excelExport
	Route::get('excelExport', 'RoleController@excelExport')->name('admin.role.excelexport');
	
});


// PermissionController路由
Route::group(['prefix'=>'permission', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_permission|permission_super_admin']], function() {

	// 显示permission页面
	Route::get('permissionIndex', 'PermissionController@permissionIndex')->name('admin.permission.index');

	// 角色列表
	Route::get('permissionGets', 'PermissionController@permissionGets')->name('admin.permission.permissiongets');

	// 创建permission
	Route::post('permissionCreate', 'PermissionController@permissionCreate')->name('admin.permission.create');

	// 编辑permission
	Route::post('permissionUpdate', 'PermissionController@permissionUpdate')->name('admin.permission.update');
	
	// 删除permission
	Route::post('permissionDelete', 'PermissionController@permissionDelete')->name('admin.permission.permissiondelete');

	// 赋予permission
	Route::post('permissionGive', 'PermissionController@permissionGive')->name('admin.permission.give');
	// 移除permission
	Route::post('permissionRemove', 'PermissionController@permissionRemove')->name('admin.permission.remove');

	// 列出当前角色拥有的权限
	Route::get('roleHasPermission', 'PermissionController@roleHasPermission')->name('admin.permission.rolehaspermission');

	// 更新当前角色的权限
	Route::post('roleUpdatePermission', 'PermissionController@roleUpdatePermission')->name('admin.permission.roleupdatepermission');
	
	// 列出所有待删除的权限
	Route::get('permissionListDelete', 'PermissionController@permissionListDelete')->name('admin.permission.permissionlistdelete');

	// 列出所有权限
	Route::get('permissionList', 'PermissionController@permissionList')->name('admin.permission.permissionlist');

	// 根据权限查看哪些角色
	Route::get('permissionToViewRole', 'PermissionController@permissionToViewRole')->name('admin.permission.permissiontoviewrole');

	// 角色同步到指定权限
	Route::post('testUsersPermission', 'PermissionController@testUsersPermission')->name('admin.permission.testuserspermission');
	
	// 测试excelExport
	Route::get('excelExport', 'PermissionController@excelExport')->name('admin.permission.excelexport');

	// 列出所有角色
	Route::get('roleList', 'PermissionController@roleList')->name('admin.permission.rolelist');

	// 列出所有用户
	Route::get('userList', 'PermissionController@userList')->name('admin.permission.userlist');
	
});


// 测试用
Route::group(['prefix'=>'test', 'namespace'=>'Test', 'middleware'=>['jwtauth','permission:permission_super_admin']], function() {
	Route::get('test', 'testController@test');
	Route::get('phpinfo', 'testController@phpinfo');
	Route::get('ldap', 'testController@ldap');
	Route::get('scroll', 'testController@scroll');
	// Route::get('config', 'testController@mainConfig');
});
