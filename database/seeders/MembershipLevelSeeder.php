<?php

namespace Database\Seeders;

use App\Models\MembershipLevel;
use Illuminate\Database\Seeder;

class MembershipLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Basic', 'Silver', 'Gold', 'Platinum'] as $name) {
            MembershipLevel::firstOrCreate(['name' => $name], [
                'fee' => 0,
                'validity_days' => 365,
            ]);
        }
    }
}
