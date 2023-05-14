<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WEB\ManagerController;
use App\Http\Controllers\Api\WEB\AllEmpDataController;
use App\Http\Controllers\Api\WEB\EmpHistoryController;
use App\Http\Controllers\Api\WEB\BranchHistoryController;
use App\Http\Controllers\Api\WEB\ReportsController;
use App\Http\Controllers\Api\MOBILE\EmployeeController;
use App\Http\Controllers\Api\MOBILE\CheckinController;
use App\Http\Controllers\Api\MOBILE\CheckoutController;
use App\Http\Controllers\Api\MOBILE\HistoryController;
use App\Http\Controllers\Api\MOBILE\SidebarDataController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// APIS FOR MANAGERS
Route::group([
    'middleware' => 'api', 'prefix' => 'authMgr'
], function ($router) {
    Route::post('/login', [ManagerController::class, 'login']);
    Route::post('/addemp', [ManagerController::class, 'add_employee']);
    Route::post("/check", [ManagerController::class, 'check_email_and_phone']);
    Route::get("/allemps", [AllEmpDataController::class, 'get_all_emp_data']);
    Route::get("/emphistory", [EmpHistoryController::class, 'get_emp_history']);
    Route::get("/branchhistory", [BranchHistoryController::class, 'get_branch_history']);
    Route::get("/reports", [ReportsController::class, 'get_reports']);
});

// MANAGER TOKEN REQUIRED
Route::group([
    'middleware' => 'auth.guard:mgr_api', 'prefix' => 'authMgr'
], function ($router) {
    Route::post('/logout', [ManagerController::class, 'logout']);
});


// ----------------------------------------------------------------------------------------------------------


// APIS FOR EMPLOYEES
Route::group([
    'middleware' => 'api', 'prefix' => 'authEmp'
], function ($router) {
    Route::post('/login', [EmployeeController::class, 'login']);
});

// EMPLOYEE TOKEN REQUIRED
Route::group([
    'middleware' => 'auth.guard:emp_api', 'prefix' => 'authEmp'
], function ($router) {
    Route::post('/logout', [EmployeeController::class, 'logout']);
    Route::post("/attend", [CheckinController::class, 'attend']);
    Route::post('/depart', [CheckoutController::class, 'depart']);
    Route::get('/emphistory', [HistoryController::class, 'get_emp_history']);
    Route::get('/homehistory', [HistoryController::class, 'get_home_history']);
    // Route::get('/empdata', [SidebarDataController::class, 'sidebar_emp_data']); h2 
});