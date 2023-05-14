<?php

namespace App\Http\Controllers\Api\WEB;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function get_reports()
    {
        $data = Attendance::join('departures', function ($join) {
            $join->on('attendances.employee_id', '=', 'departures.employee_id')
                ->on('attendances.att_Date', '=', 'departures.dep_Date');
        })
        ->join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->join('branches', 'attendances.branch_id', '=', 'branches.id')
        ->select('employees.id as emp_id', 'employees.name', 'attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time', 'employees.department', 'branches.name as branch_name', 'attendances.att_comment', 'departures.dep_comment')
        ->orderBy('attendances.att_Date', 'DESC')
        ->get();
        
        // return response()->json(['status' => 200, 'message' => 'Reports', 'data' => $data], 200);
        return response()->json($data, 200);
    }
}
