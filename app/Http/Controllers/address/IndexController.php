<?php

namespace App\Http\Controllers\address;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    const SUCCESS = 0;
    const ADD_ADDRESS_EXCEPTION = 1000;
    const EMPTY_COMMUNITY_ERROR = 1001;
    const EMPTY_ADDRESS_ERROR = 1002;
    const EMPTY_USER_ID_ERROR = 1003;


    /**
     * 添加地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAddress(Request $request) {
        $community = $request['community'];
        if (empty($request['user_id'])) {
            return responseResult(self::EMPTY_USER_ID_ERROR, '请登录');
        }
        if (empty($request['address'])) {
            return responseResult(self::EMPTY_ADDRESS_ERROR, '请填写详细地址');
        }
        if (empty($community)) {
            return responseResult(self::EMPTY_ADDRESS_ERROR, '请选择小区');
        }
        $insert_addr = [
            'user_id'      => $request['user_id'],
            'community_id' => $community['id'],
            'address'      => $request['address']
        ];

        try {
            Community::addCommunity($community['id'], $community);
            Address::addAddress($insert_addr);
            return responseResult(self::SUCCESS, '添加成功');
        } catch (\Exception $exception) {
            Log::info($exception);
            return responseResult(self::ADD_ADDRESS_EXCEPTION, '添加异常');
        }
    }

    public function queryAddressList(Request $request) {
        if (empty($request['user_id'])) {
            return responseResult(self::EMPTY_USER_ID_ERROR, '请登录');
        }
        $condition = [
            'user_id'        => $request['user_id'],
            'address.status' => 1
        ];
        $list      = Address::queryAddressList($condition);
        return responseResult(self::SUCCESS, '获取成功', $list);

    }
}
