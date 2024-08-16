<?php

namespace App\Http\Controllers;

use App\Models\Acceptancerate;
use App\Models\Evaluation;
use DateTime;
use Illuminate\Http\Request;

class AcceptancerateController extends Controller
{
    public function add_acceptance($employee_id, $rate, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $acceptance = Acceptancerate::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($acceptance)){
            $new= new Acceptancerate;
            $new->employee_id = $employee_id;
            $new->rate = $rate;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $evaluation = Evaluation::where([['type' , 'message.accepternce_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $new->points = 0;
                $target->put_target_point($employee_id, 0, 0, $interval->m, $interval->y);
            }else{
                $new->points = $evaluation->value;
                $target->put_target_point($employee_id, 0, $evaluation->value, $interval->m, $interval->y);
            }
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => __('message.ar_inrol_success')
                ],201);
        }else{
            $evaluation = Evaluation::where([['type' , 'message.accepternce_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $acceptance->points, 0, $interval->m, $interval->y);
                $acceptance->points = 0;
            }else{
                $old_points = $acceptance->points;
                $target->put_target_point($employee_id, $old_points, $evaluation->value, $interval->m, $interval->y);
                $acceptance->points = $evaluation->value;
            }
            $acceptance->rate = $rate;
            $acceptance->save();
            return response()->json([
                'status' => true ,
                'message' => __('message.ar_update_success')
                ],200);
        }
    }

    public function delet_acceptance(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:acceptancerates',
        ]);

        Acceptancerate::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => __('message.ar_delete_success')
        ],200);
    }

    public function get_acceptance_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $acceptance = Acceptancerate::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => __('message.ac_get'),
            'data' => $acceptance
        ],200);
    }
}
