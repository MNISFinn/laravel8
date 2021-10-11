<?php
/**
 * Created by PhpStorm.
 * User: shaobin
 * 微信小程序配置
 * Date: 2021-10-11
 * Time: 20:16
 */

return [
    'app_id' => env('WX_APP_ID'),
    'app_secret' => env('WX_APP_SECRET'),
    'authorization_code_url' => 'https://api.weixin.qq.com/sns/jscode2session'
];