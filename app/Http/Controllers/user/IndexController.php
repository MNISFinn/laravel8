<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Deliver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller {

    const SUCCESSFUL = 0;
    const EMPTY_CODE = 1000;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * 小程序用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $name       = $request['name'];
        $code       = $request['code'];
        $avatar     = $request['avatar'];
        $is_deliver = false;
        if (!$code) {
            return responseResult(self::EMPTY_CODE, 'code不能为空');
        }
        $open_id   = $this->weChatAuthorization($code)['openid'];
        $user_info = User::where(['wechat_openid' => $open_id])->first();
        if (empty($user_info)) {
            $insert_data = [
                'user_name'       => $name,
                'wechat_name'     => $name,
                'wechat_openid'   => $open_id,
                'avatar'          => $avatar,
                'register_time'   => time(),
                'last_login_time' => time()
            ];
            DB::table('user')->insert($insert_data);
            $user_info = User::where(['wechat_openid' => $open_id])->first();
        } else {
            $update_data = [
                'wechat_name'     => $name,
                'avatar'          => $avatar,
                'last_login_time' => time()
            ];
            DB::table('user')->where(['wechat_openid' => $open_id])->update($update_data);
            $user_info = User::where(['wechat_openid' => $open_id])->first();
            // 判断是不是配送员
            $is_deliver = Deliver::isDeliver($user_info['user_id']);
        }

        if (!$token = auth('api')->login($user_info)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 5, // 5小时
            'user_info'  => [
                'user_name'   => $user_info['user_name'],
                'user_avatar' => $user_info['avatar'],
                'mobile'      => $user_info['mobile'],
                'email'       => $user_info['email'],
                'is_deliver'  => $is_deliver
            ],
        ];
        return responseResult(self::SUCCESSFUL, '登录成功', $data);

    }

    public function me() {
        $data = auth('api')->user();
        unset($data['wechat_open_id']);
        return responseResult(self::SUCCESSFUL, '登录成功', $data);
    }

    public function logout() {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh() {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token) {
        $data = [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 5 // 5小时
        ];
        return responseResult(self::SUCCESSFUL, '登录成功', $data);
    }

    /**
     * 微信用户通过code获取session_key和openid
     * @param $code
     * @return \Illuminate\Http\Client\Response
     */
    public function weChatAuthorization($code) {
        $wx_config  = config('wechat');
        $url        = $wx_config['authorization_code_url'];
        $app_id     = $wx_config['app_id'];
        $app_secret = $wx_config['app_secret'];
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
