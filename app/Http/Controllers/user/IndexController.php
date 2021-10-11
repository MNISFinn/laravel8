<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller {

    /**
     * 获取用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userList(Request $request) {
        $page_size = $request['page_size'];
        $page_num  = $request['page_num'];
        $list      = User::getUserList($page_size, $page_num);
        return responseResult(0, '获取成功', $list);
    }

    /**
     * 小程序用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $open_id   = $request['open_id'];
        $name      = $request['name'];
        $user_info = DB::table('user')
            ->where(['wx_open_id' => $open_id])->limit(1)
            ->get()->toArray();
        if (empty($user_info)) {
            $insert_data = [
                'user_name'      => $name,
                'wechat_name'    => $name,
                'wechat_open_id' => $open_id
            ];
            DB::table('user')->insert($insert_data);
            return responseResult(0, '登录成功');
        } else {
            return responseResult(0, '登录成功');
        }
    }

    /**
     * 微信用户通过code获取session_key和openid
     * @param Request $request
     * @return \Illuminate\Http\Client\Response
     */
    public function weChatAuthorization(Request $request) {
        $wx_config  = config('wechat');
        $url        = $wx_config['authorization_code_url'];
        $app_id     = $wx_config['app_id'];
        $app_secret = $wx_config['app_secret'];
        $code       = $request['code'];
        $params     = [
            'appid'      => $app_id,
            'secret'     => $app_secret,
            'js_code'    => $code,
            'grant_type' => 'authorization_code'
        ];
        // 获取session_key和openid
        $result = Http::get($url, $params);
        return $result;
    }
}
