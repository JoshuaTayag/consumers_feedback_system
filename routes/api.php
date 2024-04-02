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

Route::get('fetch-members/', [App\Http\Controllers\MembershipController::class, 'getMembers']);