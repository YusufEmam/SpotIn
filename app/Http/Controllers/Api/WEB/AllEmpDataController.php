<?php

namespace App\Http\Controllers\Api\WEB;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class AllEmpDataController extends Controller
{
    public function get_all_emp_data()
        {
            $employees = Employee::join('branches', 'employees.branch_id', '=', 'branches.id')
            ->select('employees.id', 'employees.name', 'employees.email', 'employees.phonenumber', 'employees.gender', 'employees.department', 'employees.photo', 'branches.name as branch_name')
            ->orderby('employees.id')
            ->get();

            $employees->each(function ($employee) {
                if ($employee->photo != null) 
                {
                    $employee->photo = url('uploads/' . $employee->photo);
                } 
                else 
                {
                    if ($employee->gender == "Male")
                    {
                        $employee->photo = url("/default/male.png");
                    }
                    else
                    {
                        $employee->photo = url("/default/female.png");
                    }
                }
            });
            // return response()->json(['employees' => $employees]);
            return response()->json($employees);
        }
}
