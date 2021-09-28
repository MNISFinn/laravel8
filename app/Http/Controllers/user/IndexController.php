<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller {

    public function userList(Request $request) {
        $page_size = $request['page_size'];
        $page_num = $request['page_num'];
        $list = User::getUserList($page_size, $page_num);
        return ['code' => 0, 'message' => 'successfully', 'data' => $list];
    }
}
