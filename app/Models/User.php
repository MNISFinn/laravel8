<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'wechat_openid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [
            'role' => 'user'
        ];
    }

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
