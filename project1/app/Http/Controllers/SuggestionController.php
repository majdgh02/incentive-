<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Suggestion;
use DateTime;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function add_suggestion(Request $r){
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
        $suggistion = Suggestion::where([['employee_id' , $r->id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($suggistion)){
            $new= new Suggestion();
            $new->employee_id = $r->id;
            $new->points = $r->points;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Suggestion inrolled successfully'
                ]);
        }else{
            $suggistion->points = $r->points;
            $suggistion->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'Suggestion updated successfully'
                ]);
        }
    }

    public function delet_suggestion(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:suggestions',
        ]);

        Suggestion::where('id' , $r->id)->delete();
        return response()->json([
            'status' => 1 ,
            'message' => 'Suggestion deleted successfully'
        ]);
    }

    public function get_suggestion_month(Request $r){
        $validated = $r->validate([
            'date' => 'required|date',
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $suggistion = Suggestion::where([['month' , $interval->m] , ['year' , $interval->y]])->get();
        return response()->json([
            'status' => 1,
            'message' => $suggistion
        ]);
    }
}
