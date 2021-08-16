<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BuyController;
use App\Http\Controllers\api\Buy2Controller;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/buy_test', [BuyController::class, 'test'])->middleware('login');

Route::group(['middleware' => 'login'],function () {
    Route::get('/buy_test', [BuyController::class, 'test']);
//    Route::get('/buy_test2', [Buy2Controller::class, 'test'])->withoutMiddleware('login');
    Route::get('/buy_test2', [Buy2Controller::class, 'test']);
});

Route::get('/login', [LoginController::class, 'login']);
Route::get('/get_permission', [LoginController::class, 'get_permission']);