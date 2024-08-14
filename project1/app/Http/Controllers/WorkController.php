<?php

namespace App\Http\Controllers;

use App\Models\Work;
use DateTime;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function add_workHours($employee_id, $houres, $time){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $work = Work::where([['employee_id' , $employee_id],['month' , $interval->m] , ['year' , $interval->y]])->select()->first();
        if(empty($work)){
            $new= new Work();
            $new->employee_id = $employee_id;
            $new->houres = $houres;
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => 'Work houres inrolled successfully'
                ],201);
        }else{
            $work->houres = $houres;
            $work->save();
            return response()->json([
                'status' => true ,
                'message' => 'Work houres updated successfully'
                ],200);
        }
    }
}
