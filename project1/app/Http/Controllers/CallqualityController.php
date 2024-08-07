<?php

namespace App\Http\Controllers;

use App\Models\Callquality;
use DateTime;
use Illuminate\Http\Request;

class CallqualityController extends Controller
{
    public function add_callquality(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:employees',
            'quality' => 'required|in:Excellent,Good,Middle',
            'time' => 'required|date'
        ]);
        $date = new DateTime($r->time);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callquality = Callquality::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($callquality)){
            $new= new Callquality;
            $new->employee_id = $r->id;
            $new->quality = $r->quality;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'call quality inrolled successfully'
                ]);
        }else{
            $callquality->quality = $r->quality;
            $callquality->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'call quality updated successfully'
                ]);
        }
    }

    public function delet_callquality(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:callqualities',
        ]);

        Callquality::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'call quality deleted successfully'
        ]);
    }

    public function get_callquality_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $callquality = Callquality::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $callquality
        ]);
    }
}
