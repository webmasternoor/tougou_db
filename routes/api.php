<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

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


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'tougou',
    'namespace' => 'App\Http\Controllers\Api\Tougou'
], function () {
    // Route::apiResource('index', UserController::class);
    Route::post('/requestLogin', 'UserController@requestLogin');
    Route::post('/getIntegratedUsersName', '\App\Http\Controllers\TougouMessagesController@loadIntegratedUsers')->name('load.integrated_users.json');

    Route::get('/requestBulkInsert', 'UserController@bulkInsert');
    Route::get('/doctorRequestBulkInsert', 'UserController@bulkInsertd');
    Route::get('/doctorGateRequestBulkInsert', 'UserController@bulkInsertdg');
    Route::get('/getUserInfoByEmail/{email}', 'UserController@getUserInfoByEmail');
});
