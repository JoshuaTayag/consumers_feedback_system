<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('register', [App\Http\Controllers\AgmmController::class, 'agmmRegister']);
Route::get('account/{id}', [App\Http\Controllers\AgmmController::class, 'validateAccount'])->middleware('auth:sanctum');
Route::get('agmm-registration/{id}', [App\Http\Controllers\AgmmController::class, 'getRegistration'])->middleware('auth:sanctum');
Route::get('agmm-pre-registration/{id}', [App\Http\Controllers\AgmmController::class, 'getPreRegistration'])->middleware('auth:sanctum');
Route::get('agmm-registration/qr/{id}', [App\Http\Controllers\AgmmController::class, 'getRegistrationByQr'])->middleware('auth:sanctum')->name('checkRegistration');
Route::get('agmm-transportation-allowance/{id}', [App\Http\Controllers\AgmmController::class, 'getAllowance'])->middleware('auth:sanctum')->name('apiTranspoAllowance');
Route::get('agmm-issue-transportation-allowance/{id}', [App\Http\Controllers\AgmmController::class, 'issueAllowance'])->middleware('auth:sanctum')->name('apiIssueTranspoAllowance');
Route::get('agmm-registration/from-preregistration/{id}', [App\Http\Controllers\AgmmController::class, 'agmmVerifyAccountFromPreReg'])->middleware('auth:sanctum')->name('apiVerifyPreRegistration');
Route::get('agmm-verified-registration/{id}', [App\Http\Controllers\AgmmController::class, 'getVerifiedRegistration'])->middleware('auth:sanctum')->name('getVerifiedRegistration');

Route::get('fetch-members/', [App\Http\Controllers\MembershipController::class, 'getMembers']);
Route::get('fetch-mcrt/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'getMcrt']);
Route::get('fetch-mst/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'getMst']);