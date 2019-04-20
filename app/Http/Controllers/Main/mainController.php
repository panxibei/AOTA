<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use Cookie;
use DB;
use App\Models\Main\Smt_config;
use App\Models\Main\Release;

class mainController extends Controller
{
	// logout
	public function logout()
	{
		// 删除cookie
		Cookie::queue(Cookie::forget('token'));

		// 重置login_ttl为0
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);

		try	{
			User::where('id', $user['id'])
			->update([
				'login_ttl'	=> 0
			]);
		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			// $result = 0;
		}

		// Pass true to force the token to be blacklisted "forever"
		// auth()->logout(true);
		auth()->logout();

		// 返回登录页面
		return redirect()->route('login');
	}
	
	
    //
	public function mainPortal () {
		
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
        return view('main.portal', $share);
		
	}


	public function mainRelease () {

		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.config', $config);
		
		$share = compact('config', 'user');
        return view('main.release', $share);
		
	}


	public function mainReleasegets (Request $request) {

		if (! $request->ajax()) return null;

		$offset = $request->input('offset');

		$releasegets = Release::select('title', 'content')
			->offset($offset)
			->limit(10)
			->orderBy('id', 'desc')
			->get()
			->toArray();
		
		return $releasegets;
	}


	/**
	 * mainReleaseCreate
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function mainReleaseCreate(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$title = $request->input('title');
		$content = $request->input('content');

		// 写入数据库
		try	{
			// $result = DB::table('dailyreports')->insert([
			$result = Release::create([
				'title'		=> $title,
				'content'	=> $content,
			]);
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}	
	

}
