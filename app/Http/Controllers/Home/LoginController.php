<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use Cookie;
use Validator;
use Adldap\Laravel\Facades\Adldap;

class LoginController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		if (! sizeof($user)) {
			// 无有效用户登录，则认证失败，退回登录界面
		} else {
			// 如果是已经登录，则跳转至门户页面
			return redirect()->route('portal');
		}

		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();

		return view('home.login', $config);
	}

	public function checklogin(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// $name = $request->input('name');
		// $password = $request->input('password');
		// $captcha = $request->input('captcha');
		$rememberme = $request->input('rememberme');
		
		// 1.判断验证码
		// $rules = ['captcha' => 'required|captcha'];
		// $validator = Validator::make($request->all(), $rules);
		// if ($validator->fails()) {
		// 	return null;
		// } else {
		// }

		$name = $request->input('name');
		$password = $request->input('password');

		$nowtime = date("Y-m-d H:i:s",time());
		$ip = $request->getClientIp();

		// $singletoken = substr(md5($ip . $name . $nowtime), 0, 100);
		$singletoken = md5($ip . $name . $nowtime);


		// 判断单用户登录
		// $singleUser = User::select('login_time', 'login_ttl')->where('name', $name)->first();
		// $user_login_time = strtotime($singleUser['login_time']);
		// $user_login_ttl = $singleUser['login_ttl'] * 60;
		// $user_login_expire = $user_login_time + $user_login_ttl;
		// $user_now = time();
		
		// if ($user_now < $user_login_expire) {
			// return $user_login_time . '|' . $user_login_ttl . '|' .$user_now . 'singleuser';
			// return 'nosingleuser';
		// }


		// $minutes = 480;
		// $minutes = config('jwt.ttl', 60);
		$minutes = $rememberme ? config('jwt.ttl', 60*24*365) : config('jwt.jwt_cookies_ttl', 60*24);

		// 2.adldap判断AD认证
		$adldap = false;
		if (config('ldap.ldap_use_ldap') == 'ldap') {

			// 判断是否启用connection_01
			$provider01 = false;
			if (config('ldap.ldap_use_connection_01') == 'connection01') {
				$ad01 = new \Adldap\Adldap();

				$config = [
					// Mandatory Configuration Options
					'hosts'            => config('ldap.connections.connection_01.settings.hosts'),
					'base_dn'          => config('ldap.connections.connection_01.settings.base_dn'),
					'username'         => config('ldap.connections.connection_01.settings.username'),
					'password'         => config('ldap.connections.connection_01.settings.password'),

					// Optional Configuration Options
					'schema'           => config('ldap.connections.connection_01.settings.schema'),
					'account_prefix'   => config('ldap.connections.connection_01.settings.account_prefix'),
					'account_suffix'   => config('ldap.connections.connection_01.settings.account_suffix'),
					'port'             => config('ldap.connections.connection_01.settings.port'),
					'follow_referrals' => false,
					'use_ssl'          => false,
					'use_tls'          => false,
					'version'          => 3,
					'timeout'          => 5,
				];

				$connectionName01 = 'connection_01';
				$ad01->addProvider($config, $connectionName01);

				try {
					$provider01 = $ad01->connect($connectionName01);
				} catch (\Adldap\Auth\BindException $e) {
					$provider01 = false;
				}
			}

			// 判断是否启用connection_02
			$provider02 = false;
			if (config('ldap.ldap_use_connection_02') == 'connection02') {
				$ad02 = new \Adldap\Adldap();

				$config = [
					// Mandatory Configuration Options
					'hosts'            => config('ldap.connections.connection_02.settings.hosts'),
					'base_dn'          => config('ldap.connections.connection_02.settings.base_dn'),
					'username'         => config('ldap.connections.connection_02.settings.username'),
					'password'         => config('ldap.connections.connection_02.settings.password'),

					// Optional Configuration Options
					'schema'           => config('ldap.connections.connection_02.settings.schema'),
					'account_prefix'   => config('ldap.connections.connection_02.settings.account_prefix'),
					'account_suffix'   => config('ldap.connections.connection_02.settings.account_suffix'),
					'port'             => config('ldap.connections.connection_02.settings.port'),
					'follow_referrals' => false,
					'use_ssl'          => false,
					'use_tls'          => false,
					'version'          => 3,
					'timeout'          => 5,
				];

				$connectionName02 = 'connection_02';
				$ad02->addProvider($config, $connectionName02);

				try {
					$provider02 = $ad02->connect($connectionName02);
				} catch (\Adldap\Auth\BindException $e) {
					$provider02 = false;
				}
			}

			// default ldap test
			try {
				$adldap = Adldap::auth()->attempt($name, $password);
			}
			catch (\Adldap\Auth\BindException $e) {
				// echo 'Message: ' .$e->getMessage();
				$adldap = false;
			}

			// connection01 ldap test
			if ($adldap == false && $provider01) {

				try {
					$adldap = $provider01->auth()->attempt($name, $password);
				}
				catch (\Adldap\Auth\BindException $e) {
					$adldap = false;
				}
			}

			// connection02 ldap test
			if ($adldap == false && $provider02) {

				try {
					$adldap = $provider02->auth()->attempt($name, $password);
				}
				catch (\Adldap\Auth\BindException $e) {
					$adldap = false;
				}
			}

			// 3.如果adldap认证成功，则同步本地用户的密码
			//   否则认证失败再由jwt-auth本地判断
			if ($adldap) {

				// 获取用户email
				$user_tmp = Adldap::search()->users()->find($name);

				if (empty($user_tmp) && $provider01) {
					$user_tmp = $provider01->search()->users()->find($name);
				}

				if (empty($user_tmp) && $provider02) {
					$user_tmp = $provider02->search()->users()->find($name);
				}

				if (!empty($user_tmp)) {
					$email = $user_tmp['mail'][0];
					$displayname = $user_tmp['displayname'][0];
				} else {
					$email = $name . '@aota.local';
					$displayname = $name;
				}
				$ldapname = $name;

				// 同步本地用户密码
				try	{
					$result = User::where('name', $name)
						->increment('login_counts', 1, [
							'password'   => bcrypt($password),
							'ldapname'   => $ldapname,
							'email'      => $email,
							'displayname'=> $displayname,
							'login_time' => $nowtime,
							'login_ttl'	 => $minutes,
							'login_ip'   => $ip, //$_SERVER['REMOTE_ADDR'],
							'remember_token'=> $singletoken,
						]);

					// 4.如果没有这个用户，则自动新增用户
					if ($result == 0) {
						$result = User::create([
							'name'          => $name,
							'ldapname'   	=> $ldapname,
							'email'         => $email,
							'displayname'   => $displayname,
							'password'      => bcrypt($password),
							'login_time'    => $nowtime,
							'login_ttl'	 	=> $minutes,
							'login_ip'      => $ip, //$_SERVER['REMOTE_ADDR'],
							'login_counts'  => 1,
							'remember_token'=> $singletoken,
							'created_at'    => $nowtime,
							'updated_at'    => $nowtime,
							'deleted_at'    => NULL
						]);
					}
				}
				catch (Exception $e) {//捕获异常
					// echo 'Message: ' .$e->getMessage();
					// $result = $e->getMessage();
					$result = null;
				}

			} else {
				// 注意：adldap认证失败再由jwt-auth本地判断，不返回失败
				// return null;
			}
		}

		// 5.jwt-auth，判断用户认证
		// $credentials = $request->only('name', 'password');
		$credentials = ['name' => $name, 'password' => $password];

		$token = auth()->attempt($credentials);
		if (! $token) {
			// 如果认证失败，则返回null
			// return response()->json(['error' => 'Unauthorized'], 401);
			return null;
		}
		
		
		// 如果没有经过ldap, 则更新本地用户信息
		if (! $adldap) {
			try	{
					
				$result = User::where('name', $name)
					->increment('login_counts', 1, [
						'login_time' => $nowtime,
						'login_ttl' => $minutes,
						'login_ip'   => $ip, //$_SERVER['REMOTE_ADDR'],
						'remember_token'   => $singletoken,
					]);
			}
			catch (Exception $e) {//捕获异常
				// dd('Message: ' .$e->getMessage());
				return null;
			}
		}

		// return $this->respondWithToken($token);
		Cookie::queue('token', $token, $minutes);
		$app_key = substr(config('app.key'), 19, 12);
		Cookie::queue('singletoken'.md5($app_key), $singletoken, $minutes);
		// return $token;
		return 1;
		
  }

}
