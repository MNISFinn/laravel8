<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliver extends Model {
    use HasFactory;

    protected $table      = 'deliver';
    protected $primaryKey = 'deliver_id';

    /**
     * 判断是否配送员
     * @param $user_id 用户ID
     * @return bool
     */
    static function isDeliver($user_id) {
        $result = true;
        $info   = Deliver::where(['user_id' => $user_id])->first();
        if (empty($info)) {
            $result = false;
        }
        return $result;
    }
}
