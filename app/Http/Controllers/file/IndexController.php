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
        $file = request()->file('file');
        Log::info($file);
//        $path = $this->dealFile($file);
        $path = uploadFileToCOS($file, $bucket);
        return responseResult(self::SUCCESS, '上传成功', $path);
    }

    /*protected function dealFile($file) {
        $ext       = $file->getClientOriginalExtension();//文件扩展名
        $file_name = date("YmdHis", time()) . '-' . uniqid() . "." . $ext;//保存的文件名
        if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) return response()->json(err('文件类型不是图片'));
        //把临时文件移动到指定的位置，并重命名
        $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'wchat_img' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d') . DIRECTORY_SEPARATOR;
        $bool = $file->move($path, $file_name);
        if ($bool) {
            $img_path = '/uploads/wchat_img/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file_name;
            $data     = [
                'domain_img_path' => get_domain() . $img_path,
                'img_path'        => $img_path,
            ];
            return response()->json(succ($data));
        } else {
            return response()->json(err("图片上传失败！"));
        }

    }*/
}
