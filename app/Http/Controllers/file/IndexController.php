<?php

namespace App\Http\Controllers\file;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    const SUCCESS = 0;

    public function uploadFile(Request $request) {
        Log::info($request);
        $bucket = $request['bucket'];
        $file   = request()->file('file');
        Log::info($file);
        $file_info = $this->dealFile($file);
        $path      = uploadFileToCOS($file_info, $bucket);
        @unlink($file_info['file_path']);
        return responseResult(self::SUCCESS, '上传成功', $path);
    }

    protected function dealFile($file) {
        $ext       = $file->getClientOriginalExtension();//文件扩展名
        $file_name = date("YmdHis", time()) . '-' . uniqid() . "." . $ext;//保存的文件名
        //把临时文件移动到指定的位置，并重命名
        $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'wchat_img' . DIRECTORY_SEPARATOR;
        Log::info('path');
        Log::info($path);
        $bool = $file->move($path, $file_name);
        if ($bool) {
            $file_path = public_path() . '/uploads/wchat_img/' . $file_name;
            return [
                'file_path' => $file_path,
                'file_name' => $file_name
            ];
        } else {
            return [];
        }

    }
}
