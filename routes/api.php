<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\EmployeeRequestController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\JustifyRquestController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\VacationRquestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'api'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('verify_code', [AuthController::class, 'verify_code']);
});


Route::group(['middleware' => 'auth:api'], function () {

    // -- Admin --//
    Route::post('create_employee', [AdminController::class, 'store']);
    Route::post('create_hr', [AdminController::class, 'store_hr']);
    Route::post('create_admin', [AdminController::class, 'store_admin']);



    Route::delete('employee/{id}', [AdminController::class, 'destroyEmployee']);
    Route::post('determine_working_hours', [AdminController::class, 'determine_working_hours']);
    Route::post('reward_adversaries_salary', [AdminController::class, 'reward_adversaries_salary']);
    Route::get('get_dashboard_data', [AdminController::class, 'getDashboardData']);
    Route::get('get_employees_list', [AdminController::class, 'getEmployeesList']);
    Route::get('employees_salaries', [AdminController::class, 'employees_salaries']);
    Route::post('update_salary', [AdminController::class, 'update_salary']);
    Route::post('update_employment_contract', [AdminController::class, 'update_employment_contract']);
    Route::get('employees_attendances', [AdminController::class, 'employees_attendances']);
    Route::get('get_employee/{id}', [AdminController::class, 'getEmployee']);
    Route::get('profile', [AdminController::class, 'profile']);
    Route::post('check_in_attendance', [AdminController::class, 'check_in_attendance']);
    Route::post('check_out_attendance', [AdminController::class, 'check_out_attendance']);
    Route::post('termination_employees_contract', [AdminController::class, 'termination_employees_contract']);
    Route::get('list_of_nationalities', [AdminController::class, 'list_of_nationalities']);





    // -- Posts -- //
    Route::post('create_post', [PostController::class, 'store']);
    Route::get('get_posts_list', [PostController::class, 'getPostsList']);
    Route::get('get_my_posts', [PostController::class, 'getMyPosts']);
    Route::post('add_comment', [PostController::class, 'addComment']);
    Route::post('add_like', [PostController::class, 'addLike']);
    Route::post('add_like_comment', [PostController::class, 'add_like_comment']);
    Route::post('share_post', [PostController::class, 'sharePost']);
    Route::delete('post/{id}', [PostController::class, 'destroyPost']);
    Route::delete('comment/{id}', [PostController::class, 'destroyComment']);


    // -- Vcation Requests -- //
    Route::post('add_vacation_request', [VacationRquestController::class, 'store']);
    Route::put('approve_vacation_request/{id}', [VacationRquestController::class, 'approve_vacation_request']);
    Route::post('reject_vacation_request', [VacationRquestController::class, 'reject_vacation_request']);
    Route::get('vacation_request/{id}', [VacationRquestController::class, 'show']);
    Route::get('my_vacation_requests', [VacationRquestController::class, 'getMyVacationRequests']);
    Route::get('vacation_requests', [VacationRquestController::class, 'getVacationRequests']);
    Route::get('my_monthly_shift', [VacationRquestController::class, 'getMonthlyData']);
    Route::get('get_all_requests', [VacationRquestController::class, 'get_all_requests']);

    // -- Justify Requests -- //
    Route::post('add_justify_request', [JustifyRquestController::class, 'store']);
    Route::get('justify_request/{id}', [JustifyRquestController::class, 'show']);

    Route::put('approve_justify_request/{id}', [JustifyRquestController::class, 'approve_justify_request']);
    Route::post('reject_justify_request', [JustifyRquestController::class, 'reject_justify_request']);

    Route::get('my_justify_requests', [JustifyRquestController::class, 'getMyJustifyRequests']);
    Route::get('justify_requests', [JustifyRquestController::class, 'getJustifyRequests']);


    Route::post('employee_request', [EmployeeRequestController::class, 'employee_request']);
    Route::put('approve_employee_request/{id}', [EmployeeRequestController::class, 'approve_employee_request']);
    Route::post('reject_employee_request', [EmployeeRequestController::class, 'reject_employee_request']);

    // -- Alerts -- //
    Route::post('add_alert', [AlertController::class, 'store']);
    Route::get('get_my_alert', [AlertController::class, 'getMyAlert']);

    // -- Chat -- //
    Route::get('get_hrs_list', [MessageController::class, 'getHrsList']);
    Route::get('/private-messages/{user}', [MessageController::class, 'privateMessages'])->name('privateMessages');
    Route::post('/private-messages/{user}', [MessageController::class, 'sendPrivateMessage'])->name('privateMessages.store');

    // -- Notifications  -- //
    Route::get('/notification', [NotificationController::class, 'index']);


    // -- Company  -- //
    Route::post('create_company', [CompanyController::class, 'store']);
    Route::get('company/{id}', [CompanyController::class, 'show']);
    Route::post('update_comapny', [CompanyController::class, 'update_comapny']);
    // Route::post('update_company_location', [CompanyController::class, 'update_company_location']);



    // -- Holiday -- //
    Route::post('create_weekly_holiday', [HolidayController::class, 'create_weekly_holiday']);
    Route::post('create_annual_holiday', [HolidayController::class, 'create_annual_holiday']);
    Route::post('update_weekly_holiday', [HolidayController::class, 'update_weekly_holiday']);
    Route::post('update_annual_holiday', [HolidayController::class, 'update_annual_holiday']);
    Route::get('list_of_holidays', [HolidayController::class, 'list_of_holidays']);

    // -- Deposit -- //
    Route::post('craete_deposit', [DepositController::class, 'store']);
    Route::put('approve_deposit/{id}', [DepositController::class, 'approve_deposit']);
    Route::post('reject_deposit', [DepositController::class, 'reject_deposit']);
    Route::put('approve_clearance_request/{id}', [DepositController::class, 'approve_clearance_request']);
});
