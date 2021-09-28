<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $primaryKey = 'user_id';
    protected $table = 'user';

    static public function getUserList($page_size = 10, $page_num = 1, array $column = ['*'], array $condition = []) {
        $data = User::where($condition)->paginate($page_size, $column, 'page', $page_num)->toArray();
        $result = [
            'data' => $data['data'],
            'current_page' => $data['current_page'],
            'page_size' => (int)$data['per_page'],
            'total' => $data['total']
        ];
        return $result;
    }
}
