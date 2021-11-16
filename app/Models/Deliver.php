<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliver extends Model {
    use HasFactory;

    const VERIFIED = 1;

    protected $table      = 'deliver';
    protected $primaryKey = 'deliver_id';

    /**
     * 判断是否配送员
     * @param $user_id 用户ID
     * @return bool
     */
    static function isDeliver($user_id) {
        $result = true;
        $info   = Deliver::where(['user_id' => $user_id, 'status' => self::VERIFIED])->first();
        if (empty($info)) {
            $result = false;
        }
        return $result;
    }

    /**
     * 注册配送员
     * @param $data
     * @return mixed
     */
    static function registerDeliver($data) {
        $result = Deliver::insert($data);
        return $result;
    }

    /**
     * 更新信息
     * @param $data
     * @param $id
     * @return bool
     */
    static function modifyDelivery($data, $id) {
        $result = Deliver::where(['delivery_id' => $id])->update($data);
        return $result;
    }

    /**
     * 通过用户id查询是否申请注册过配送员
     * @param $user_id
     * @return mixed
     */
    static function findDeliverByUserId($user_id) {
        return Deliver::where(['user_id' => $user_id])->first();
    }
}
