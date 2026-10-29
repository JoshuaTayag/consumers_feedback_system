<?php

namespace Database\Seeders;

use App\Models\DataManagement\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 5) as $number) {
            Area::firstOrCreate(['name' => "AREA {$number}"]);
        }
    }
}
