<?php

namespace App\Http\Controllers\file;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller {

    const SUCCESS = 0;

    public function uploadFile(Request $request) {
        $bucket = $request['bucket'];
        $files = $request['files'];
        $path = uploadFileToCOS($files, $bucket);
        return responseResult(self::SUCCESS, '上传成功', $path);
    }
}
