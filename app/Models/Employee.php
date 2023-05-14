<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Departure;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = "employees";
    protected $fillable = [
        "id", "name", "email", "phonenumber",
        "password", "gender", "birthdate",
        "department", "photo", "branch_id"
    ];
    public $timestamps = false;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
