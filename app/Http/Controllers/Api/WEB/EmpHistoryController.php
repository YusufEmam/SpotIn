<?php

namespace App\Http\Controllers\Api\WEB;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EmpHistoryController extends Controller
{
    public function get_emp_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "emp_id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $user_id = request('emp_id');
        if (Attendance::select("employee_id")->where(["employee_id" => $user_id])->exists())
        {
            // $data = Attendance::join('departures', function ($join) {
            //     $join->on('attendances.employee_id', '=', 'departures.employee_id')
            //         ->on('attendances.att_Date', '=', 'departures.dep_Date');
            // })
            // ->select('attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time', 'attendances.branch_name', 'attendances.att_comment', 'departures.dep_comment')
            // ->where('attendances.employee_id', $user_id)
            // ->orderby('attendances.att_Date', 'DESC')
            // ->get();

            $data = Attendance::join('departures', function ($join) {
                $join->on('attendances.employee_id', '=', 'departures.employee_id')
                    ->on('attendances.att_Date', '=', 'departures.dep_Date');
            })
            ->select('attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time', 'attendances.branch_name', 'attendances.att_comment', 'departures.dep_comment')
            ->where('attendances.employee_id', $user_id)
            ->orderByRaw("attendances.att_Date DESC, attendances.att_Time DESC")
            ->get();


            return response()->json(['status' => 200, 'message' => 'Attendance and Departure history', 'data' => $data], 200);
        }
        else
        {
            return response()->json(['status' => 403, 'message' => 'Employee never attended!'], 403);
        }
    }
}