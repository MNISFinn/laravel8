<?php
/**
 * Created by PhpStorm.
 * User: shaobin
 * Date: 2021-09-29
 * Time: 21:54
 */

use Qcloud\Cos\Client;
use Illuminate\Support\Facades\Log;

/**
 * 统一接口返回格式
 */
if (!function_exists('responseResult')) {
    function responseResult($code = 0, $message = '', $data = []) {
        return response()->json([
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ]);
    }
}

/**
 * 检验数据的真实性，并且获取解密后的明文.
 * @param $sessionKey string 用户在小程序登录后获取的会话密钥
 * @param $appid string 小程序的appid
 * @param $encryptedData string 加密的用户数据
 * @param $iv string 与用户数据一同返回的初始向量
 * @param $data string 解密后的原文
 *
 * @return int 成功0，失败返回对应的错误码
 */
if (!function_exists('decryptWeChatData')) {
    function decryptWeChatData($appid, $sessionKey, $encryptedData, $iv, &$data) {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return -41002;
        }
        $aesIV     = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result    = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj   = json_decode($result);
        if ($dataObj == NULL) {
            return -41013;
        }
        if ($dataObj->watermark->appid != $appid) {
            return -41003;
        }
        $data = $result;
        return 0;
    }
}

/**
 * 上传文件到COS
 */
if (!function_exists('uploadFileToCOS')) {
    function uploadFileToCOS($file, $bucket_name) {
        $path      = [];
        $appId     = env('COS_APP_ID');
        $secretId  = env('COS_SECRET_ID');
        $secretKey = env('COS_SECRET_KEY');
        $region    = env('COS_REGION');
        $cosClient = new Client([
            'region'      => $region,
            'schema'      => 'https', //协议头部，默认为http
            'credentials' => [
                'secretId'  => $secretId,
                'secretKey' => $secretKey
            ]
        ]);
        try {
            $bucket = $bucket_name . '-' . $appId; //存储桶名称 格式：BucketName-APPID
            $result = $cosClient->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $secretKey,
                    'Body'   => fopen($file, 'rb')
                ]
            );
            Log::info($result);
            $path   = 'https://' . (array)$result['Location'];
        } catch (\Exception $e) {
            // 请求失败
            Log::info('文件上传失败：' . $e);
            return;
        }

        return $path;
    }
}