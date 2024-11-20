<?php

namespace Database\Seeders;

use App\Models\PolicyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        PolicyType::factory()->count(20)->create();
    }
}
