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


    /**
     * 添加地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAddress(Request $request) {
        $community = $request['community'];
        if (empty($request['address'])) {
            return responseResult(self::EMPTY_ADDRESS_ERROR, '请填写详细地址');
        }
        if (empty($community)) {
            return responseResult(self::EMPTY_ADDRESS_ERROR, '请选择小区');
        }
        $insert_addr = [
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
}
