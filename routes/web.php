<?php

use App\Http\Controllers\TougouMessagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TougouUsersController;
use App\Http\Controllers\UserController;
use App\Models\TougouUser;
use App\Models\User;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RirekiController;
use App\Http\Controllers\LineLoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/home', [TougouMessagesController::class, 'index'])->name('remoteusers.index');
// Route::get('/home', [TougouUsersController::class, 'profile'])->name('profile');
// Route::get('users', [TougouUsersController::class, 'index'])->name('users.index');


Route::get('profile', [TougouUsersController::class, 'profile'])->name('profile');

Route::controller(TougouUsersController::class)->group(function(){
    // Route::get('messages-send', 'message_send');
    // Route::post('messages-send-submit', 'create');
});
Route::controller(TougouMessagesController::class)->group(function(){
    // Route::post('messages-send-submit', 'create');
});

// Route::resource('users12', TougouMessagesController::class);

Route::get('messages', [TougouMessagesController::class, 'index'])->name('remoteusers.index');
// Route::get('rireki', [TougouMessagesController::class, 'rireki']);
Route::get('/message-send', [TougouMessagesController::class, 'index']);

// Route::post('/import', [TougouMessagesController::class, 'import']);
Route::post('/sendRequest', [TougouMessagesController::class, 'sendRequest']);
Route::post('/sendMessage', [TougouMessagesController::class, 'sendMessage']);
Route::post('/message/search',[TougouMessagesController::class,'showmessage'])->name('message.search');


// User groups ....
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::post('/import',[UserController::class,'import'])->name('users.import');


Route::resource('roles', RoleController::class);
Route::resource('policy', PolicyController::class);
Route::resource('permissions', PermissionsController::class);
Route::post('/permission', [PermissionsController::class, 'permission']);
Route::resource('rireki', RirekiController::class);


Route::group(['prefix' => 'user'], function () {
    Route::get('/login/line', [LineLoginController::class, 'login'])->name('login.line');
    Route::get('/line/check', [LineLoginController::class, 'check'])->name('login.line.check');
    Route::get('/error', [LineLoginController::class, 'check'])->name('login.line.error');
});