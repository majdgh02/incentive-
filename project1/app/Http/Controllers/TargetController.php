<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    // add value to target point
    public function target_value(Request $r){
        $v = $r->validate([
            'price' => 'required'
            ]);
        $t = Target::select()->first();
        if(empty($t)){
            $target = new Target;
            $target->price = $r->price;
            $target->save();
            return response()->json([
                'status' => true,
                'message' => 'Target value created'
            ],201);
        }
        else{
            $t->price = $r->price;
            $t->save();
            return response()->json([
                'status' => true,
                'message' => 'Target value Updated'
            ],200);
        }
    }

    public function get_target_value(){
        $t = Target::where('id', 1)->select('price')->get();
        if(empty($t)){
            return response()->json([
                'status' => false,
                'message' => 'You did not enter the target point price' ,
                'data' => $t
            ],404);
        }else{
            return response()->json([
                'status' => true,
                'message' => 'This is the price of one point of the Target' ,
                'data' => $t
            ],200);
        }
    }
    ////// Helperes functions //////////////
    public function put_target_point($id, $old_points, $new_points, $month, $year){
        $e = DB::table('employee_target')->where([['employee_id' , $id],['month' , $month] , ['year' , $year]])->select()->first();
        if(empty($e)){
            DB::table('employee_target')->insert([
                'employee_id' => $id,
                'value' => $new_points,
                'month' => $month,
                'year' => $year
            ]);
            // return $new_points;
        }else{
            $new = $e->value - $old_points + $new_points;
            DB::table('employee_target')->where([['employee_id' , $id],['month' , $month] , ['year' , $year]])->update(['value' => $new]);
        }
    }

    public function delete_target_points($id, $old_points, $month, $year){
        $e = DB::table('employee_target')->where([['employee_id' , $id],['month' , $month] , ['year' , $year]])->select()->first();
        $new = $e->value - $old_points;
        DB::table('employee_target')->where([['employee_id' , $id],['month' , $month] , ['year' , $year]])->update(['value' => $new]);
    }
    /////////////////
}
