<?php

namespace App\Http\Controllers;

use App\Models\Callnum;
use App\Models\Evaluation;
use DateTime;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    ///////////////////////////rules////////////////////////
    public function add_evaluation(Request $r){
        $validated = $r->validate([
            'name' => 'required|unique:evaluations',
            'type' => 'required',
            'from' => 'required|numeric|between:0,100',
            'to' => 'required|numeric|between:0,100',
            'value' => 'required'
        ]);
        if($r->to<$r->from){
            return response()->json([
                'status' => false,
                'message' => '"To" must be bigger than "From"'
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
            $new->type = $r->type;
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

    public function update_evaluation(Request $r){
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
            $eva->type = $r->type;
            $eva->from = $r->from;
            $eva->to = $r->to;
            $eva->value = $r->value;
            $eva->save();
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

    public function delete_evaluation(Request $r){
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
            Evaluation::where('id', $r->id)->delete();
            return response()->json([
            'status' => true,
            'message' => 'Evaluation rule deleted successfully'
            ],200);
        }

    }

    public function get_evaluation(){
        $evaluations = Evaluation::select('id', 'name', 'type', 'from', 'to', 'value')->get();
        return response()->json([
            'status' => true,
            'message' => "This is evaluations rules",
            'data' => $evaluations
        ],200);
    }

    //////////////for employee////////////////////////
    public function add_evaluation_emp(Request $r, CallnumController $callnum,
    ProblemticController $problem, FollowerrorController $follow, AcceptancerateController $accept,
    CallqualityController $callqu, SuggestionController $sugg, CommitmentController $com,
    WorkController $work, TargetController $target, CallsrateController $calls, ErrorrateController $error){
        $json = $r->json()->all();
        $evaluations = $json['evaluations'];
        $checkwh = false;
        $checkcn = false;
        $checkar = false;
        $checkcq = false;
        foreach($evaluations as $e){
            if($e['type'] == __('message.Number_Work_hours'))
            $checkwh = true;
            if($e['type'] == __('message.Call_number'))
            $checkcn = true;
            if($e['type'] == __('message.Acceptance_rate')){
                if($e['num']<=100 || $e['num']>=0){
                    $checkar = true;
                }
            }
            if($e['type'] == __('message.Call_quality_rate')){
                if($e['num']>100 || $e['num']<0){
                    $checkcq = true;
                }
            }
        }
        if(!$checkwh)
            return response()->json([
                'status' => false,
                'message' => __('message.WH_not_in'),
            ],404);
        if(!$checkcn)
            return response()->json([
                'status' => false,
                'message' => __('message.CN_not_in'),
            ],404);
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
        foreach( $evaluations as $e){
            if($e['type'] == __('message.Number_Work_hours'))
                $n = $work->add_workHours($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Call_number'))
                $c = $callnum->add_calnum($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Problim_tickits_number'))
                $p = $problem->add_porbtic($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Follow_errors_number'))
                $f = $follow->add_follow($json['employee_id'], $e['num'], $json['date']);
            if($e['type'] == __('message.Acceptance_rate'))
                $a = $accept->add_acceptance($json['employee_id'], $e['num'], $json['date'] ,$target);
            if($e['type'] == __('message.Call_quality_rate'))
                $cq = $callqu->add_callquality($json['employee_id'], $e['num'], $json['date'],$target);
            if($e['type'] == __('message.Suggestion'))
                $s = $sugg->add_suggestion($json['employee_id'], $e['num'], $json['date'], $target);
            if($e['type'] == __('message.Commitment'))
                $comit = $com->add_commitment($json['employee_id'], $e['num'], $json['date'], $target);
        }
        $calls->add_callsrate($json['employee_id'], $json['date'],$target);
        $error->add_errorrate($json['employee_id'], $json['date'], $target);
        return response()->json([
            __('message.Number_Work_hours') => $n,
            __('message.Call_number') => $c,
            __('message.Problim_tickits_number') => $p,
            __('message.Follow_errors_number') => $f,
            __('message.Acceptance_rate') => $a,
            __('message.Call_quality_rate') => $cq,
            __('message.Suggestion') => $s,
            __('message.Commitment') => $comit
        ]);
    }

    // evaluation types
    public function evaluation_types(){
        $e = [__('message.Number_Work_hours'), __('message.Call_number'), __('message.Problim_tickits_number'), __('message.Follow_errors_number'), __('message.Acceptance_rate'), __('message.Call_quality_rate'), __('message.Suggestion'), __('message.Commitment')];
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
            __('message.follow_error_num'),
            __('message.accepternce_rate'),
            __('message.error_rate')
        ];
        return response()->json([
            'status' => true,
            'message' => "This is evaluation rules types",
            'data' => $e
        ],200);
    }
}
