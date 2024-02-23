<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Authentication\AuthenticationController;


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

Route::post('/auth/register', [AuthenticationController::class, 'register']);

Route::post('/auth/login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/user', [AuthenticationController::class, 'authUser']);
    Route::post('/auth/logout', [AuthenticationController::class, 'logout']);
    Route::post('/auth/admin_switch_to_user', [AuthenticationController::class, 'adminSwitchToUser']);
    Route::post('/auth/user_switch_to_admin', [AuthenticationController::class, 'userSwitchToAdmin']);
});