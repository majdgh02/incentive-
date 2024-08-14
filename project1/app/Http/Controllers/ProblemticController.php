<?php

namespace App\Http\Controllers;

use App\Models\Problemtic;
use DateTime;
use Illuminate\Http\Request;

class ProblemticController extends Controller
{
    public function add_porbtic($employee_id, $num, $time){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $problem = Problemtic::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($problem)){
            $new= new Problemtic;
            $new->employee_id = $employee_id;
            $new->num = $num;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => 'problem tickits number inrolled successfully'
                ],201);
        }else{
            $problem->num = $num;
            $problem->save();
            return response()->json([
                'status' => true ,
                'message' => 'problem tickits number updated successfully'
                ],200);
        }
    }

    public function delet_probtic(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:problemtics',
        ]);

        Problemtic::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'callnum deleted successfully'
        ]);
    }

    public function get_probtic_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $problem = Problemtic::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $problem
        ]);
    }
}
