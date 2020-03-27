<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空数据表
        \App\Models\User::truncate();

        //添加模拟数据 100用户
        factory(\App\Models\User::class, 100)->create();

        //规定id1=1用户名为admin
        \App\Models\User::where('id', 1)->update(['username' => 'admin']);
    }
}
