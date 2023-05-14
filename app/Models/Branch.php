<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Branch extends Model
{
    use HasFactory;
    protected $table = "branches";
    protected $fillable = [
        "id", "name",
        "address", "photo",
    ];
    public $timestamps = false;

    public function employees()
    {
        return $this->hasMany(Employee::class, "branch_id");
    }
}
