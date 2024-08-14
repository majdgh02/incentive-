<?php

namespace App\Http\Controllers;

use App\Models\Followerror;
use DateTime;
use Illuminate\Http\Request;

class FollowerrorController extends Controller
{
    public function add_follow($employee_id, $num, $time){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $follow = Followerror::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($follow)){
            $new= new Followerror;
            $new->employee_id = $employee_id;
            $new->num = $num;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => 'Follow errorrs number inrolled successfully'
                ],201);
        }else{
            $follow->num = $num;
            $follow->save();
            return response()->json([
                'status' => true ,
                'message' => 'Follow errorrs number updated successfully'
                ],200);
        }
    }

    public function delet_follow(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:followerrors',
        ]);

        Followerror::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Follow errorrs number deleted successfully'
        ]);
    }

    public function get_follow_month(Request $r){
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
