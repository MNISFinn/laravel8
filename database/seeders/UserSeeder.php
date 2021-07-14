<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 0; $i<20000; $i++) {
            DB::table('user')->insert([
                'name' => Str::random(3),
                'email' => Str::random(10).'@foxmail.com',
                'mobile' => $this->generateMobileNum(),
                'wechat_code' => Str::random(12),
                'passwd' => md5(rand(100000, 99999)),
                'login_type' => rand(1, 2),
                'state' => rand(1, 2),
                'school_name' => Str::random(10),
                'class_name' => Str::random(4)
            ]);
        }
    }

    /**
     * 随机生成手机号码
     * @return string
     */
    private function generateMobileNum() {
        $pre_num = ['130','131','132','133','134','135','136','137','138','139','144','147','150','151','152','153','155','156','157','158','159','176','177','178','180','181','182','183','184','185','186','187','188','189'];
        $rand_key = array_rand($pre_num);
        $num = $pre_num[$rand_key].rand(10000000,99999999);
        return $num;
    }
}
