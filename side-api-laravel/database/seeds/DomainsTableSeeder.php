<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('domains')->insert([
            [
                'domain' => 'example.com',
                'registrar' => '阿里云',
                'purchase_date' => '2023-01-01',
                'expire_date' => '2025-01-01',
                'project' => '博客项目',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain' => 'ai-tools.site',
                'registrar' => '腾讯云',
                'purchase_date' => '2022-06-01',
                'expire_date' => '2024-06-01',
                'project' => '工具集',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
