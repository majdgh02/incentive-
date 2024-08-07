<?php

namespace App\Http\Controllers;

use App\Models\Callnum;
use App\Models\Employee;
use DateTime;
use Illuminate\Http\Request;

class CallnumController extends Controller
{
    public function add_calnum(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:employees',
            'num' => 'required',
            'time' => 'required|date'
        ]);
        $date = new DateTime($r->time);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callnum = Callnum::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callnum)){
            $new= new Callnum;
            $new->employee_id = $r->id;
            $new->num = $r->num;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'callnumber inrolled successfully'
                ]);
        }else{
            $callnum->num = $r->num;
            $callnum->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'callnumber updated successfully'
                ]);
        }
    }

    public function delet_calnum(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:callnums',
        ]);

        Callnum::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'callnum deleted successfully'
        ]);
    }

    public function get_callnum_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callnum = Callnum::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $callnum
        ]);
    }
}
