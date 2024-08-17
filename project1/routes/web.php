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
use App\Models\Maneger;
use App\Models\Targetpoint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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

//change lang
Route::get('/greeting/{locale}', function ($locale) {
    if (in_array($locale,['en', 'ar'])) {
        DB::table('lang')->where('íd', 1)->update(['locale' => $locale]);
        return response()->json([
            'status' => true,
            'message' => __('message.changelangtrue')
        ],200);
    }
    return response()->json([
        'status' => false,
        'message' => __('message.changelangfalse')
    ],422);
});

Route::get('language/get', function(){
    $locale = App::currentLocale();
    return response()->json([
        'status' => true,
        'message' => __('message.lang'),
        'data' => $locale
    ],200);
});
Route::middleware('Check')->group(function(){


    Route::get('/nnjj', [TargetController::class, 'get_targets']);

    // get targets with employees
    Route::get("/target/getemp", [TargetController::class, 'get_targets']);

    //get maneger details
    Route::get('/maneger/get', [ManegerController::class, 'get_details']);

    //logout
    Route::get('/out', [ManegerController::class, 'out']);

    //password change
    Route::post('/password/change', [ManegerController::class, 'password_change']);

    //put target value
    Route::post('/target/put', [TargetController::class, 'target_value']);

    // get target value
    Route::get('target/get', [TargetController::class, 'get_target_value']);

    //create new employee
    Route::post('/employee/create', [EmployeeController::class, 'create']);

    //update employee details
    Route::post('/employee/update', [EmployeeController::class, 'update']);

    // update employee status
    Route::post('/employee/update/status', [EmployeeController::class, 'update_status']);

    //delete employee
    Route::delete('/employee/delete', [EmployeeController::class, 'delete']);

    //get employee details
    Route::get('/employee/get', [EmployeeController::class, 'get']);

    //get employees by name
    Route::get('/employee/name', [EmployeeController::class, 'get_name']);

    //get employees by name working employees
    Route::get('/employee/working/name', [EmployeeController::class, 'get_name_working']);

    //add evaluation rule
    Route::post('/evaluation', [EvaluationController::class, 'add_evaluation']);

    //update evaluation rule
    Route::post('/evaluation/update', [EvaluationController::class, 'update_evaluation']);

    //delete evaluation rule
    Route::delete('/evaluation/delete', [EvaluationController::class, 'delete_evaluation']);

    //get evaluation rule
    Route::get('/evaluation/get', [EvaluationController::class, 'get_evaluation']);

    // get evaluation champions
    Route::get('/evaluation/champions', [EvaluationController::class, 'evaluation_champions']);

    //put Target Points from maneger for an employee
    Route::post('/puttargetmaneger', [TargetpointController::class, 'add_targetpoint']);

    //delete Target Points
    Route::delete('/puttargetmaneger/delete', [TargetpointController::class, 'delet_targetpoint']);

    //get Target points for a month "from maneger"
    Route::get('/puttargetmaneger/get', [TargetpointController::class, 'get_targetpoint_month']);

    // get evaluation types
    Route::get('/evaluation/types', [EvaluationController::class, 'evaluation_types']);

    // get evaluation rules types
    Route::get('/evaluationrules/types', [EvaluationController::class, 'evaluation_rules_types']);

    // add evaluation for the employee
    Route::post('/evaluations/add', [EvaluationController::class, 'add_evaluation_emp']);

    // get evaluation hestory
    Route::get('evaluation/get/form', [EvaluationController::class, 'get_evaluation_form']);





    //////////////////////////////////////////////////////

    // //create call number for an employee
    // Route::post('/callnum', [CallnumController::class, 'add_calnum']);

    // //delete call number
    // Route::delete('/callnum/delete', [CallnumController::class, 'delet_calnum']);

    // //get call number for a month
    // Route::get('/callnum/get', [CallnumController::class, 'get_callnum_month']);

    // //create problem tickits number for an employee
    // Route::post('/probtic', [ProblemticController::class, 'add_porbtic']);

    // //delete problem tickits number
    // Route::delete('/probtic/delete', [ProblemticController::class, 'delet_probtic']);

    // //get prolem tickits number for a month
    // Route::get('/probtic/get', [ProblemticController::class, 'get_probtic_month']);

    // //create Follow errorrs number for an employee
    // Route::post('/follow', [ProblemticController::class, 'add_porbtic']);

    // //delete Follow errorrs number
    // Route::delete('/follow/delete', [ProblemticController::class, 'delet_probtic']);

    // //get Follow errorrs number for a month
    // Route::get('/follow/get', [ProblemticController::class, 'get_probtic_month']);

    // //create call quality for an employee
    // Route::post('/callquality', [CallqualityController::class, 'add_callquality']);

    // //delete call quality number
    // Route::delete('/callquality/delete', [CallqualityController::class, 'delet_callquality']);

    // //get call quality number for a month
    // Route::get('/callquality/get', [CallqualityController::class, 'get_callquality_month']);

    // //create Commitment for an employee
    // Route::post('/commitment', [CommitmentController::class, 'add_commitment']);

    // //delete Commitment number
    // Route::delete('/commitment/delete', [CommitmentController::class, 'delet_commitment']);

    // //get Commitment number for a month
    // Route::get('/commitment/get', [CommitmentController::class, 'get_commitment_month']);

    // //create Acceptance rate for an employee
    // Route::post('/acceptancerate', [AcceptancerateController::class, 'add_acceptance']);

    // //delete Acceptance rate
    // Route::delete('/acceptancerate/delete', [AcceptancerateController::class, 'delet_acceptance']);

    // //get Acceptance rate for a month
    // Route::get('/acceptancerate/get', [AcceptancerateController::class, 'get_acceptance_month']);

    // //create Call return for an employee
    // Route::post('/callreturn', [CallreturnController::class, 'add_callreturn']);

    // //delete Call return
    // Route::delete('/callreturn/delete', [CallreturnController::class, 'delet_callreturn']);

    // //get Call return for a month
    // Route::get('/callreturn/get', [CallreturnController::class, 'get_callreturn_month']);

    // //create Suggestion for an employee
    // Route::post('/suggestion', [SuggestionController::class, 'add_suggestion']);

    // //delete Suggestion
    // Route::delete('/suggestion/delete', [SuggestionController::class, 'delet_suggestion']);

    // //get Suggestion for a month
    // Route::get('/suggestion/get', [SuggestionController::class, 'get_suggestion_month']);


});
