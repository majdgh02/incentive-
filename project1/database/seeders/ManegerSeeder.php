<?php

namespace Database\Seeders;

use App\Models\Maneger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManegerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Maneger::truncate();
        $m = new Maneger();
        $m->name = 'majd';
        $m->username = 'majdgh02';
        $m->password = '12345678';
        $m->status = false;
        $m->save();
    }
}
