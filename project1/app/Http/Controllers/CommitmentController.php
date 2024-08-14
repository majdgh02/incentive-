<?php

namespace App\Http\Controllers;

use App\Models\Commitment;
use DateTime;
use Illuminate\Http\Request;

class CommitmentController extends Controller
{
    public function add_commitment($employee_id, $points, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $commitment = Commitment::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($commitment)){
            $new= new Commitment;
            $new->employee_id = $employee_id;
            $new->points = $points;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            $target->put_target_point($employee_id, null, $points,$interval->m, $interval->y);
            return response()->json([
                'status' => true ,
                'message' => 'Commitment inrolled successfully'
                ],201);
        }else{
            $target->put_target_point($employee_id, $commitment->points, $points,$interval->m, $interval->y);
            $commitment->points = $points;
            $commitment->save();
            return response()->json([
                'status' => true ,
                'message' => 'Commitment updated successfully'
                ],200);
        }
    }

    public function delet_commitment(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:commitments',
        ]);

        Commitment::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Commitment deleted successfully'
        ]);
    }

    public function get_commitment_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $commitment = Commitment::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $commitment
        ]);
    }
}
