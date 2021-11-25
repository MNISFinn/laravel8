<?php

namespace App\Http\Controllers\community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    public function addCommunities(Request $request) {
        if (empty($request['communities'])) {
            return responseResult(1000, '请选择小区');
        }
        $data = [];
        foreach ($request['communities'] as $key => $community) {
            $data[$key] = [
                'community_id'       => $community['id'],
                'community_name'     => $community['title'],
                'community_province' => $request['province'],
                'community_city'     => $request['city'],
                'community_address'  => $community['address'],
                'longitude'          => $community['location']['lng'],
                'latitude'           => $community['location']['lat']
            ];
        }
        try {
            DB::table('community')->insert($data);
            return responseResult(0, '添加小区成功');
        } catch (\Exception $exception) {
            Log::info('添加小区失败' . $exception);
            return responseResult(1001, '添加小区失败');
        }

    }
}
