<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    

    // membership
    Route::resource('membership', 'App\Http\Controllers\MembershipController');
    Route::get('fetch-pre-members', [App\Http\Controllers\MembershipController::class, 'fetchPreMembers'])->name('fetchPreMembers');
    Route::get('fetch-members', [App\Http\Controllers\MembershipController::class, 'fetchMembers'])->name('fetchMembers');

    // pre membership
    Route::get('pre-membership', [App\Http\Controllers\MembershipController::class, 'preMembershipIndex'])->name('pre_membership_index');
    Route::get('pre-membership/create', [App\Http\Controllers\MembershipController::class, 'preMembershipCreate'])->name('pre_membership_create');
    Route::post('pre-membership', [App\Http\Controllers\MembershipController::class, 'preMembershipStore'])->name('preMembershipStore');
    Route::get('pre-membership/{id}/edit', [App\Http\Controllers\MembershipController::class, 'preMembershipEdit'])->name('pre_membership_edit');
    Route::put('pre-membership/update/{id}', [App\Http\Controllers\MembershipController::class, 'preMembershipUpdate'])->name('preMembershipUpdate');
    Route::delete('pre-membership/{id}', [App\Http\Controllers\MembershipController::class, 'preMembershipDestroy'])->name('preMembershipDestroy');

    //survey
    Route::get('survey-report', [App\Http\Controllers\HomeController::class, 'surveyReport'])->name('surveyReport');
    Route::post('api/fetch-survey', [App\Http\Controllers\HomeController::class, 'fetchSurvey']);
});

Route::get('/survey', function () {
    return view('welcome');
});
Route::post('store-survey', [App\Http\Controllers\SurveyController::class, 'store']);
Route::post('api/fetch-municipalities', [App\Http\Controllers\AddressController::class, 'fetchMunicipalities']);
Route::post('api/fetch-barangays', [App\Http\Controllers\AddressController::class, 'fetchBarangays']);
