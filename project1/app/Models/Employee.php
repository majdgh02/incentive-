<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function targets()
    {
        return $this->belongsToMany(Target::class , 'employee_target');
    }

    public function evaluations()
    {
        return $this->belongsToMany(Evaluation::class , 'employee_evaluation');
    }

    public function works()
    {
        return $this->hasMany(Work::class , 'employee_id');
    }

    public function callnums()
    {
        return $this->hasMany(Callnum::class , 'employee_id');
    }

    public function problentics()
    {
        return $this->hasMany(Problemtic::class , 'employee_id');
    }

    public function followerrors()
    {
        return $this->hasMany(Followerror::class , 'employee_id');
    }

    public function callqualities()
    {
        return $this->hasMany(Callquality::class , 'employee_id');
    }

    public function acceptancerate()
    {
        return $this->hasMany(Acceptancerate::class , 'employee_id');
    }

    public function callreturns()
    {
        return $this->hasMany(Callreturn::class , 'employee_id');
    }

    public function targetpoints()
    {
        return $this->hasMany(Targetpoint::class , 'employee_id');
    }

    public function attendancerate()
    {
        return $this->hasMany(Attendancerate::class , 'employee_id');
    }
}
