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
                'status' => 1 ,
                'message' => 'target value created'
            ]);
        }
        else{
            $t->price = $r->price;
            $t->save();
            return response()->json([
                'status' => 1 ,
                'message' => 'target value Updated'
            ]);
        }
    }

    public function put_target_point($id, $old_points, $new_points, $month, $year){
        $e = DB::table('employee_target')->where([['employee_id' , $id],['month' , $month] , ['year' , $year]])->select()->first();
        if(empty($e)){
            DB::table('employee_target')->insert([
                'employee_id' => $id,
                'target_id' => 1,
                'value' => $new_points,
                'month' => $month,
                'year' => $year
            ]);
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

}
