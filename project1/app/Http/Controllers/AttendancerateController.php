<?php

namespace App\Http\Controllers;

use App\Models\Attendancerate;
use App\Models\Evaluation;
use App\Models\Work;
use DateTime;
use Illuminate\Http\Request;

class AttendancerateController extends Controller
{
    public function add_attendance($employee_id, $time, TargetController $target){
        $date = new DateTime($time);
        $t = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($t);
        $interval->m++;
        $interval->y++;
        $attendance = Attendancerate::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
        if(empty($attendance)){
            $work = Work::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = ($work->houres/160)*100;
            $new = new Attendancerate();
            $new->employee_id = $employee_id;
            $new->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'message.work_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $new->points = 0;
                $target->put_target_point($employee_id, 0, 0, $interval->m, $interval->y);
            }else{
                $new->points = $evaluation->value;
                $target->put_target_point($employee_id, 0, $evaluation->value, $interval->m, $interval->y);
            }
            $new->month = $interval->m;
            $new->year = $interval->y;
            $new->save();
            return response()->json([
                'status' => true ,
                'message' => 'Attendance rate inroled successfully'
            ],201);
        }
        else{
            $work = Work::where([['employee_id', $employee_id],['month', $interval->m],['year', $interval->y]])->select()->first();
            $rate = ($work->houres/160)*100;
            $attendance->rate = $rate;
            $evaluation = Evaluation::where([['type' , 'message.work_rate'],['from', '<=', $rate],['to', '>=', $rate]])->select()->first();
            if(empty($evaluation)){
                $target->put_target_point($employee_id, $attendance->points, 0, $interval->m, $interval->y);
                $attendance->points = 0;
            }else{
                $old_points = $attendance->points;
                $target->put_target_point($employee_id, $old_points, $evaluation->value, $interval->m, $interval->y);
                $attendance->points = $evaluation->value;
            }
            $attendance->save();
            return response()->json([
                'status' => true ,
                'message' => 'Attendance rate Updated successfully'
            ],200);
        }
    }
}
