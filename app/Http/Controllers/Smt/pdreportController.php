<?php

namespace App\Http\Controllers\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Smt\Smt_mpoint;
use App\Models\Smt\Smt_pdreport;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Smt\mpointExport;
use App\Imports\Smt\mpointImport;
use Illuminate\Support\Facades\Storage;

class pdreportController extends Controller
{
    //
	public function pdreport () {
		
		// $test = DB::table('dailyreports')->where('id', 2)->get();
		// dd($test);
		
		// $test0 = json_encode([1,2,3]);
		// $share = compact('test', 'test0');
		
		// return view('pdreport', $share);
		return view('smt.pdreport');
		
	}

    //
	public function mpoint () {
		
		// $test = DB::table('mpoints')->get();
		// dd($test);
		
		// $test0 = json_encode([1,2,3]);
		// $share = compact('test', 'test0');
		
		// return view('mpoint', $share);
		return view('smt.mpoint');
		
	}
	
    /**
     * mpointGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mpointGets(Request $request)
    {
		if (! $request->ajax()) { return null; }

		$perPage = $request->input('perPage');
		$page = $request->input('page');
		if (null == $page) $page = 1;

		$dailydate_filter = $request->input('dailydate_filter');
		$jizhongming_filter = $request->input('jizhongming_filter');
		
		// $mpoint = DB::table('mpoints')
		$mpoint = Smt_mpoint::when($dailydate_filter, function ($query) use ($dailydate_filter) {
				return $query->whereBetween('created_at', $dailydate_filter);
			})
			->when($jizhongming_filter, function ($query) use ($jizhongming_filter) {
				return $query->where('jizhongming', 'like', '%'.$jizhongming_filter.'%');
			})
			->orderBy('created_at', 'desc')
			->paginate($perPage, ['*'], 'page', $page);
		
		// $circulation = Circulation::select('id', 'guid', 'name', 'template_id', 'mailinglist_id', 'slot2user_id', 'slot_id', 'user_id', 'current_station as currentstation', 'creator', 'todo_time', 'progress', 'description', 'is_archived', 'created_at as sendingdate')
			// ->get()->toArray();

		return $mpoint;
    }
	
    /**
     * mpointCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mpointCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$mpoint = $request->only(
			'jizhongming',
			'pinming',
			'gongxu',
			'diantai',
			'pinban'
			// 'created_at'
		);
		// dd($mpoint);

		// 写入数据库
		try	{
			// $result = DB::table('mpoints')->insert([
			$result = Smt_mpoint::create([
				'jizhongming'	=> $mpoint['jizhongming'],
				'pinming'		=> $mpoint['pinming'],
				'gongxu'			=> $mpoint['gongxu'],
				'diantai'		=> $mpoint['diantai'],
				'pinban'		=> $mpoint['pinban']
				// 'created_at'	=> date("Y-m-d H:i:s",time())
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
    }

    /**
     * mpointUpdate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mpointUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$mpoint = $request->only(
			'jizhongming',
			'pinming',
			'gongxu',
			'diantai',
			'pinban',
			'id'
		);
		// dd($mpoint);

		// 写入数据库
		try	{
			// $result = DB::table('mpoints')
			$result = Smt_mpoint::where('id', $mpoint['id'])
				->update([
					'jizhongming'	=> $mpoint['jizhongming'],
					'pinming'		=> $mpoint['pinming'],
					'gongxu'			=> $mpoint['gongxu'],
					'diantai'		=> $mpoint['diantai'],
					'pinban'		=> $mpoint['pinban'],
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
    }
	
	
    /**
     * mpointDelete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mpointDelete(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$id = $request->only('tableselect');

		// $result = DB::table('mpoints')->whereIn('id', $id)->delete();
		$result = Smt_mpoint::whereIn('id', $id)->delete();
		return $result;

	}
	
	
    /**
     * getJizhongming
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getJizhongming(Request $request)
    {
		if (! $request->ajax()) { return null; }

		$jizhongming = $request->only('jizhongming');

		// $result = DB::table('mpoints')
		$result = Smt_mpoint::where('jizhongming', $jizhongming)
			->get();
		// dd($result);
		return $result;

	}
	
	
    /**
     * dailyreportCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dailyreportCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$dailyreport = $request->only(
			'xianti',
			'banci',
			'jizhongming',
			'spno',
			'pinming',
			'lotshu',
			'meimiao',
			'meishu',
			'gongxu'
		);
		// dd($dailyreport['banci']);
		
		//读取点/枚
		// $t = DB::table('mpoints')->select('diantai', 'pinban')
		$t = Smt_mpoint::select('diantai', 'pinban')
			->where('jizhongming', $dailyreport['jizhongming'])
			->where('pinming', $dailyreport['pinming'])
			->where('gongxu', $dailyreport['gongxu'])
			->first();
		// dd($t);
		
		if ($t == null) return 0;
		
		$dianmei = $t->diantai * $t->pinban;
		$taishu = $dailyreport['meishu'] * $t->pinban;
		$chajiandianshu = $t->diantai * $dailyreport['meishu'];
		$jiadonglv = $dailyreport['meishu'] * $dailyreport['meimiao'] / 43200;

		// 写入数据库
		try	{
			// $result = DB::table('dailyreports')->insert([
			$result = Smt_pdreport::create([
				'xianti'		=> $dailyreport['xianti'],
				'banci'			=> $dailyreport['banci'],
				'jizhongming'	=> $dailyreport['jizhongming'],
				'spno'			=> $dailyreport['spno'],
				'pinming'		=> $dailyreport['pinming'],
				'lotshu'		=> $dailyreport['lotshu'],
				'meimiao'		=> $dailyreport['meimiao'],
				'meishu'		=> $dailyreport['meishu'],
				'gongxu'		=> $dailyreport['gongxu'],
				'dianmei'		=> $dianmei,
				'meimiao'		=> $dailyreport['meimiao'],
				'meishu'		=> $dailyreport['meishu'],
				'taishu'		=> $taishu,
				'lotcan'		=> 0,
				'chajiandianshu'=> $chajiandianshu,
				'jiadonglv'		=> $jiadonglv
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
    }
	
	
    /**
     * dailyreportGets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dailyreportGets(Request $request)
    {
		if (! $request->ajax()) { return null; }

		$perPage = $request->input('perPage');
		$page = $request->input('page');
		if (null == $page) $page = 1;

		$dailydate_filter = $request->input('dailydate_filter');
		$xianti_filter = $request->input('xianti_filter');
		$banci_filter = $request->input('banci_filter');
		
		// $mpoint = DB::table('mpoints')
		$dailyreport = Smt_pdreport::when($dailydate_filter, function ($query) use ($dailydate_filter) {
				return $query->whereBetween('created_at', $dailydate_filter);
			})
			->when($xianti_filter, function ($query) use ($xianti_filter) {
				return $query->where('xianti', 'like', '%'.$xianti_filter.'%');
			})
			->when($banci_filter, function ($query) use ($banci_filter) {
				return $query->where('banci', 'like', '%'.$banci_filter.'%');
			})
			->orderBy('created_at', 'asc')
			->paginate($perPage, ['*'], 'page', $page);
		
		// $circulation = Circulation::select('id', 'guid', 'name', 'template_id', 'mailinglist_id', 'slot2user_id', 'slot_id', 'user_id', 'current_station as currentstation', 'creator', 'todo_time', 'progress', 'description', 'is_archived', 'created_at as sendingdate')
			// ->get()->toArray();

		return $dailyreport;
    }
	
	
    /**
     * dailyreportDelete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dailyreportDelete(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$id = $request->only('tableselect');

		$result = Smt_pdreport::whereIn('id', $id)->delete();
		return $result;

	}
	
    /**
     * dandangzheChange
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dandangzheChange(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$dailyreport = $request->only('id', 'dandangzhe');
		// dd($dailyreport['id']);
		foreach ($dailyreport['id'] as $key => $value) {
			$data[$key]['id'] = $value;
			$data[$key]['dandangzhe'] = $dailyreport['dandangzhe'];
		}
		// dd($data);

		try	{
			// $result = Smt_pdreport::where('id', $dailyreport['id'])
				// ->update([
					// 'dandangzhe'	=> $dailyreport['dandangzhe']
				// ]);
				
			// 批量更新
			app(Smt_pdreport::class)->updateBatch($data);
			
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}

    /**
     * querenzheChange
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function querenzheChange(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) { return null; }

		$dailyreport = $request->only('id', 'querenzhe');
		// dd($dailyreport['id']);
		foreach ($dailyreport['id'] as $key => $value) {
			$data[$key]['id'] = $value;
			$data[$key]['querenzhe'] = $dailyreport['querenzhe'];
		}
		
		try	{
			// $result = Smt_pdreport::where('id', $dailyreport['id'])
				// ->update([
					// 'querenzhe'	=> $dailyreport['querenzhe']
				// ]);

			// 批量更新
			app(Smt_pdreport::class)->updateBatch($data);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}
	
    /**
     * mpointImport
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mpointImport(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 接收文件
		$fileCharater = $request->file('myfile');
		// dd($fileCharater);
 
		if ($fileCharater->isValid()) { //括号里面的是必须加的哦
			//如果括号里面的不加上的话，下面的方法也无法调用的

			//获取文件的扩展名 
			$ext = $fileCharater->extension();
			// dd($ext);
			if ($ext != 'xls' && $ext != 'xlsx') {
				return 0;
			}

			//获取文件的绝对路径
			// $path = $fileCharater->path();
			// dd($path);

			//定义文件名
			// $filename = date('Y-m-d-h-i-s').'.'.$ext;
			$filename = 'importmpoint.'.$ext;
			// dd($filename);

			//存储文件。使用 storeAs 方法，它接受路径、文件名和磁盘名作为其参数
			// $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
			$fileCharater->storeAs('excel', $filename);
			// dd($filename);
		} else {
			return 0;
		}
		
		// 导入excel文件内容
		try {
			$ret = Excel::import(new mpointImport, 'excel/'.$filename);
			// dd($ret);
			$result = 1;
		} catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		} finally {
			Storage::delete('excel/'.$filename);
		}
		
		return $result;
		
	}
	
}
