<?php

use Illuminate\Database\Seeder;

class ApiServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_services')->insert([
            [
                'name' => 'OpenAI',
                'platform' => 'openai.com',
                'api_key' => 'sk-xxx',
                'bind_project' => 'AI对话',
                'quota' => '$10',
                'fee' => '$20/月',
                'doc_url' => 'https://platform.openai.com/docs',
                'note' => 'GPT-4 key',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
