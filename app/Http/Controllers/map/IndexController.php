<?php

namespace App\Http\Controllers\map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    const SUCCESSFUL = 0;
    const ERROR = 1000;
    const NOT_IN_CURRENT_CITY = 0;
    const IN_CURRENT_CITY = 1;
    const NORMAL_POLICY = 0;
    const ADDRESS_POLICY = 1;
    const MAX_PAGE_SIZE = 20;
    const QUERY_SUCCESSFUL_STATUS = 0;
    const DATA_SOURCE = 'api';

    private $map_config = [];

    public function __construct() {
        $this->map_config = config('qqmap');
    }


    /**
     * 调用腾讯地图关键词搜索接口获取小区列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryCommunities(Request $request) {
        $communities = [];
        $params      = [
            'key'        => $this->map_config['key'],
            'keyword'    => $request['keyword'],
            'region'     => $request['region'],
            'policy'     => self::ADDRESS_POLICY,
            'region_fix' => self::IN_CURRENT_CITY,
            'filter'     => $this->map_config['category']['community'],
            'page_size'  => self::MAX_PAGE_SIZE,
            'page_index' => $request['page_index']
        ];
        $url         = $this->map_config['host'] . $this->map_config['api']['suggestion'];
        try {
            $res = Http::get($url, $params);
            if ($res['status'] == self::QUERY_SUCCESSFUL_STATUS && !empty($res['data'])) {
                foreach ($res['data'] as $datum) {
                    $communities[] = [
                        'id'       => $datum['id'],
                        'title'    => $datum['title'],
                        'address'  => $datum['address'],
                        'location' => $datum['location'],
                        'source'   => self::DATA_SOURCE
                    ];
                }
            }
            return responseResult(self::SUCCESSFUL, '获取小区列表成功', $communities);
        } catch (\Exception $exception) {
            Log::info('获取小区列表异常' . $exception);
            return responseResult(self::ERROR, '获取小区列表失败');
        }

    }
}
