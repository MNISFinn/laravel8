<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PauseController extends Controller
{
    //
    public function test() {
        $permission = [
            ['menu' => 1, 'menu_item' => 1],
            ['menu' => 2, 'menu_item' => 2],
        ];
        return serialize($permission);
    }
}
