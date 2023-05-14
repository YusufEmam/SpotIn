<?php

namespace App\Http\Controllers\Api\WEB;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManagerController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'add_employee', 'logout', 'check_email_and_phone']]);
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
            return response()->json($validator->errors(), 422);
        }
        //msh fl database
        $token = auth("mgr_api")->attempt($validator->validated());
        if (!$token) {
            return api_response_not_found(406, "User data is required with correct password", null);
        }

        // $data = Manager::where("email", $request->input("email"))->orWhere("phonenumber", $request->input("phonenumber"))->first();
        //ANOTHER METHOD TO GET DATA!!!!!!!
        $data = auth("mgr_api")->user();
        return api_response($token, 200, "Login success", $data);
    }

    public function add_employee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phonenumber' => 'nullable|unique:employees',
            'email' => 'nullable|max:100|unique:employees',
            'gender' => 'required|string',
            'birthdate' => 'nullable|string',
            'department' => 'required|string',
            'branch_id' => 'required',
            'photo' => 'nullable|max:2048',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $filename = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = upload_photo($image);
        }
        
        $branch = $request->branch_id;
        $user = Employee::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password), 'photo' => $filename, 'branch_id' => $branch]
        ));
        
        return response()->json([
            'status' => 200,
            'message' => 'Employee created successfully',
            'user' => $user
        ], 200);

    }

    public function check_email_and_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'nullable',
            'email' => 'nullable',
        ]);
        if (Employee::where('email', $request->email)->exists() && !is_null($request->email) || Employee::where('phonenumber', $request->phonenumber)->exists() && !is_null($request->phonenumber)) 
        {
            return response()->json(['exists' => true], 200);
        } 
        else 
        {
            return response()->json(['exists' => false], 200);
        }
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("mgr_api")->logout();
        return response()->json(['status' => 200, 'message' => 'Manager successfully signed out'], 200);
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
