<?php

namespace App\Http\Controllers;

use App\Models\Callnum;
use App\Models\Callsrate;
use App\Models\Evaluation;
use App\Models\Work;
use DateTime;
use Illuminate\Http\Request;

class CallsrateController extends Controller
{
    public function add_callsrate($employee_id, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $callsrate = Callsrate::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
        if(empty($callsrate)){
            $callnum = Callnum::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $workh = Work::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = $callnum->num/$workh->houres;
            $new = new Callsrate();
            $new->employee_id = $employee_id;
            $new->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'Call Rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $new->points = 0;
                $target->put_target_point($employee_id, null, 0, $interval->m, $interval->y);
            }else{
                $new->points = $evaluation->value;
                $target->put_target_point($employee_id, null, $evaluation->value, $interval->m, $interval->y);
            }
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => 'Calls rate inroled successfully'
            ],201);
        }
        else{
            $callnum = Callnum::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $workh = Work::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = $callnum->num/$workh->houres;
            $callsrate->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'Call Rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $callsrate->points, 0, $interval->m, $interval->y);
                $callsrate->points = 0;
            }else{
                $target->put_target_point($employee_id, $callsrate->points, $evaluation->value, $interval->m, $interval->y);
                $callsrate->points = $evaluation->value;
            }
            $callsrate->save();
            return response()->json([
                'status' => true ,
                'message' => 'Calls rate Updated successfully'
            ],200);
        }
    }
}
