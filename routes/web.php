<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\LoginController as AdminLogin;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\user\IndexController as UserIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'login']);
Route::get('/get_permission', [LoginController::class, 'get_permission']);

Route::group([
    'jwt.role:admin',
    'jwt.auth'
], function () {
    // 后台管理员登录登出、刷新token
    Route::get('/admin_login', [AdminLogin::class, 'login']);
    Route::get('/admin_info', [AdminLogin::class, 'me']);
    Route::get('/admin_logout', [AdminLogin::class, 'logout']);
    Route::get('/admin_refresh', [AdminLogin::class, 'refresh']);
    // 后台管理员权限
    Route::get('get_permission', [PermissionController::class, 'getPermission']);
    // 用户管理
    Route::post('/user_list', [UserController::class, 'userList']);
});

Route::group([
    'jwt.role:user',
    'jwt.auth'
], function () {
    Route::post('/user_login', [UserIndex::class, 'login']);
    Route::post('/user_token_refresh', [UserIndex::class, 'refresh']);
    Route::post('/user_info', [UserIndex::class, 'me']);
});
