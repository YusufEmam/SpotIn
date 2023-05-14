<?php

namespace App\Http\Controllers\Api\MOBILE;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class SidebarDataController extends Controller
{
    // public function sidebar_emp_data()
    // {
    //     $user_id = auth()->id();
    //     if (Attendance::select("employee_id")->where(["employee_id" => $user_id])->exists())
    //     {
    //         $employees = Employee::select('employees.name', 'employees.gender', 'employees.photo')
    //         ->where("employees.id", $user_id)
    //         ->get();

    //         $employees->each(function ($employee) {
    //             if ($employee->photo != null) 
    //             {
    //                 $employee->photo = url('uploads/' . $employee->photo);
    //             } 
    //             else 
    //             {
    //                 if ($employee->gender == "Male")
    //                 {
    //                     $employee->photo = url("/default/male.png");
    //                 }
    //                 else
    //                 {
    //                     $employee->photo = url("/default/female.png");
    //                 }
    //             }
    //         });
    //         return response()->json(['Employee Data' => $employees]);
    //     }
    // }
}
