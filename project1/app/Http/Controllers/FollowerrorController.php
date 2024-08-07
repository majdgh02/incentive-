<?php

namespace App\Http\Controllers;

use App\Models\Followerror;
use DateTime;
use Illuminate\Http\Request;

class FollowerrorController extends Controller
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
        $follow = Followerror::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($follow)){
            $new= new Followerror;
            $new->employee_id = $r->id;
            $new->num = $r->num;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Follow errorrs number inrolled successfully'
                ]);
        }else{
            $follow->num = $r->num;
            $follow->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Follow errorrs number updated successfully'
                ]);
        }
    }

    public function delet_calnum(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:followerrors',
        ]);

        Followerror::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Follow errorrs number deleted successfully'
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
        $follow = Followerror::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $follow
        ]);
    }
}
