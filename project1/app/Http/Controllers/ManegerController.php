<?php

namespace App\Http\Controllers;

use App\Models\Maneger;
use Illuminate\Http\Request;

class ManegerController extends Controller
{
    // login
    public function login(Request $r){
        $v = $r->validate([
        'password' => 'required'
        ]);
        $id=1;
        $maneger = Maneger::where('id', $id)->select()->first();
        if(empty($maneger)){
            return response()->json([
                'status' => false,
                'message' => 'The username is not regestered'
            ],404);
        }else{
            if($maneger->password == $r->password){
                $maneger->status = true;
                $maneger->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Logged in successfully',
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Password is not correct'
                ],422);
            }
        }
    }
    //log out
    public function out(){
        $id=1;
        $maneger = Maneger::where('id', $id)->select()->first();
        $maneger->status = false;
        $maneger->save();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
            ],200);
    }
    // change password
    public function password_change(Request $r){
        $v = $r->validate([
            'old' => 'required' ,
            'new' => 'required'
            ]);
        $id = 1;
        $maneger = Maneger::where('id', $id)->select()->first();
        if(empty($maneger)){
            return response()->json([
                'status' => false,
                'message' => 'The username is not regestered'
            ],404);
        }else{
            if($maneger->password == $r->old){
                Maneger::where('id', $id)->update(['password' => $r->new]);
                return response()->json([
                    'status' => true,
                    'message' => 'Password updated successfully'
                ],200);
            }
            else{
                return response()->json([
                    'status' => false ,
                    'message' => 'Old password is not correct'
                ],422);
            }
        }
    }

//     public function end_session(){
//         $m = Maneger::where('id', 1)->select()->first();
//         $m->status = false;
//         $m->save();
//     }
}
