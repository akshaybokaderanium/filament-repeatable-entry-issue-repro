<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConsentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consents')->insert([
            [
                'title' => 'Sample Consent 1',
                'version' => 1,
                'is_mandatory' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sample Consent 2',
                'version' => 1,
                'is_mandatory' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
