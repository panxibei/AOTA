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
	Route::get('zrcfx', 'zrcfjController@zrcfxIndex')->name('bpjg.zrcfx.index');
	// Route::get('qcreportgets', 'qcreportController@qcreportGets')->name('smt.qcreport.qcreportgets');
	// Route::get('bulianggets', 'qcreportController@buliangGets')->name('smt.qcreport.bulianggets');
	// Route::get('getsaomiao', 'qcreportController@getSaomiao')->name('smt.qcreport.getsaomiao');
	// Route::post('qcreportcreate', 'qcreportController@qcreportCreate')->name('smt.qcreport.qcreportcreate');
	// Route::post('qcreportdelete', 'qcreportController@qcreportDelete')->name('smt.qcreport.qcreportdelete');
	// Route::get('qcreportexport', 'qcreportController@qcreportExport')->name('smt.qcreport.qcreportexport');
	// Route::post('qcreportimport', 'qcreportController@qcreportImport')->name('smt.qcreport.qcreportimport');
	// Route::get('chart1', 'qcreportController@chart1')->name('smt.qcreport.chart1');
	// Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
});

// 品质日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('qcreport', 'qcreportController@qcreport');
	Route::get('qcreportgets', 'qcreportController@qcreportGets')->name('smt.qcreport.qcreportgets');
	Route::get('bulianggets', 'qcreportController@buliangGets')->name('smt.qcreport.bulianggets');
	Route::get('getsaomiao', 'qcreportController@getSaomiao')->name('smt.qcreport.getsaomiao');
	Route::post('qcreportcreate', 'qcreportController@qcreportCreate')->name('smt.qcreport.qcreportcreate');
	Route::post('qcreportdelete', 'qcreportController@qcreportDelete')->name('smt.qcreport.qcreportdelete');
	Route::get('qcreportexport', 'qcreportController@qcreportExport')->name('smt.qcreport.qcreportexport');
	Route::post('qcreportimport', 'qcreportController@qcreportImport')->name('smt.qcreport.qcreportimport');
	Route::get('chart1', 'qcreportController@chart1')->name('smt.qcreport.chart1');
	Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
});


// 生产日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('pdreport', 'pdreportController@pdreport');
	Route::get('dailyreportgets', 'pdreportController@dailyreportGets')->name('smt.pdreport.dailyreportgets');
	Route::get('getjizhongming', 'pdreportController@getJizhongming')->name('smt.pdreport.getjizhongming');
	Route::post('dailyreportcreate', 'pdreportController@dailyreportCreate')->name('smt.pdreport.dailyreportcreate');
	Route::post('dailyreportdelete', 'pdreportController@dailyreportDelete')->name('smt.pdreport.dailyreportdelete');
	Route::post('dandangzhechange', 'pdreportController@dandangzheChange')->name('smt.pdreport.dandangzhechange');
	Route::post('querenzhechange', 'pdreportController@querenzheChange')->name('smt.pdreport.querenzhechange');
});


// MPoint页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>[]], function() {
	Route::get('mpoint', 'pdreportController@mpoint');
	Route::get('mpointgets', 'pdreportController@mpointGets')->name('smt.pdreport.mpointgets');
	Route::post('mpointcreate', 'pdreportController@mpointCreate')->name('smt.pdreport.mpointcreate');
	Route::post('mpointupdate', 'pdreportController@mpointUpdate')->name('smt.pdreport.mpointupdate');
	Route::post('mpointdelete', 'pdreportController@mpointDelete')->name('smt.pdreport.mpointdelete');
});


// 测试用
Route::get('test', 'testController@test');
Route::get('chart', 'testController@chart')->name('test.chart');