<?php

namespace App\Http\Controllers;

use App\Models\Acceptancerate;
use App\Models\Attendancerate;
use App\Models\Callnum;
use App\Models\Callquality;
use App\Models\Callreturn;
use App\Models\Callsrate;
use App\Models\Employee;
use App\Models\Errorrate;
use App\Models\Evaluation;
use App\Models\Followerror;
use App\Models\Problemtic;
use App\Models\Target;
use App\Models\Work;
use DateTime;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    ///////////////////////////rules////////////////////////
    public function add_evaluation(Request $r, TargetController $target){
        $validated = $r->validate([
            'name' => 'required|unique:evaluations',
            'type' => 'required',
            'from' => 'required|numeric|between:0,100',
            'to' => 'required|numeric|between:0,100',
            'value' => 'required'
        ]);
        $e = [
            __('message.work_rate'),
            __('message.Call_rate'),
            __('message.Call_quality'),
            __('message.prob_tic_num'),
            __('message.accepternce_rate'),
            __('message.error_rate'),
            __('message.call_return')
        ];
        if($r->to<$r->from){
            return response()->json([
                'status' => false,
                'message' => '"To" must be bigger than "From"'
            ],422);
        }
        if(!in_array($r->type, $e)){
            return response()->json([
                'status' => false,
                'message' => 'The type must be one of these types',
                'data' => $e
            ],422);
        }
        $evaluations = Evaluation::where('type' , $r->type)->get();
        $check = true;
        foreach($evaluations as $e){
            if($r->from>=$e->from && $r->to<=$e->to){
                $check = false;
            }elseif($r->from<=$e->from && $r->to>=$e->to){
                $check = false;
            }elseif($r->from>=$e->from && $r->to>=$e->to && $r->from<=$e->to){
                $check = false;
            }elseif($r->from<=$e->from && $r->to<=$e->to && $r->to>=$e->from){
                $check = false;
            }
        }
        if($check){
            $new = new Evaluation;
            $new->name = $r->name;
            if($r->type == __('message.Call_rate')){
                $new->type = 'message.Call_rate';
                $callsrate = Callsrate::all();
                if(!empty($callsrate))
                    foreach($callsrate as $c){
                        if($c->rate >= $r->from && $c->rate <= $r->to){
                            $c->points = $r->value;
                            $target->put_target_point($c->employee_id, 0, $r->value, $c->month, $c->year);
                            $c->save();
                        }
                    }
            }else{
                if($r->type == __('message.Call_quality')){
                    $new->type = 'message.Call_quality';
                    $callqu = Callquality::all();
                    if(!empty($callqu))
                        foreach($callqu as $c){
                            if($c->quality >= $r->from && $c->quality <= $r->to){
                                $c->points = $r->value;
                                $target->put_target_point($c->employee_id, 0, $r->value, $c->month, $c->year);
                                $c->save();
                            }
                        }
                }else{
                    if($r->type == __('message.work_rate')){
                        $new->type = 'message.work_rate';
                        $work = Attendancerate::all();
                        if(!empty($work))
                            foreach($work as $w){
                                if($w->rate >= $r->from && $w->rate <= $r->to){
                                    $w->points = $r->value;
                                    $target->put_target_point($w->employee_id, 0, $r->value, $w->month, $w->year);
                                    $w->save();
                                }
                            }
                    }else{
                        if($r->type == __('message.prob_tic_num')){
                            $new->type = 'message.prob_tic_num';
                            $problem = Problemtic::all();
                            if(!empty($problem))
                                foreach($problem as $p){
                                    if($p->num >= $r->from && $p->num <= $r->to){
                                        $p->points = $r->value;
                                        $target->put_target_point($p->employee_id, 0, $r->value, $p->month, $p->year);
                                        $p->save();
                                    }
                                }
                        }else{
                            if($r->type == __('message.accepternce_rate')){
                                $new->type = 'message.accepternce_rate';
                                $accept = Acceptancerate::all();
                                if(!empty($accept))
                                    foreach($accept as $a){
                                        if($a->rate >= $r->from && $a->rate <= $r->to){
                                            $a->points = $r->value;
                                            $target->put_target_point($a->employee_id, 0, $r->value, $a->month, $a->year);
                                            $a->save();
                                        }
                                    }
                            }else{
                                if($r->type == __('message.error_rate')){
                                    $new->type = 'message.error_rate';
                                    $error = Errorrate::all();
                                    if(!empty($error))
                                        foreach($error as $e){
                                            if($e->rate >= $r->from && $e->rate <= $r->to){
                                                $e->points = $r->value;
                                                $target->put_target_point($e->employee_id, 0, $r->value, $e->month, $e->year);
                                                $e->save();
                                            }
                                        }
                                }else{
                                    if($r->type == __('message.call_return')){
                                        $new->type = 'message.call_return';
                                        $callreturn = Callreturn::all();
                                        if(!empty($callreturn))
                                            foreach($callreturn as $cre){
                                                if($cre->rate >= $r->from && $cre->rate <= $r->to){
                                                    $cre->points = $r->value;
                                                    $target->put_target_point($cre->employee_id, 0, $r->value, $cre->month, $cre->year);
                                                    $cre->save();
                                                }
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $new->from = $r->from;
            $new->to = $r->to;
            $new->value = $r->value;
            $new->save();
            return response()->json([
                'status' => true,
                'message' => 'Evaluation rule inrolled successfully'
            ],201);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'the range you have inrolled is not correct'
            ],422);
        }
    }

    public function update_evaluation(Request $r, TargetController $target){
        $validated = $r->validate([
            'id' => 'required|exists:evaluations',
            'name' => 'required',
            'type' => 'required',
            'from' => 'required|between:0,100',
            'to' => 'required|between:0,100|',
            'value' => 'required'
        ]);
        if($r->to<$r->from){
            return response()->json([
                'status' => false,
                'message' => 'to must be bigger than from'
            ],422);
        }
        $evaluations = Evaluation::where('type' , $r->type)->wherenot('id' , $r->id)->get();
        $check = true;
        foreach($evaluations as $e){
            if($r->from>=$e->from && $r->to<=$e->to){
                $check = false;
            }elseif($r->from<=$e->from && $r->to>=$e->to){
                $check = false;
            }elseif($r->from>=$e->from && $r->to>=$e->to && $r->from<=$e->to){
                $check = false;
            }elseif($r->from<=$e->from && $r->to<=$e->to && $r->to>=$e->from){
                $check = false;
            }
        }
        if($check){
            $eva = Evaluation::where('id', $r->id)->select()->first();
            if($eva->type == 'message.Call_rate'){
                $callsrate = Callsrate::all();
                if(!empty($callsrate))
                    foreach($callsrate as $c){
                        if($c->rate >= $eva->from && $c->rate <= $eva->to){
                            $target->delete_target_points($c->employee_id, $c->point, $c->month, $c->year);
                            $c->points = 0;
                            $c->save();
                        }
                    }
            }else{
                if($eva->type == 'message.Call_quality'){
                    $callqu = Callquality::all();
                    if(!empty($callqu))
                        foreach($callqu as $c){
                            if($c->quality >= $eva->from && $c->quality <= $eva->to){
                                $target->delete_target_points($c->employee_id, $c->point, $c->month, $c->year);
                                $c->points = 0;
                                $c->save();
                            }
                        }
                }else{
                    if($eva->type == 'message.work_rate'){
                        $work = Attendancerate::all();
                        if(!empty($work))
                            foreach($work as $w){
                                if($w->rate >= $eva->from && $w->rate <= $eva->to){
                                $target->delete_target_points($w->employee_id, $w->point, $w->month, $w->year);
                                    $w->points = 0;
                                    $w->save();
                                }
                            }
                    }else{
                        if($eva->type == 'message.prob_tic_num'){
                            $problem = Problemtic::all();
                            if(!empty($problem))
                                foreach($problem as $p){
                                    if($p->num >= $eva->from && $p->num <= $eva->to){
                                        $target->delete_target_points($p->employee_id, $p->points, $p->month, $p->year);
                                        $p->points = 0;
                                        $p->save();

                                    }
                                }
                        }else{
                            if($eva->type == 'message.accepternce_rate'){
                                $accept = Acceptancerate::all();
                                if(!empty($accept))
                                    foreach($accept as $a){
                                        if($a->rate >= $eva->from && $a->rate <= $eva->to){
                                            $target->delete_target_points($a->employee_id, $a->points, $a->month, $a->year);
                                            $a->points = 0;
                                            $a->save();
                                        }
                                    }
                            }else{
                                if($eva->type == 'message.error_rate'){
                                    $error = Errorrate::all();
                                    if(!empty($error))
                                        foreach($error as $er){
                                            if($er->rate >= $eva->from && $er->rate <= $eva->to){
                                                $target->delete_target_points($er->employee_id, $er->point, $er->month, $er->year);
                                                $er->points = 0;
                                                $er->save();
                                            }
                                        }
                                }else{
                                    if($eva->type == 'message.call_return'){
                                        $callreturn = Callreturn::all();
                                        if(!empty($callreturn))
                                            foreach($callreturn as $cre){
                                                if($cre->rate >= $eva->from && $cre->rate <= $eva->to){
                                                    $target->delete_target_points($cre->employee_id, $cre->point, $cre->month, $cre->year);
                                                    $cre->points = 0;
                                                    $cre->save();
                                                }
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $eva->name = $r->name;
            $eva->type = $r->type;
            $eva->from = $r->from;
            $eva->to = $r->to;
            $eva->value = $r->value;
            $eva->save();
            if($r->type == __('message.Call_rate')){
                $callsrate = Callsrate::all();
                if(!empty($callsrate))
                    foreach($callsrate as $c){
                        if($c->rate >= $r->from && $c->rate <= $r->to){
                            $c->points = $r->value;
                            $target->put_target_point($c->employee_id, 0, $r->value, $c->month, $c->year);
                            $c->save();
                        }
                    }
            }else{
                if($r->type == __('message.Call_quality')){
                    $callqu = Callquality::all();
                    if(!empty($callqu))
                        foreach($callqu as $c){
                            if($c->quality >= $r->from && $c->quality <= $r->to){
                                $c->points = $r->value;
                                $target->put_target_point($c->employee_id, 0, $r->value, $c->month, $c->year);
                                $c->save();
                            }
                        }
                }else{
                    if($r->type == __('message.work_rate')){
                        $work = Attendancerate::all();
                        if(!empty($work))
                            foreach($work as $w){
                                if($w->rate >= $r->from && $w->rate <= $r->to){
                                    $w->points = $r->value;
                                    $target->put_target_point($w->employee_id, 0, $r->value, $w->month, $w->year);
                                    $w->save();
                                }
                            }
                    }else{
                        if($r->type == __('message.prob_tic_num')){
                            $problem = Problemtic::all();
                            if(!empty($problem))
                                foreach($problem as $p){
                                    if($p->num >= $r->from && $p->num <= $r->to){
                                        $p->points = $r->value;
                                        $target->put_target_point($p->employee_id, 0, $r->value, $p->month, $p->year);
                                        $p->save();
                                    }
                                }
                        }else{
                            if($r->type == __('message.accepternce_rate')){
                                $accept = Acceptancerate::all();
                                if(!empty($accept))
                                    foreach($accept as $a){
                                        if($a->rate >= $r->from && $a->rate <= $r->to){
                                            $a->points = $r->value;
                                            $target->put_target_point($a->employee_id, 0, $r->value, $a->month, $a->year);
                                            $a->save();
                                        }
                                    }
                            }else{
                                if($r->type == __('message.error_rate')){
                                    $error = Errorrate::all();
                                    if(!empty($error))
                                        foreach($error as $er){
                                            if($er->rate >= $r->from && $er->rate <= $r->to){
                                                $er->points = $r->value;
                                                $target->put_target_point($er->employee_id, 0, $r->value, $er->month, $er->year);
                                                $er->save();
                                            }
                                        }
                                }else{
                                    if($r->type == __('message.call_return')){
                                        $callreturn = Callreturn::all();
                                        if(!empty($callreturn))
                                            foreach($callreturn as $cre){
                                                if($cre->rate >= $r->from && $cre->rate <= $r->to){
                                                    $cre->points = $r->value;
                                                    $target->put_target_point($cre->employee_id, 0, $r->value, $cre->month, $cre->year);
                                                    $cre->save();
                                                }
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Evaluation rule updated successfully'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'the range you have inrolled is not correct'
            ],422);
        }
    }

    public function delete_evaluation(Request $r, TargetController $target){
        $validated = $r->validate([
            'id' => 'required'
        ]);
        $e = Evaluation::where('id' , $r->id)->get();
        if(empty($e)){
            return response()->json([
                'status' => false,
                'message' => 'Evaluation rule is not exsist'
            ],404);
        }else{
            $e = Evaluation::where('id', $r->id)->select()->first();
            if($e->type == 'message.Call_rate'){
                $callsrate = Callsrate::all();
                if(!empty($callsrate))
                    foreach($callsrate as $c){
                        if($c->rate >= $e->from && $c->rate <= $e->to){
                            $target->delete_target_points($c->employee_id, $c->point, $c->month, $c->year);
                            $c->points = 0;
                            $c->save();
                        }
                    }
            }else{
                if($e->type == 'message.Call_quality'){
                    $callqu = Callquality::all();
                    if(!empty($callqu))
                        foreach($callqu as $c){
                            if($c->quality >= $e->from && $c->quality <= $e->to){
                                $target->delete_target_points($c->employee_id, $c->point, $c->month, $c->year);
                                $c->points = 0;
                                $c->save();
                            }
                        }
                }else{
                    if($e->type == 'message.work_rate'){
                        $work = Attendancerate::all();
                        if(!empty($work))
                            foreach($work as $w){
                                if($w->rate >= $e->from && $w->rate <= $e->to){
                                $target->delete_target_points($w->employee_id, $w->point, $w->month, $w->year);
                                    $w->points = 0;
                                    $w->save();
                                }
                            }
                    }else{
                        if($e->type == 'message.prob_tic_num'){
                            $problem = Problemtic::all();
                            if(!empty($problem))
                                foreach($problem as $p){
                                    if($p->num >= $e->from && $p->num <= $e->to){
                                        $target->delete_target_points($p->employee_id, $p->points, $p->month, $p->year);
                                        $p->points = 0;
                                        $p->save();
                                    }
                                }
                        }else{
                            if($e->type == 'message.accepternce_rate'){
                                $accept = Acceptancerate::all();
                                if(!empty($accept))
                                    foreach($accept as $a){
                                        if($a->rate >= $e->from && $a->rate <= $e->to){
                                            $target->delete_target_points($a->employee_id, $a->points, $a->month, $a->year);
                                            $a->points = 0;
                                            $a->save();
                                        }
                                    }
                            }else{
                                if($e->type == 'message.error_rate'){
                                    $error = Errorrate::all();
                                    if(!empty($error))
                                        foreach($error as $e){
                                            if($e->rate >= $e->from && $e->rate <= $e->to){
                                                $target->delete_target_points($e->employee_id, $e->point, $e->month, $e->year);
                                                $e->points = 0;
                                                $e->save();
                                            }
                                        }
                                }else{
                                    if($e->type == 'message.call_return'){
                                        $callreturn = Callreturn::all();
                                        if(!empty($callreturn))
                                            foreach($callreturn as $cre){
                                                if($cre->rate >= $e->from && $cre->rate <= $e->to){
                                                    $target->delete_target_points($cre->employee_id, $cre->point, $cre->month, $cre->year);
                                                    $cre->points = 0;
                                                    $cre->save();
                                                }
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            Evaluation::where('id', $r->id)->delete();
            return response()->json([
            'status' => true,
            'message' => 'Evaluation rule deleted successfully'
            ],200);
        }

    }

    public function get_evaluation(){
        $evaluations = Evaluation::select('id', 'name', 'type', 'from', 'to', 'value')->get();
        foreach($evaluations as $e)
        $e->type = __($e->type);
        return response()->json([
            'status' => true,
            'message' => "This is evaluations rules",
            'data' => $evaluations
        ],200);
    }

    //////////////for employee////////////////////////
    public function add_evaluation_emp(Request $r, CallnumController $callnum,
    ProblemticController $problem, FollowerrorController $follow, AcceptancerateController $accept,
    CallqualityController $callqu,WorkController $work, TargetController $target, CallsrateController $calls,
    ErrorrateController $error, CallreturnController $callre, AttendancerateController $attendance){
        $json = $r->json()->all();
        $evaluations = $json['evaluations'];
        $ea = [__('message.Number_Work_hours'), __('message.Call_number'), __('message.prob_tic_num'), __('message.Follow_errors_number'), __('message.Acceptance_rate'), __('message.Call_quality_rate'), __('message.call_return')];
        foreach($evaluations as $evaluation){
            if(!in_array($evaluation['type'],$ea)){
                return response()->json([
                    'status' => false,
                    'message' => "The type must be one of these types",
                    'data' => $ea
                ],422);
            }
        }
        $checkwh = false;
        $checkcn = false;
        $checkfe = false;
        $checkar = false;
        $checkcq = false;
        $checkcr = false;
        foreach($evaluations as $e){
            if($e['type'] == __('message.Number_Work_hours'))
            $checkwh = true;
            if($e['type'] == __('message.Call_number'))
            $checkcn = true;
            if($e['type'] == __('message.Follow_errors_number'))
            $checkfe= true;
            if($e['type'] == __('message.Acceptance_rate')){
                if($e['num'] <= 100 && $e['num'] >= 0){
                    $checkar = true;
                }
            }
            if($e['type'] == __('message.Call_quality_rate')){
                if($e['num'] <= 100 && $e['num'] >= 0){
                    $checkcq = true;
                }
            }
            if($e['type'] == __('message.call_return')){
                if($e['num'] <= 100 && $e['num'] >= 0)
                    $checkcr = true;
            }
        }
        if ($checkfe){
            if(!$checkcn)
            return response()->json([
                'status' => false,
                'message' => __('message.CN_not_in'),
            ],404);
        }
        if($checkcn){
            if(!$checkwh)
                return response()->json([
                    'status' => false,
                    'message' => __('message.WH_not_in'),
                ],404);
        }
        if(!$checkar)
            return response()->json([
                'status' => false,
                'message' => __('message.ar_not_in'),
            ],404);
        if(!$checkcq)
            return response()->json([
                'status' => false,
                'message' => __('message.cq_not_in'),
            ],404);
            if(!$checkcr)
                return response()->json([
                    'status' => false,
                    'message' => __('message.cr_not_in'),
                ],404);
        foreach( $evaluations as $e){
            if($e['type'] == __('message.Number_Work_hours'))
                $n = $work->add_workHours($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Call_number'))
                $c = $callnum->add_calnum($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.prob_tic_num'))
                $p = $problem->add_porbtic($json['employee_id'], $e['num'], $json['date'], $target);
            if($e['type'] == __('message.Follow_errors_number'))
                $f = $follow->add_follow($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Acceptance_rate'))
                $a = $accept->add_acceptance($json['employee_id'], $e['num'], $json['date'] ,$target);
            if($e['type'] == __('message.Call_quality_rate'))
                $cq = $callqu->add_callquality($json['employee_id'], $e['num'], $json['date'],$target);
            if($e['type'] == __('message.call_return'))
                $cr = $callre->add_callreturn($json['employee_id'], $e['num'], $json['date'],$target);
        }
        if($checkwh)
            $attendance->add_attendance($json['employee_id'], $json['date'],$target);
        if($checkwh && $checkcn)
            $calls->add_callsrate($json['employee_id'], $json['date'],$target);
        if($checkcn && $checkfe)
            $error->add_errorrate($json['employee_id'], $json['date'], $target);
        return response()->json([
            __('message.Number_Work_hours') => $n,
            __('message.Call_number') => $c,
            __('message.prob_tic_num') => $p,
            __('message.Follow_errors_number') => $f,
            __('message.Acceptance_rate') => $a,
            __('message.Call_quality_rate') => $cq,
            __('message.call_return') => $cr,
        ]);
    }

    public function get_evaluation_form(Request $r){
        $validated = $r->validate([
            'id' => 'required|exists:employees',
            'date' => 'required|date'
        ]);
        $date = new DateTime($r->date);
        $time = new DateTime('0001-01-01 00:00:00');
        $interval = $date->diff($time);
        $interval->m++;
        $interval->y++;
        $array = [];
        $e = Employee::where('id', $r->id)->select('name')->first();
        $array = Arr::add($array, __('message.e_data'), $e);
        $w = Work::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('houres', 'points')->first();
        $array = Arr::add($array, __('message.Number_Work_hours'), $w);
        $a = Attendancerate::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('rate', 'points')->first();
        $array = Arr::add($array, __('message.work_rate'), $a);
        $cn = Callnum::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('num', 'points')->first();
        $array = Arr::add($array, __('message.Call_number'), $cn);
        $cr = Callsrate::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('rate', 'points')->first();
        $array = Arr::add($array, __('message.Call_rate'), $cr);
        $f = Followerror::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('num', 'points')->first();
        $array = Arr::add($array, __('message.Follow_errors_number'), $f);
        $er = Errorrate::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('rate', 'points')->first();
        $array = Arr::add($array, __('message.error_rate'), $er);
        $cq = Callquality::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('quality', 'points')->first();
        $array = Arr::add($array, __('message.Call_quality'), $cq);
        $p = Problemtic::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('num', 'points')->first();
        $array = Arr::add($array, __('message.prob_tic_num'), $p);
        $ac = Acceptancerate::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('rate', 'points')->first();
        $array = Arr::add($array, __('message.Acceptance_rate'), $ac);
        $cre = Callreturn::where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('rate', 'points')->first();
        $array = Arr::add($array, __('message.call_return'), $cre);
        $t = DB::table('employee_target')->where([['employee_id', $r->id],['month', $interval->m],['year', $interval->y]])->select('value')->first();
        $p = Target::where('id', 1)->select('price')->first();
        $tp = $t->value * $p->price;
        $array = Arr::add($array, __('message.target'), $t);
        $array = Arr::add($array, __('message.target_p'), $tp);
        return response()->json([
            'status' => true,
            'message' => "This is evaluation form",
            'data' => $array
        ],200);
    }

    // evaluation types
    public function evaluation_types(){
        $e = [__('message.Number_Work_hours'), __('message.Call_number'), __('message.prob_tic_num'), __('message.Follow_errors_number'), __('message.Acceptance_rate'), __('message.Call_quality_rate'), __('message.call_return')];
        return response()->json([
            'status' => true,
            'message' => "This is evaluation types",
            'data' => $e
        ],200);
    }

    // evaluation rules type
    public function evaluation_rules_types(){
        $e = [
            __('message.work_rate'),
            __('message.Call_rate'),
            __('message.Call_quality'),
            __('message.prob_tic_num'),
            __('message.accepternce_rate'),
            __('message.error_rate'),
            __('message.call_return')
        ];
        return response()->json([
            'status' => true,
            'message' => "This is evaluation rules types",
            'data' => $e
        ],200);
    }

    //evaluation championes
    public function evaluation_champions(){
        $array = [];
        $maxas = Attendancerate::max('rate');
        $as = Attendancerate::where('rate', $maxas)->get();
        $arraya = [];
        $i = 1;
        foreach($as as $a){
            $e= Employee::where('id', $a->employee_id)->select('id', 'name')->first();
            $arraya =Arr::add($arraya, __('message.employee', ['num' => $i]), [$e, $a->rate]);
            $i++;}
        $array = Arr::add($array, __('message.work_rate'), $arraya);
        $maxcs = Callsrate::max('rate');
        $cs = Callsrate::where('rate', $maxcs)->get();
        $arrayc = [];
        $i = 1;
        foreach($cs as $c){
            $e= Employee::where('id', $c->employee_id)->select('id', 'name')->first();
            $arrayc =Arr::add($arrayc, __('message.employee', ['num' => $i]), [$e, $c->rate]);
            $i++;}
        $array =Arr::add($array, __('message.Call_rate'), $arrayc);
        $maxcqs = Callquality::max('quality');
        $cqs = Callquality::where('quality', $maxcqs)->get();
        $arraycq = [];
        $i = 1;
        foreach($cqs as $cq){
            $e = Employee::where('id', $cq->employee_id)->select('id', 'name')->first();
            $arraycq = Arr::add($arraycq, __('message.employee', ['num' => $i]), [$e, $cq->quality]);
            $i++;}
        $array =Arr::add($array, __('message.Call_quality'), $arraycq);
        $minp = Problemtic::min('num');
        $ps = Problemtic::where('num', $minp)->get();
        $arrayp =[];
        $i=1;
        foreach($ps as $p){
            $e = Employee::where('id', $p->employee_id)->select('id', 'name')->first();
            $arrayp =Arr::add($arrayp, __('message.employee', ['num' => $i]), [$e, $p->num]);
            $i++;}
        $array = Arr::add($array, __('message.prob_tic_num'), $arrayp);
        $maxa = Acceptancerate::max('rate');
        $acs = Acceptancerate::where('rate', $maxa)->get();
        $arrayac =[];
        $i=1;
        foreach($acs as $ac){
            $e = Employee::where('id', $ac->employee_id)->select('id', 'name')->first();
            $arrayac =Arr::add($arrayac, __('message.employee', ['num' => $i]), [$e, $ac->rate]);
            $i++;}
        $array = Arr::add($array, __('message.accepternce_rate'), $arrayac);
        $mine = Errorrate::min('rate');
        $ers = Errorrate::where('rate', $mine)->get();
        $arrayer =[];
        $i=1;
        foreach($ers as $er){
            $e = Employee::where('id', $er->employee_id)->select('id', 'name')->first();
            $arrayer =Arr::add($arrayer, __('message.employee', ['num' => $i]), [$e, $er->rate]);
            $i++;}
        $array = Arr::add($array, __('message.error_rate'), $arrayer);
        $maxcr = Callreturn::max('rate');
        $crs = Callreturn::where('rate', $maxcr)->get();
        $arraycr =[];
        $i=1;
        foreach($crs as $cr){
            $e = Employee::where('id', $cr->employee_id)->select('id', 'name')->first();
            $arraycr =Arr::add($arraycr, __('message.employee', ['num' => $i]), [$e, $cr->rate]);
            $i++;}
        $array = Arr::add($array, __('message.call_return'), $arraycr);
        return response()->json([$array,]);
    }
}
