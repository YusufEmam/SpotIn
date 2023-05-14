<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = "attendances";
    protected $fillable = [
        'id','att_Latitude', 'att_Longitude',
        'att_Date', 'att_Time', 'att_address',
        'last_att_status', 'att_comment', 'branch_id',
        'branch_name', 'employee_id'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, "attendance_id");
    }
}
