<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenuItem extends Model {
    use HasFactory;

    protected $primaryKey = 'menu_item_id';

    static public function getList() {
        return AdminMenuItem::where(['status' => 1])->get(['menu_item_id', 'item_name'])->toArray();
    }
}
