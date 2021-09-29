<?php
/**
 * Created by PhpStorm.
 * User: shaobin
 * Date: 2021-09-29
 * Time: 21:54
 */

/**
 * 统一接口返回格式
 */
if (!function_exists('responseResult')) {
    function responseResult($code = 0, $message = '', $data = []) {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}