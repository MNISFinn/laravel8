<?php
/**
 * 用户管理
 */

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    const SUCCESSFUL = 0;

    /**
     * 获取用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userList(Request $request) {
        $page_size = $request['page_size'];
        $page_num  = $request['page_num'];
        $list      = User::getUserList($page_size, $page_num);
        return responseResult(self::SUCCESSFUL, '获取成功', $list);
    }
}
