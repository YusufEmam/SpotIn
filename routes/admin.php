<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\AddEmployeeController;
use App\Models\Branch;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\JoinTableController;
use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//FOR BACKEND
Route::get('/showemps', [AddEmployeeController::class, 'showemps'])->name('Emps.show');

//DIDN'T LOGIN YET!
Route::group(["namespace" => "Backend", "prefix" => "admin", "middleware" => "guest:admin"],function()
{
    Route::get('/login', [LoginController::class, 'show_login_view'])->name('LoginPage');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.Login');
});

//LOGGED IN!
Route::group(["namespace" => "Backend", "prefix" => "admin", "middleware" => "auth:admin"],function()
{
    Route::get('/addemp', [AddEmployeeController::class, 'show_addemp_view'])->name('AddEmpPage');
    Route::post('/addemp', [AddEmployeeController::class, 'addemp'])->name('admin.AddEmp');
    Route::get('/home', [HomeController::class, 'index'])->name('admin.Home');
    Route::get('/logout', [HomeController::class, 'logout'])->name('admin.Logout');
});


