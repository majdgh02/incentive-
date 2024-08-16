<?php

namespace App\Http\Controllers;

use App\Models\Callreturn;
use App\Models\Evaluation;
use DateTime;
use Illuminate\Http\Request;

class CallreturnController extends Controller
{
    public function add_callreturn($employee_id, $rate, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $callreturn = Callreturn::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callreturn)){
            $new= new Callreturn;
            $new->employee_id = $employee_id;
            $new->rate = $rate;
            $evaluation = Evaluation::where([['type', 'message.call_return'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
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
                'message' => 'Call return inrolled successfully'
                ],201);
        }else{
            $callreturn->rate = $rate;
            $evaluation = Evaluation::where([['type', 'message.call_return'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $callreturn->points, 0, $interval->m, $interval->y);
                $callreturn->points = 0;
            }else{
                $old_pooints = $callreturn->points;
                $target->put_target_point($employee_id, $old_pooints, $evaluation->value, $interval->m, $interval->y);
                $callreturn->points = $evaluation->value;
            }
            $callreturn->save();
            return response()->json([
                'status' => true ,
                'message' => 'Call return updated successfully'
                ],200);
        }
    }

    public function delet_callreturn(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:callreturns',
        ]);

        Callreturn::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Call return deleted successfully'
        ]);
    }

    public function get_callreturn_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callreturn = Callreturn::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $callreturn
        ]);
    }
}
