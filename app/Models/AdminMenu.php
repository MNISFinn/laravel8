<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model {
    use HasFactory;

    protected $primaryKey = 'menu_id';

    static public function getList() {
        return AdminMenu::where(['status' => 1])->get(['menu_id', 'menu_name'])->toArray();
    }
}
