<?php

namespace App\Http\Controllers;

use App\Models\Acceptancerate;
use DateTime;
use Illuminate\Http\Request;

class AcceptancerateController extends Controller
{
    public function add_acceptance(Request $r){
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
        $acceptance = Acceptancerate::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($acceptance)){
            $new= new Acceptancerate;
            $new->employee_id = $r->id;
            $new->rate = $r->rate;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Acceptance rate inrolled successfully'
                ]);
        }else{
            $acceptance->rate = $r->rate;
            $acceptance->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Acceptance rate updated successfully'
                ]);
        }
    }

    public function delet_acceptance(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:acceptancerates',
        ]);

        Acceptancerate::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Acceptance rate deleted successfully'
        ]);
    }

    public function get_acceptance_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $acceptance = Acceptancerate::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $acceptance
        ]);
    }
}
