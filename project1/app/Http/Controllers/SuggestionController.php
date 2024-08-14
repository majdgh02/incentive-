<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Suggestion;
use DateTime;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function add_suggestion($employee_id, $points, $time,TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $suggistion = Suggestion::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($suggistion)){
            $new= new Suggestion();
            $new->employee_id = $employee_id;
            $new->points = $points;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            $target->put_target_point($employee_id, null, $points, $interval->m, $interval->y);
            return response()->json([
                'status' => true ,
                'message' => 'Suggestion inrolled successfully'
                ],201);
        }else{
            $target->put_target_point($employee_id, $suggistion->points, $points, $interval->m, $interval->y);
            $suggistion->points = $points;
            $suggistion->save();
            return response()->json([
                'status' => true ,
                'message' => 'Suggestion updated successfully'
                ],200);
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
