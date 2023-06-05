<?php

namespace App\Http\Controllers\Api\MOBILE;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Departure;
use App\Models\Employee;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function get_emp_history()
{
    $user_id = auth()->id();
    if (Attendance::select("employee_id")->where(["employee_id" => $user_id])->exists())
    {
        //paginate var

        $data = Attendance::join('departures', function ($join) {
            $join->on('attendances.employee_id', '=', 'departures.employee_id')
                ->on('attendances.att_Date', '=', 'departures.dep_Date');
        })
        ->select('attendances.branch_name', 'attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time')
        ->where('attendances.employee_id', $user_id)
        ->orderByRaw("STR_TO_DATE(attendances.att_Date, '%d/%m/%Y') DESC")
        ->get();

        $dataWithIds = [];
        $count = 1;
        foreach ($data as $item) {
            $dataWithIds[] = ['number' => $count++, 'data' => $item];
        }

        return response()->json(['status' => 200, 'message' => 'Attendance and Departure history', 'data' => $data], 200);
    }
    else
    {
        return response()->json(['status' => 403, 'message' => 'Employee never attended!'], 200);
    }
}


    public function get_home_history()
    {
        $user_id = auth()->id();
        if (Attendance::select("employee_id")->where(["employee_id" => $user_id])->exists())
        {
            //paginate var
            $data = Attendance::Leftjoin('departures', function ($join) {
                $join->on('attendances.employee_id', '=', 'departures.employee_id')
                    ->on('attendances.att_Date', '=', 'departures.dep_Date');
            })
            ->select('attendances.branch_name', 'attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time', 'attendances.last_att_status')
            ->where('attendances.employee_id', $user_id)
            ->orderByRaw("STR_TO_DATE(attendances.att_Date, '%d/%m/%Y') DESC")
            ->first();

            if (!$data->dep_Time) {
                $data->dep_Time = null;
            }

            return response()->json(['status' => 200, 'message' => 'Attendance and Departure history', 'data' => $data], 200);
        }
        else
        {
            return response()->json(['status' => 403, 'message' => 'Employee never attended!'], 200);
        }
    }
}
