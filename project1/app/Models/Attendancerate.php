<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendancerate extends Model
{
    public $timestamps = false;

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    use HasFactory;
}
