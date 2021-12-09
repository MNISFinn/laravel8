<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model {
    use HasFactory;

    protected $table      = 'delivery_order';
    protected $primaryKey = 'delivery_order_id';

    static public function saveOrder($data) {
        return DeliveryOrder::insertGetId($data);
    }
}
