<?php

use Illuminate\Database\Seeder;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logs')->insert([
            [
                'type' => '域名',
                'level' => '警告',
                'content' => 'example.com 3天后到期',
                'object' => 'example.com',
                'status' => '未读',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
