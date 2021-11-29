<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model {
    use HasFactory;
    protected $table      = 'community';
    protected $primaryKey = 'community_id';
    protected $fillable   = ['community_id', 'community_name',
        'community_province', 'community_city', 'community_address',
        'longitude', 'latitude'];
    public $timestamps = false;

    /**
     * 添加小区
     * @param $id 小区id
     * @param $data 小区信息
     */
    static function addCommunity($id, array $data) {
        Community::firstOrCreate(
            ['community_id' => $id],
            [
                'community_id'       => $data['id'],
                'community_name'     => $data['title'],
                'community_province' => $data['province'],
                'community_city'     => $data['city'],
                'community_address'  => $data['addr'],
                'longitude'          => $data['longitude'],
                'latitude'           => $data['latitude'],
            ]
        );
    }
}
