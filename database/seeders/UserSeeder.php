<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    protected static int $noOfSeeds = 20;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->createMany(static::$noOfSeeds);
    }
}
