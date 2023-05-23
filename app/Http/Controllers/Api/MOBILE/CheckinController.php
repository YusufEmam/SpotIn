<?php

namespace App\Http\Controllers\Api\MOBILE;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class CheckinController extends Controller
{
    private function isInsidePolygon($polygon, $point)
    {
        $intersectCount = 0;
        $latitude = $point[0];
        $longitude = $point[1];
        $pointCount = count($polygon);

        for ($i = 0; $i < $pointCount; $i++) {
            $point1 = $polygon[$i];
            $point2 = $polygon[($i + 1) % $pointCount];
            $lat1 = $point1[0];
            $lat2 = $point2[0];
            $long1 = $point1[1];
            $long2 = $point2[1];

            if (($lat1 <= $latitude && $latitude < $lat2) || ($lat2 <= $latitude && $latitude < $lat1)) {
                $slope = ($long2 - $long1) / ($lat2 - $lat1);
                $intersectLong = $long1 + ($latitude - $lat1) * $slope;

                if ($intersectLong > $longitude) {
                    $intersectCount++;
                }
            }
        }

        return $intersectCount % 2 == 1;
    }

    private function get_allowed_zones()
    {
        //ALLOWED ZONES
        $el_shorouk_academy = [
            [
                "id" => 1,
                "name" => "El Shorouk Academy",
                "points" => [
                    [30.120033340643701, 31.605739062001501],
                    [30.119964084139301, 31.605410391356202],
                    [30.1199089151335, 31.605096519906599],
                    [30.119334116645501, 31.605223162407299],
                    [30.119305236501599, 31.605332446804901],
                    [30.1191642462563, 31.605366035330398],
                    [30.1191309604921, 31.6053550481973],
                    [30.118878317082, 31.605406572854001],
                    [30.118872018276999, 31.605550275221901],
                    [30.118904343100599, 31.605737443874801],
                    [30.118671725617201, 31.605787823186098],
                    [30.1186415015394, 31.605870779848001],
                    [30.118780342707399, 31.606692237775398],
                    [30.1188443745522, 31.606742900844999],
                    [30.1188769740711, 31.606772585467901],
                    [30.118895224256399, 31.6069868237348],
                    [30.118936501992199, 31.607226719451901],
                    [30.1190503463142, 31.607496503975199],
                    [30.1192271448177, 31.607735131124201],
                    [30.119270806046, 31.607795683935102],
                    [30.119342448936202, 31.608378497849401],
                    [30.119407830144301, 31.608514811486799],
                    [30.119563913991801, 31.608589085031301],
                    [30.119771029416899, 31.608570297952799],
                    [30.119867949301, 31.608545341446099],
                    [30.119847838369498, 31.608412794209301],
                    [30.120158400993802, 31.608341767972199],
                    [30.120177357545099, 31.608456741055001],
                    [30.1201856204362, 31.6084916610905],
                    [30.1202374906362, 31.6084370829929],
                    [30.120294294301701, 31.608396922116398],
                    [30.1203963343048, 31.608381018179099],
                    [30.120454288964002, 31.608390498961001],
                    [30.120490642287699, 31.608392955067199],
                    [30.120520468487999, 31.608409445585099],
                    [30.120435614524698, 31.607963215846201],
                    [30.120337626980401, 31.60739494728],
                    [30.120071928472001, 31.605870421609499],
                    [30.120033340643701, 31.605739062001501],
                ]
            ]
        ];

        $mass_communication = [
            [
                "id" => 2,
                "name" => "Mass Communication",
                "points" => [
                    [30.113771288317199, 31.606623695118301],
                    [30.111426276586101, 31.607128051766601],
                    [30.111706821079, 31.6088786999489],
                    [30.1121909620305, 31.609213476056699],
                    [30.1124946823393, 31.609152248213],
                    [30.112803459495399, 31.6086276663959],
                    [30.113051511141201, 31.6085916889893],
                    [30.1134935372427, 31.608901719536998],
                    [30.1136057951237, 31.608813248967898],
                    [30.113793783819901, 31.6087626183811],
                    [30.1139755334708, 31.6084751861363],
                    [30.113967663437101, 31.6083474618978],
                    [30.1139067536281, 31.6080623745314],
                    [30.1140012423073, 31.607948955114601],
                    [30.1140417423988, 31.6078450727796],
                    [30.113771288317199, 31.606623695118301],
                ]
            ]
        ];

        $allowedzones = [$el_shorouk_academy, $mass_communication];

        return $allowedzones;
    }

    public function attend(Request $request)
    {
        $polygons = $this->get_allowed_zones();
        $loggedInUserId = auth()->id();

        if (Auth::guard('emp_api')->user()) {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|string',
                'longitude' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 200);
            }
            $userLocation = [$request->input('latitude'), $request->input('longitude')];
            // Check if the user's location is allowed
            foreach ($polygons as $polygon) {
                foreach ($polygon as $zone) {
                    // Check if the user's location is inside the polygon
                    if ($this->isInsidePolygon($zone["points"], $userLocation)) {
                        // Check if the user has already attended on the current day
                        $date = Carbon::now()->format('d/m/Y');
                        $hasAttended = Attendance::where('employee_id', $loggedInUserId)
                            ->where('att_Date', $date)
                            ->exists();

                        if ($hasAttended) {
                            return response()->json(['status' => 403, 'message' => 'You have already attended today!'], 200);
                        }

                        // Create a new attendance record
                        $attendance = new Attendance();
                        $attendance->att_Latitude = $request->input('latitude');
                        $attendance->att_Longitude = $request->input('longitude');
                        date_default_timezone_set("Asia/Riyadh");
                        $attendance->att_Date = $date;
                        $attendance->att_Time = date("h:i:s A", $request->att_Time);
                        $attendance->att_address = $request->input('address');
                        $attendance->last_att_status = "lst-1";
                        $attendance->att_comment = $request->att_comment;
                        $attendance->branch_id = $zone["id"];
                        $attendance->branch_name = $zone["name"];
                        $attendance->employee_id = auth()->user()->id;
                        $attendance->save();

                        return response()->json(['status' => 200, 'message' => 'Checkin saved successfully!'], 200);
                    }
                }
            }
            return response()->json(['status' => 403, 'message' => 'Location not allowed'], 200);
        }
    }
}
