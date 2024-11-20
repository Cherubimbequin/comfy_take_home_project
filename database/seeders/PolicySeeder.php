<?php

namespace Database\Seeders;

use App\Models\PolicyManager;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PolicyManager::factory()->count(15)->create();
    }
}
