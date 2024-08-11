<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Target;
use App\Models\Targetpoint;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TargetpointController extends Controller
{
    public function add_targetpoint(Request $r , TargetController $t){
        $validated = $r->validate([
            'employee_id' => 'required|exists:employees,id',
            'points' => 'required|integer',
            'case' => 'required',
            'time' => 'required|date'
        ]);
        $e = Employee::where('id', $r->employee_id)->get();
        if(empty($e)){
            return response()->json([
                'status' => false,
                'message' => 'Employee is not exsist'
            ],404);
        }
        $date = new DateTime($r->time);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $targetpoint = Targetpoint::where([['employee_id' , $r->employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($targetpoint)){
            $new= new Targetpoint();
            $new->employee_id = $r->employee_id;
            $new->points = $r->points;
            $new->case = $r->case;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            $t->put_target_point($r->employee_id, null, $r->points, $interval->m, $interval->y);
            return response()->json([
                'status' => true ,
                'message' => 'target points added successfully'
            ],201);
        }else{
            $old_points = $targetpoint->points;
            $targetpoint->points = $r->points;
            $targetpoint->case = $r->case;
            $targetpoint->save();
            $t->put_target_point($r->employee_id, $old_points, $r->points, $interval->m, $interval->y);
            return response()->json([
                'status' => true ,
                'message' => 'Target points updated successfully'
            ],200);
        }
    }

    public function delet_targetpoint(Request $r, TargetController $t){
        $validated = $r->validate([
            'id' => 'required|exists:targetpoints',
        ]);
        $targetpoint = Targetpoint::where('id' , $r->id)->get();
        if(empty($targetpoint)){
            return response()->json([
                'status' => false ,
                'message' => 'Target points is not exists'
                ],404);
        }
        $t->delete_target_points($targetpoint->employee_id, $targetpoint->points, $targetpoint->month, $targetpoint->year);
        Targetpoint::where('id' , $r->id)->delete();
        return response()->json([
            'status' => true ,
            'message' => 'Target Points deleted successfully'
        ],200);
    }

    public function get_targetpoint_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $targetpoint = Targetpoint::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => true,
            'message' => "This is the target point matches with $r->date",
            'data' => $targetpoint
        ],200);
    }
}
