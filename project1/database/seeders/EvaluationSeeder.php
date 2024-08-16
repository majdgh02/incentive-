<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Evaluation::truncate();
        $e1 = new Evaluation();
        $e1->name = 'weakw';
        $e1->type = 'message.work_rate';
        $e1->from = 0;
        $e1->to = 100;
        $e1->value = 10;
        $e1->save();

        $e2 = new Evaluation();
        $e2->name = 'weakcr';
        $e2->type = 'message.Call_rate';
        $e2->from = 0;
        $e2->to = 100;
        $e2->value = 20;
        $e2->save();

        $e3 = new Evaluation();
        $e3->name = 'weakcr';
        $e3->type = 'message.Call_quality';
        $e3->from = 0;
        $e3->to = 100;
        $e3->value = 30;
        $e3->save();

        $e4 = new Evaluation();
        $e4->name = 'weakcr';
        $e4->type = 'message.prob_tic_num';
        $e4->from = 0;
        $e4->to = 100;
        $e4->value = 40;
        $e4->save();

        $e5 = new Evaluation();
        $e5->name = 'weakcr';
        $e5->type = 'message.accepternce_rate';
        $e5->from = 0;
        $e5->to = 100;
        $e5->value = 50;
        $e5->save();

        $e6 = new Evaluation();
        $e6->name = 'weakcr';
        $e6->type = 'message.error_rate';
        $e6->from = 0;
        $e6->to = 100;
        $e6->value = 60;
        $e6->save();

        $e7 = new Evaluation();
        $e7->name = 'weakcr';
        $e7->type = 'message.call_return';
        $e7->from = 0;
        $e7->to = 100;
        $e7->value = 70;
        $e7->save();
    }
}
