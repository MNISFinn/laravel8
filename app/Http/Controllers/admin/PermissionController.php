<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminMenu;
use App\Models\AdminMenuItem;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionController extends Controller {

    const ISNOT_EXIST_ADMIN = 1000;

    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function getPermission(Request $request) {
        $token = $request['user'];
        $admin = JWTAuth::setToken($token)->authenticate();
        if (!$admin) {
            return response()->json(['code' => self::ISNOT_EXIST_ADMIN, 'message' => '管理员不存在']);
        }
        $admin_id = $admin->admin_id;
        $permission = Admin::getPermission($admin_id);
        $menu = unserialize($permission->permission_detail);
        $menus = $this->getPermissionMenus($menu);
        $result = [
            'code' => 0,
            'message' => '获取成功',
            'data' => $menus
        ];
        return $result;
    }

    /**
     * 获取菜单权限
     * @param $menu_array 权限菜单
     * @return array
     */
    protected function getPermissionMenus($menu_array) {
        $permission_menus = [];
        $menu_list = AdminMenu::getList();
        $menu_list_by_id = array_column($menu_list, null, 'menu_id');
        $menu_item_list = AdminMenuItem::getList();
        $menu_item_list_by_id = array_column($menu_item_list, null, 'menu_item_id');
        if (!empty($menu_array)) {
            foreach ($menu_array as $index => $value) {
                $permission_menus[$index] = [
                    'menu_id' => $value['menu'],
                    'name' => $menu_list_by_id[$value['menu']]['menu_name'],
                ];
                foreach ($value['menu_item'] as $item) {
                    $permission_menus[$index]['children'][] = [
                        'menu_item_id' => $item,
                        'name' => $menu_item_list_by_id[$item]['item_name']
                    ];
                }
            }
        }
        return $permission_menus;
    }
}
