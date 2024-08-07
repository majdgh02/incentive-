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
                'status' => 0,
                'message' => 'the username is not regestered'
            ]);
        }else{
            if($maneger->password == $r->password){
                return response()->json([
                    'status' => 1 ,
                    'message' => 'logged in successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 0 ,
                    'message' => 'password is not correct'
                ]);
            }
        }
    }
    //log out
    public function out(){
        $id=1;
        $maneger = Maneger::where('id', $id)->select()->first();
        return response()->json([
            'status' => 1 ,
            'message' => 'logged out successfully'
            ]);
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
                'status' => 0,
                'message' => 'the username is not regestered'
            ]);
        }else{
            if($maneger->password == $r->old){
                Maneger::where('id', $id)->update(['password' => $r->new]);
                return response()->json([
                    'status' => 1 ,
                    'message' => 'password updated successfully'
                ]);
            }
            else{
                return response()->json([
                    'status' => 0 ,
                    'message' => 'old password is not correct'
                ]);
            }
        }
    }

    // public function add_points(Request $r){
    //     $v = $r->validate([
    //         'id' => 'required|exsist:employees' ,
    //         'points' => 'required|ineger',
    //         'case' => 'required|longtext'
    //         ]);
    // }
}
