<?php

namespace App\Http\Middleware;

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
            return redirect(route('admin.login'))->withErrors(['error'=>'请登录']);
        }
        // 如果没有停止则向后执行
        return $next($request);
    }
}
