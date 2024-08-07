<?php

use App\Http\Controllers\AcceptancerateController;
use App\Http\Controllers\CallnumController;
use App\Http\Controllers\CallqualityController;
use App\Http\Controllers\CallreturnController;
use App\Http\Controllers\CommitmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ManegerController;
use App\Http\Controllers\ProblemticController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TargetpointController;
use App\Models\Callnum;
use App\Models\Targetpoint;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//login
Route::post('/login', [ManegerController::class, 'login']);

Route::middleware('Check')->group(function(){
    //logout
    Route::post('/out', [ManegerController::class, 'out']);

    //password change
    Route::post('/password/change', [ManegerController::class, 'password_change']);

    //put target value
    Route::post('/target/put', [TargetController::class, 'target_value']);

    //create new employee
    Route::post('/employee/create', [EmployeeController::class, 'create']);

    //update employee details
    Route::post('/employee/update', [EmployeeController::class, 'update']);

    // update employee status
    Route::post('/employee/update/status', [EmployeeController::class, 'update_status']);

    //delete employee
    Route::post('/employee/delete', [EmployeeController::class, 'delete']);

    //get employee details
    Route::post('/employee/get', [EmployeeController::class, 'get']);

    //add evaluation rule
    Route::post('/evaluation', [EvaluationController::class, 'add_evaluation']);

    //update evaluation rule
    Route::post('/evaluation/update', [EvaluationController::class, 'update_evaluation']);

    //delete evaluation rule
    Route::post('/evaluation/delete', [EvaluationController::class, 'delete_evaluation']);

    //get evaluation rule
    Route::post('/evaluation/get', [EvaluationController::class, 'get_evaluation']);

    //put Target Points from maneger for an employee
    Route::post('/puttargetmaneger', [TargetpointController::class, 'add_targetpoint']);

    //delete Target Points
    Route::post('/puttargetmaneger/delete', [TargetpointController::class, 'delet_targetpoint']);

    //get Target points for a month "from maneger"
    Route::post('/puttargetmaneger/get', [TargetpointController::class, 'get_targetpoint_month']);

    //create call number for an employee
    Route::post('/callnum', [CallnumController::class, 'add_calnum']);

    //delete call number
    Route::delete('/callnum/delete', [CallnumController::class, 'delet_calnum']);

    //get call number for a month
    Route::get('/callnum/get', [CallnumController::class, 'get_callnum_month']);

    //create problem tickits number for an employee
    Route::post('/probtic', [ProblemticController::class, 'add_porbtic']);

    //delete problem tickits number
    Route::delete('/probtic/delete', [ProblemticController::class, 'delet_probtic']);

    //get prolem tickits number for a month
    Route::get('/probtic/get', [ProblemticController::class, 'get_probtic_month']);

    //create Follow errorrs number for an employee
    Route::post('/follow', [ProblemticController::class, 'add_porbtic']);

    //delete Follow errorrs number
    Route::delete('/follow/delete', [ProblemticController::class, 'delet_probtic']);

    //get Follow errorrs number for a month
    Route::get('/follow/get', [ProblemticController::class, 'get_probtic_month']);

    //create call quality for an employee
    Route::post('/callquality', [CallqualityController::class, 'add_callquality']);

    //delete call quality number
    Route::delete('/callquality/delete', [CallqualityController::class, 'delet_callquality']);

    //get call quality number for a month
    Route::get('/callquality/get', [CallqualityController::class, 'get_callquality_month']);

    //create Commitment for an employee
    Route::post('/commitment', [CommitmentController::class, 'add_commitment']);

    //delete Commitment number
    Route::delete('/commitment/delete', [CommitmentController::class, 'delet_commitment']);

    //get Commitment number for a month
    Route::get('/commitment/get', [CommitmentController::class, 'get_commitment_month']);

    //create Acceptance rate for an employee
    Route::post('/acceptancerate', [AcceptancerateController::class, 'add_acceptance']);

    //delete Acceptance rate
    Route::delete('/acceptancerate/delete', [AcceptancerateController::class, 'delet_acceptance']);

    //get Acceptance rate for a month
    Route::get('/acceptancerate/get', [AcceptancerateController::class, 'get_acceptance_month']);

    //create Call return for an employee
    Route::post('/callreturn', [CallreturnController::class, 'add_callreturn']);

    //delete Call return
    Route::delete('/callreturn/delete', [CallreturnController::class, 'delet_callreturn']);

    //get Call return for a month
    Route::get('/callreturn/get', [CallreturnController::class, 'get_callreturn_month']);

    //create Suggestion for an employee
    Route::post('/suggestion', [SuggestionController::class, 'add_suggestion']);

    //delete Suggestion
    Route::delete('/suggestion/delete', [SuggestionController::class, 'delet_suggestion']);

    //get Suggestion for a month
    Route::get('/suggestion/get', [SuggestionController::class, 'get_suggestion_month']);


});
