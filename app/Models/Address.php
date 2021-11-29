<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model {
    use HasFactory;
    protected $table      = 'address';
    protected $primaryKey = 'id';
    protected $fillable   = ['id', 'community_id', 'address'];
    public    $timestamps = false;

    static function addAddress(array $data) {
        $id = Address::insertGetId($data);
        return $id;
    }

    static function queryAddressList($condition, $orderBy = 'desc') {
        return Address::rightJoin('community', 'address.community_id', '=', 'community.community_id')
            ->where($condition)->orderBy('id', $orderBy)->get()->toArray();
    }
}
