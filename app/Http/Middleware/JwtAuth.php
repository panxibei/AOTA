<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin\Config;
use Cookie;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

		// 请求前处理内容
		// return $next($request);


		// 验证sitekey和appkey
		$config = Config::where('cfg_name', 'SITE_KEY')->pluck('cfg_value', 'cfg_name')->toArray();
		$site_key = $config['SITE_KEY'];
		$app_key = substr(config('app.key'), 19, 12);
		if ($app_key != $site_key) die();

		
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

		// 判断数组为空，以此来判断是否有有效用户登录
		// 通过判断IP决定是否更换了登录地点
		if (! sizeof($user)) {
			// 无有效用户登录，则认证失败，退回登录界面
			// dd('credentials are invalid');

			// Cookie::queue(Cookie::forget('token'));
			// Cookie::queue(Cookie::forget('singletoken'));
			if($request->ajax()){
				// 如果是ajax请求，则返回空数组，由axios处理返回登录页面
				// return response()->json();
				// return response()->json(['name' => 'Abigail']);
				return response()->json(['jwt' => 'logout']);
			} else {
				// 如果是正常请求，则直接返回登录页面
				return redirect()->route('login');
			}
		
		} else {
			$token_local = Cookie::get('singletoken');
			// $singletoken = md5($user['login_ip'] . $user['name'] . $user[login_time]);
			$token_remote = $user['remember_token'];

			// dump($token_local);
			// dump('<br><br>');
			// dd($token_remote);

			if (empty($token_remote) || $token_local != $token_remote) {
				Cookie::queue(Cookie::forget('token'));
				Cookie::queue(Cookie::forget('singletoken'));
				return $request->ajax() ? response()->json(['jwt' => 'logout']) : redirect()->route('login');
			}

		}


		// 保存请求内容
		$response = $next($request);


		// 请求后处理内容


		// 返回请求
		return $response;
    }
}
