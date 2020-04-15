<?php

namespace App\Http\Controllers\Api;

use App\Models\Renting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 登录 post
    public function login(Request $request)
    {
        // 表单验证
        // 异步捕获  ajax 和  接口会用
        try {
            // 验证不通过跳转到 首页  需要自定义异常走向，错误信息要让自己来确定
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 1000, 'msg' => '账号或密码不能为空'], 400);
        }
        // 查看对象中可用的方法
        // dump(get_class_methods(auth()->guard('api')));
        $bool = auth()->guard('apiweb')->attempt($request->all());
        // 账号登录成功
        if ($bool) {
            // 生成token
            // 得到用户模型对象
            $userModel = auth()->guard('apiweb')->user();
            // 生成token 保存在服务器端1份，给客户返回1份
            $token = $userModel->createToken('api')->accessToken;

            // 判断接口当天是否超过2000次
            if ($userModel->click > env('APINUM')) {
                return response()->json(['status' => 1002, 'msg' => '当天请求次数超上限'], 500);
            }
            // 让请求次数加1  数据一致
            $userModel->increment('click'); // set click=click+1

            $data = [
                'expire' => 7200,
                'token'  => $token
            ];
            return response()->json($data);
        } else {
            return response()->json(['status' => 1001, 'msg' => '账号或密码不正确'], 401);
        }
    }

    // 小程序登录
    public function wxlogin(Request $request)
    {
        // 此2个值是小程序后台提供
        $appid  = 'wxbceadeedecba5c90';
        $secret = '063837e61eda58febdcb86f922674271';
        // 小程序传过来的
        $code = $request->get('code');

        // 请求地址
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        $url = sprintf($url, $appid, $secret, $code);

        // 申请请求客户端  verify 不检查证书ssl
        $client   = new Client(['timeout' => 5, 'verify' => false]);
        $response = $client->get($url);
        $json     = (string)$response->getBody();

        // json转为数组
        $arr = json_decode($json, true);

        // 写入到数据表中
        try {
            Renting::create(['openid' => $arr['openid']]);
        } catch (\Exception $exception) {
        }

        return $json;

    }
}
