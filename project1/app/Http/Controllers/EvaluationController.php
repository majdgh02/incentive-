<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
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

    ////////////////for employee////////////////////////
    // public function add_evaluation_emp(Request $r){
    // }
}
