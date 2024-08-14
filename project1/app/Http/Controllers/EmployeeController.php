<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class EmployeeController extends Controller
{
    // create new employee
    public function create(Request $r){
        $validated = $r->validate([
            'name' => 'required',
            'id_num' => 'required|unique:employees',
            'address' => 'required',
            'home_num' => 'required',
            'phone_num' => 'required',
            'birth' => 'required|date',
            'join' => 'required|date',
            'edu' => 'required' ,
            'section' => 'required',
            'username' => 'required|unique:employees',
            'password' => 'required'
        ]);

        $employee = new Employee;
        $employee->name = $r->name;
        $employee->id_num = $r->id_num;
        $employee->address = $r->address;
        $employee->home_num = $r->home_num;
        $employee->phone_num = $r->phone_num;
        $employee->birth = $r->birth;
        $employee->join = $r->join;
        $employee->edu = $r->edu;
        $employee->section = $r->section;
        $employee->username = $r->username;
        $employee->password = $r->password;
        $employee->status = true;
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => __('message.create_employee')
        ],201);
    }
    //update employee details
    public function update(Request $r){
        $validated = $r->validate([
            'id' => 'required',
            'name' => 'required',
            'id_num' => 'required',
            'address' => 'required',
            'home_num' => 'required',
            'phone_num' => 'required',
            'birth' => 'required|date',
            'join' => 'required|date',
            'edu' => 'required' ,
            'section' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);
        $employee=Employee::where('id', $r->id)->select()->first();
        if(empty($employee)){
            return response()->json([
                'status' => false,
                'message' => 'Employee is not exsist'
            ],404);
        }else{
            Employee::where('id', $r->id)->update([
            'name' => $r->name ,
            'id_num' => $r->id_num,
            'address' => $r->address,
            'home_num' => $r->home_num ,
            'phone_num' => $r->phone_num ,
            'birth' => $r->birth ,
            'join' => $r->join ,
            'edu' => $r->edu ,
            'section' => $r->section ,
            'join' => $r->join ,
            'username' => $r->username,
            'password' => $r->password,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully'
            ],200);
        }

    }
    // update status
    public function update_status(Request $r){
        $validated = $r->validate([
            'id' => 'required' ,
            'status' => 'required'
        ]);
        if(empty(Employee::where('id', $r->id)->select()->first())){
            return response()->json([
                'status' => false,
                'message' => 'Employee is not exsist'
            ],404);
        }else{
            Employee::where('id', $r->id)->update(['status' => $r->status]);
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ],200);
        }
    }
    // delet employee
    public function delete(Request $r){
        $validated = $r->validate([
            'id' => 'required'
        ]);
        $employee = Employee::where('id', $r->id)->select()->first();
        if(empty($employee)){
            return response()->json([
                'status' => false,
                'message' => 'Employee is not exsist'
            ],404);
        }else{
            $employee->delete();
            return response()->json([
                'status' => true,
                'message' => 'Employee deleted successfully'
            ],200);
        }
    }
    // get employee details
    public function get(Request $r){
        $validated = $r->validate([
            'id' => 'required'
        ]);
        $employee = Employee::where('id', $r->id)->select()->first();
        if(empty($employee)){
            return response()->json([
                'status' => false,
                'message' => 'Employee is not exsist'
            ],404);
        }else{
            return response()->json([
                'status' => true,
                'message' => "This is the employee data",
                'data' => $employee
            ],200);
        }
    }

    // get employee delails by name
    public function get_name(Request $r){
        $r_array = $r->all();
        if(key_exists('name', $r_array)){
            $e = Employee::where('name' , 'like' , "%$r->name%")->get();
            return response()->json([
                'status' => true ,
                'message' => "This is the employees data that their name matches $r->name" ,
                'data' => $e
            ],200);
        }else{
            $e = Employee::all();
            return response()->json([
                'status' => false ,
                'message' => "This is all employees data",
                'data' => $e
            ],200);
        }
    }
}
