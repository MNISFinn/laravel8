<?php

namespace App\Http\Controllers\deliver;

use App\Http\Controllers\Controller;
use App\Models\Deliver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller {

    const SUCCESS = 0;
    const ERROR = 1001;
    const USER_BLACKLIST = 1002;

    const STATUS_WAIT_TO_VERIFY = 0;
    const STATUS_IN_BLACKLIST = 5;

    /**
     * 注册配送员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $user_id       = $request['user_id'];
        $true_name     = $request['true_name'];
        $mobile        = $request['mobile'];
        $email         = $request['email'];
        $id_card_front = $request['id_card_front'];
        $id_card_back  = $request['id_card_back'];
        // 判断是否申请注册过配送员
        $deliver_info = Deliver::findDeliverByUserId($user_id);
        if ($deliver_info) {
            if ($deliver_info['status'] == self::STATUS_IN_BLACKLIST) {
                return responseResult(
                    self::USER_BLACKLIST,
                    '该用户因为违规，不能再次成为配送员。有疑问请联系管理员，谢谢。'
                );
            }
        }

        $data = [
            'user_id'       => $user_id,
            'true_name'     => $true_name,
            'mobile'        => $mobile,
            'email'         => $email,
            'id_card_front' => $id_card_front,
            'id_card_back'  => $id_card_back,
            'status'        => self::STATUS_WAIT_TO_VERIFY,
            'register_time' => time()
        ];

        try {
            if (empty($deliver_info)) {
                $result = Deliver::registerDeliver($data);
            } else {
                $result = Deliver::modifyDelivery($data, $deliver_info['delivery_id']);
            }

            if ($result) {
                return responseResult(self::SUCCESS, '提交成功，等待管理员审核1-2个工作日！');
            } else {
                return responseResult(self::ERROR, '提交失败，请重试');
            }
        } catch (\Exception $exception) {
            Log::info($exception);
            return responseResult(self::ERROR, '提交失败，请重试');
        }
    }
}
