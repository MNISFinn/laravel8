<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderGoods extends Model {
    use HasFactory;

    protected $table      = 'delivery_goods';
    protected $primaryKey = 'delivery_goods_id';

    static public function saveGoods($data) {
        return DeliveryOrderGoods::insert($data);
    }
}
