<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $table = "departures";
    protected $fillable = [
        'id','dep_Latitude', 'dep_Longitude',
        'dep_Date', 'dep_Time','dep_address',
        'last_dep_status', 'dep_comment', 'branch_id',
        'branch_name', 'employee_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, "departure_id");
    }
}
