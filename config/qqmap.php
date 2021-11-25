<?php
/**
 * 腾讯地图配置
 * Created by PhpStorm.
 * User: shaobin
 * Date: 2021-11-22
 * Time: 22:07
 */
return [
    'key' => env('QQ_MAP_KEY'),
    'host' => 'https://apis.map.qq.com/',
    'api' => [
        'suggestion' => 'ws/place/v1/suggestion'
    ],
    'category' => [
        'community' => 'category=室内及附属设施,房产小区'
    ]
];