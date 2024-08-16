<?php

namespace App\Http\Controllers;

use App\Models\Callnum;
use App\Models\Errorrate;
use App\Models\Evaluation;
use App\Models\Followerror;
use App\Models\Problemtic;
use DateTime;
use Illuminate\Http\Request;

class ErrorrateController extends Controller
{
    public function add_errorrate($employee_id, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $error = Errorrate::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
        if(empty($error)){
            $callnum = Callnum::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $Follow = Followerror::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = ($Follow->num/$callnum->num)*100;
            $new = new Errorrate();
            $new->employee_id = $employee_id;
            $new->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'message.error_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $new->points = 0;
                $target->put_target_point($employee_id, 0, 0, $interval->m, $interval->y);
            }else{
                $new->points = $evaluation->value;
                $target->put_target_point($employee_id, 0, $evaluation->value, $interval->m, $interval->y);
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
            $Follow = Followerror::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = ($Follow->num/$callnum->num)*100;
            $error->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'message.error_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $error->points, 0, $interval->m, $interval->y);
                $error->points = 0;
            }else{
                $old_points = $error->points;
                $target->put_target_point($employee_id, $old_points, $evaluation->value, $interval->m, $interval->y);
                $error->points = $evaluation->value;
            }
            $error->save();
            return response()->json([
                'status' => true ,
                'message' => 'Error rate Updated successfully'
            ],200);
        }
    }
}
