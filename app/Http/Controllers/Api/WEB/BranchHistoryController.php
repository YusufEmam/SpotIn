<?php

namespace App\Http\Controllers\Api\WEB;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BranchHistoryController extends Controller
{
    public function get_branch_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "branch_id" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $branch_id = request('branch_id');
        if (Branch::where(["id" => $branch_id])->exists())
        {
            $data = Attendance::join('departures', function ($join) {
                $join->on('attendances.employee_id', '=', 'departures.employee_id')
                    ->on('attendances.att_Date', '=', 'departures.dep_Date');
            })
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->join('branches', 'attendances.branch_id', '=', 'branches.id')
            ->select('employees.id as emp_id', 'employees.name', 'attendances.att_Date', 'attendances.att_Time', 'departures.dep_Time', 'employees.department', 'attendances.att_comment', 'departures.dep_comment')
            ->where('branches.id', $branch_id)
            ->orderBy('attendances.att_Date', 'DESC')
            ->get();

            return response()->json(['status' => 200, 'message' => 'Branch history', 'data' => $data], 200);
        }
        else
        {
            return response()->json(['status' => 403, 'message' => 'Branch not found!'], 403);
        }
    }
}
