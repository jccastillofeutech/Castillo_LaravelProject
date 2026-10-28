<?php

namespace Database\Seeders;

use App\Models\MembershipCategory;
use Illuminate\Database\Seeder;

class MembershipCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $name) {
            MembershipCategory::firstOrCreate(['name' => $name]);
        }
    }
}
