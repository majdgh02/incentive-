<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            ManegerSeeder::class,
            TargetSeeder::class,
            //EvaluationSeeder::class
        ]);
        \App\Models\Employee::factory(10)->create();
    }
}
