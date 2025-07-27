<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'platform' => '阿里云',
                'username' => 'aric001',
                'email' => 'aric@aliyun.com',
                'bind_project' => '副业CMS',
                'login_type' => '邮箱',
                'note' => '管理员',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
