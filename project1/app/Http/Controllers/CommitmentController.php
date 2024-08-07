<?php

namespace App\Http\Controllers;

use App\Models\Commitment;
use DateTime;
use Illuminate\Http\Request;

class CommitmentController extends Controller
{
    public function add_commitment(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:employees',
            'points' => 'required|integer',
            'time' => 'required|date'
        ]);
        $date = new DateTime($r->time);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $commitment = Commitment::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($commitment)){
            $new= new Commitment;
            $new->employee_id = $r->id;
            $new->points = $r->points;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Commitment inrolled successfully'
                ]);
        }else{
            $commitment->points = $r->points;
            $commitment->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Commitment updated successfully'
                ]);
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
