<?php

namespace App\Http\Controllers;

use App\Models\Callreturn;
use DateTime;
use Illuminate\Http\Request;

class CallreturnController extends Controller
{
    public function add_callreturn(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:employees',
            'rate' => 'required|integer|between:0,100',
            'time' => 'required|date'
        ]);
        $date = new DateTime($r->time);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callreturn = Callreturn::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callreturn)){
            $new= new Callreturn;
            $new->employee_id = $r->id;
            $new->rate = $r->rate;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Call return inrolled successfully'
                ]);
        }else{
            $callreturn->rate = $r->rate;
            $callreturn->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Call return updated successfully'
                ]);
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
