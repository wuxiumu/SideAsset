<?php

use Illuminate\Database\Seeder;

class ServersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servers')->insert([
            [
                'name' => '阿里云ECS-01',
                'type' => 'ECS',
                'ip' => '123.123.123.1',
                'location' => '北京',
                'os' => 'CentOS 7.9',
                'purpose' => '主站服务',
                'project' => '博客项目',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
