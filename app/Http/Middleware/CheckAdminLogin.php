<?php

namespace App\Http\Middleware;

use App\Models\Node;
use Closure;

class CheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $param 中间件传参  在绑定中间件地方 :值
     * @return mixed
     */
//    public function handle($request, Closure $next, $param)
    public function handle($request, Closure $next)
    {
        /*dump($param);
        echo '<h3>我是一个中间件</h3>';*/

        // 用户是否登录检查
        if (!auth()->check()) {
            return redirect(route('admin.login'))->withErrors(['error' => '请登录']);
        }

        // 访问的权限
        $auths = is_array(session('admin.auth')) ? array_filter(session('admin.auth')) : [];
        $auths = array_merge($auths, config('rbac.allow_route'));
        // dd($auths);
        // 访问当前路由
        $currentRoute = $request->route()->getName();

        // 权限判断
        if (auth()->user()->username != config('rbac.super') && !in_array($currentRoute, $auths)) {
            exit('你没有权限');
        }

        // 使用request传到下级去
        $request->auths = $auths;

        /*$auths = is_array(session('admin.auth')) ? array_keys(session('admin.auth')) : [];
        // 访问当前路由
        $currentRoute = $request->route()->getName();
        $info         = Node::where('route_name', $currentRoute)->first();
//        dd($info);
        if (empty($info)) {
            return redirect(route('admin.login'))->withErrors(['请重新登陆']);
        }
        if (auth()->user()->username != config('rbac.super') && !in_array($currentRoute, config('rbac.allow_route'))) {
            exit('你没有权限');
        }
        // 权限判断
        if (auth()->user()->username != config('rbac.super') && !in_array($info['id'], $auths)) {
            exit('你没有权限');
        }*/

        // 如果没有停止则向后执行
        return $next($request);
    }
}
