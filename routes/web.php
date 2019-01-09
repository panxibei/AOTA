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

Route::get('/', function () {
    return view('index');
});


// 中日程分析页面
Route::group(['prefix'=>'bpjg', 'namespace'=>'Bpjg', 'middleware'=>[]], function() {
	Route::get('zrcfx', 'zrcfxController@zrcfxIndex')->name('bpjg.zrcfx.index');
	Route::get('zrcgets', 'zrcfxController@zrcGets')->name('bpjg.zrcfx.zrcgets');
	Route::post('zrcupdate', 'zrcfxController@zrcUpdate')->name('bpjg.zrcfx.zrcupdate');
	Route::post('zrccreate', 'zrcfxController@zrcCreate')->name('bpjg.zrcfx.zrccreate');
	Route::post('zrcdelete', 'zrcfxController@zrcDelete')->name('bpjg.zrcfx.zrcdelete');
	Route::get('zrcexport', 'zrcfxController@zrcExport')->name('bpjg.zrcfx.zrcexport');
	// Route::post('zrcimport', 'zrcfxController@zrcImport')->name('bpjg.zrcfx.zrcimport');
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
	// Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
});

// 品质日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('qcreport', 'qcreportController@qcreport')->name('smt.qcreport.qcreport');
	Route::get('qcreportgets', 'qcreportController@qcreportGets')->name('smt.qcreport.qcreportgets');
	Route::get('bulianggets', 'qcreportController@buliangGets')->name('smt.qcreport.bulianggets');
	Route::get('getsaomiao', 'qcreportController@getSaomiao')->name('smt.qcreport.getsaomiao');
	Route::post('qcreportcreate', 'qcreportController@qcreportCreate')->name('smt.qcreport.qcreportcreate');
	Route::post('qcreportupdate', 'qcreportController@qcreportUpdate')->name('smt.qcreport.qcreportupdate');
	Route::post('qcreportdelete', 'qcreportController@qcreportDelete')->name('smt.qcreport.qcreportdelete');
	Route::get('qcreportexport', 'qcreportController@qcreportExport')->name('smt.qcreport.qcreportexport');
	Route::post('qcreportimport', 'qcreportController@qcreportImport')->name('smt.qcreport.qcreportimport');
	Route::get('chart1', 'qcreportController@chart1')->name('smt.qcreport.chart1');
	Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
	Route::get('getdianmei', 'qcreportController@getDianmei')->name('smt.qcreport.getdianmei');
});


// 生产日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('pdreport', 'pdreportController@pdreport')->name('smt.pdreport.pdreport');
	Route::get('dailyreportgets', 'pdreportController@dailyreportGets')->name('smt.pdreport.dailyreportgets');
	Route::get('getjizhongming', 'pdreportController@getJizhongming')->name('smt.pdreport.getjizhongming');
	Route::post('dailyreportcreate', 'pdreportController@dailyreportCreate')->name('smt.pdreport.dailyreportcreate');
	Route::post('dailyreportdelete', 'pdreportController@dailyreportDelete')->name('smt.pdreport.dailyreportdelete');
	Route::post('dandangzhechange', 'pdreportController@dandangzheChange')->name('smt.pdreport.dandangzhechange');
	Route::post('querenzhechange', 'pdreportController@querenzheChange')->name('smt.pdreport.querenzhechange');
});


// MPoint页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('mpoint', 'pdreportController@mpoint')->name('smt.pdreport.mpoint');
	Route::get('mpointgets', 'pdreportController@mpointGets')->name('smt.pdreport.mpointgets');
	Route::post('mpointcreate', 'pdreportController@mpointCreate')->name('smt.pdreport.mpointcreate');
	Route::post('mpointupdate', 'pdreportController@mpointUpdate')->name('smt.pdreport.mpointupdate');
	Route::post('mpointdelete', 'pdreportController@mpointDelete')->name('smt.pdreport.mpointdelete');
	Route::post('mpointimport', 'pdreportController@mpointImport')->name('smt.pdreport.mpointimport');
});


// AOTA门户页面
Route::group(['prefix'=>'', 'namespace'=>'Main', 'middleware'=>[]], function() {
	Route::get('portal', 'mainController@mainPortal');
	Route::get('config', 'mainController@mainConfig');
	Route::get('configgets', 'mainController@configGets')->name('config.configgets');
	Route::post('configcreate', 'mainController@configCreate')->name('config.create');
	Route::post('configupdate', 'mainController@configUpdate')->name('config.update');
});


// 测试用
Route::get('test', 'testController@test');
Route::get('chart', 'testController@chart')->name('test.chart');