<?php

namespace App\Http\Controllers\Api\MOBILE;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {   
        if (validEmail($request->input("email")))
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
        }
        else if (!validEmail($request->input("phonenumber")))
        {
            $validator = Validator::make($request->all(), [
                'phonenumber' => 'required|numeric',
                'password' => 'required|string',
            ]);
        }
        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }
        //msh fl database
        $token = auth("emp_api")->attempt($validator->validated());
        if (!$token) {
            return api_response_not_found(406, "User data is required with correct password", null);
        }

        $data = auth("emp_api")->user();

        if ($data->photo != null) 
        {
            $data->photo = url('uploads/' . $data->photo);
        } 
        else 
        {
            if ($data->gender == "Male")
            {
                $data->photo = url("/default/male.png");
            }
            else
            {
                $data->photo = url("/default/female.png");
            }
        }

        // $data = Employee::where("email", $request->input("email"))->orWhere("phonenumber", $request->input("phonenumber"))->first();
        //ANOTHER METHOD TO GET DATA!!!!!!!
        return api_response($token, 200, "Login success", $data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("emp_api")->logout();
        return response()->json(['status' => 200, 'message' => 'User successfully signed out'], 200);
    }

    // /**
    //  * Refresh a token.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function refresh()
    // {
    //     return $this->createNewToken(auth()->refresh());
    // }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL(),
        ]);
    }
}
