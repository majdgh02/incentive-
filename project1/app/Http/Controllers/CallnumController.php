<?php

namespace App\Http\Controllers;

use App\Models\Callnum;
use App\Models\Employee;
use App\Models\Evaluation;
use DateTime;
use Illuminate\Http\Request;

class CallnumController extends Controller
{
    public function add_calnum ($employee_id, $num, $time){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $callnum = Callnum::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callnum)){
            $new= new Callnum;
            $new->employee_id = $employee_id;
            $new->num = $num;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => __('message.inroled_success', ['name' => __('message.callnum')])
                ],201);
        }else{

            $callnum->num = $num;
            $callnum->save();
            return response()->json([
                'status' => true ,
                'message' => __('message.update_success', ['name' => __('message.callnum')])
                ],200);
        }
    }

    public function delet_calnum(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:callnums',
        ]);

        Callnum::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => __('message.cn_delete_success')
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
            'message' => __('message.cn_get'),
            'data' => $callnum
        ]);
    }
}
