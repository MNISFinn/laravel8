<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPayment extends Model {
    use HasFactory;

    protected $table      = 'delivery_payment';
    protected $primaryKey = 'delivery_payment_id';

    static public function savePayment($data) {
        return DeliveryPayment::insert($data);
    }
}
