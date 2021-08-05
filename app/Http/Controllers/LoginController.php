<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller {

    public function login(Request $request) {
        $user_name = $request['user'];
//        $password = $request['password'];
        $data = [
            'code' => 0,
            'message' => '登录成功'
        ];
        if ($user_name == 'admin') {
            $data['data']['token'] = 'admin';
        } else {
            $data['data']['token'] = 'vip';
        }
        return $data;
    }

    public function get_permission(Request $request) {
        $user_name = $request['user'];
        $data = [
            'code' => 0,
            'message' => '获取权限成功'
        ];
        if ($user_name == 'admin') {
            $data['data'] = [
                [
                    'name' => '订单管理',
                    'children' => [
                        [
                            'name' => '订单列表'
                        ]
                    ]
                ],
                [
                    'name' => '用户管理',
                    'children' => [
                        [
                            'name' => '用户列表'
                        ]
                    ]
                ]
            ];
        } else {
            $data['data'] = $data['data'] = [
                [
                    'name' => '订单管理',
                    'children' => [
                        [
                            'name' => '订单列表'
                        ]
                    ]
                ],
                [
                    'name' => '商品管理',
                    'children' => [
                        [
                            'name' => '商品列表'
                        ]
                    ]
                ]
            ];
        }
        return $data;
    }
}
