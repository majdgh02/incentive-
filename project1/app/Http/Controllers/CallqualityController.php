<?php

namespace App\Http\Controllers;

use App\Models\Callquality;
use App\Models\Evaluation;
use DateTime;
use Illuminate\Http\Request;

class CallqualityController extends Controller
{
    public function add_callquality($employee_id, $quality, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $callquality = Callquality::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callquality)){
            $new= new Callquality;
            $new->employee_id = $employee_id;
            $new->quality = $quality;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $evaluation = Evaluation::where([['type' , 'message.Call_quality'],['from', '<=', $quality],['to', '>=', $quality]])->select()->first();
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
                'message' => __('message.cq_inrol_success')
                ],201);
        }else{
            $evaluation = Evaluation::where([['type' , 'message.Call_quality'],['from', '<=', $quality],['to', '>=', $quality]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $callquality->quality, 0, $interval->m, $interval->y);
                $callquality->points = 0;
            }else{
                $old_points = $callquality->points;
                $target->put_target_point($employee_id, $old_points, $evaluation->value, $interval->m, $interval->y);
                $callquality->points = $evaluation->value;
            }
            $callquality->quality = $quality;
            $callquality->save();
            return response()->json([
                'status' => true ,
                'message' => __('message.cq_update_success')
                ],200);
        }
    }

    public function delet_callquality(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:callqualities',
        ]);

        Callquality::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => __('message.cq_delete_success')
        ]);
    }

    public function get_callquality_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callquality = Callquality::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => __('message.cq_get'),
            'data' => $callquality
        ]);
    }
}
