<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminMenu;
use App\Models\AdminMenuItem;

class LoginController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin', ['except' => ['login']]);
    }

    public function login() {
        $credentials = request(['name', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth('admin')->user())->getData();
    }

    public function logout() {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh() {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'permission' => $this->respondWithPermission()
        ]);
    }

    /**
     * 获取管理员对应权限
     * @param $token
     */
    protected function respondWithPermission() {
        $admin_info = $this->me();
        $admin_id = $admin_info->admin_id;
        $permission = Admin::getPermission($admin_id);
        $menu = unserialize($permission->permission_detail);
        $menus = $this->getPermissionMenus($menu);
        return $menus;
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
            foreach ($menu_array as $value) {
                $permission_menus[] = [
                    'menu_id' => $value['menu'],
                    'menu_name' => $menu_list_by_id[$value['menu']]['menu_name'],
                    'menu_item_id' => $value['menu_item'],
                    'menu_item_name' => $menu_item_list_by_id[$value['menu_item']]['item_name']
                ];
            }
        }
        return $permission_menus;
    }
}
