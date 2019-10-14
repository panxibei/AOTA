<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;
use Closure;
use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Cookie;
use App\Models\Admin\Config;
use App\Models\Admin\User;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
		// 请求前处理内容
        // return $next($request);
        
        // if 就是看有没有 down 文件；有则执行 if 里面的代码
        if ($this->app->isDownForMaintenance()) {

            // 强制哪些IP可进入系统而不受维护模式影响
            $config = Config::where('cfg_name', 'SITE_MAINTENANCE_ALLOWED')
                ->orWhere('cfg_name', 'SITE_MAINTENANCE_MESSAGE')
                ->pluck('cfg_value', 'cfg_name')->toArray();
            // dd($config);
            $allowed_ip = explode(',', $config['SITE_MAINTENANCE_ALLOWED']);
            // if (in_array($request->getClientIp(), ['127.0.0.1', '172.22.14.212'])) {
            if (in_array($request->getClientIp(), $allowed_ip)) {
                return $next($request);
            }

            
            // 将 down 文件里面的json数据转换成数组
            $data = json_decode(file_get_contents($this->app->storagePath().'/framework/down'), true);

            // 看一下数组里面的 allowed 与请求过来的 IP 匹配吗，匹配就进入应用
            if (isset($data['allowed']) && IpUtils::checkIp($request->ip(), (array) $data['allowed'])) {
                return $next($request);
            }

            // 这个就是看一下子类定义的除外路由，有没有与请求的路由匹配上，匹配上就进入应用
            if ($this->inExceptArray($request)) {
                return $next($request);
            }



            // 经过披荆斩棘，一路坎坷，没有一个符合条件，不好意思，甩你一脸狗粮（维护页面），难受不。。。哈哈哈
            // setcookie('token', null, -1, '/');
            // setcookie('singletoken', null, -1, '/');
            // Cookie::queue(Cookie::forget('token'));
            // Cookie::queue(Cookie::forget('singletoken'));

            if($request->ajax()){
                // 如果是ajax请求，则返回空数组，由axios处理返回登录页面
				return response()->json(['jwt' => 'logout']);
			} else {
                // 如果是正常请求，则直接返回维护页面

                // 如果有系统维护的自定义配置值，则显示自定义配置内容
                if (!empty($config['SITE_MAINTENANCE_MESSAGE'])) {
                    $data['message'] = $config['SITE_MAINTENANCE_MESSAGE'];
                }

                // throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
                abort(503, $data['message'] == null || $data['message'] == '' ? '很抱歉，系统维护中！ 请稍后再试！' : $data['message']);
			}

        }
        return $next($request);

    }
}
