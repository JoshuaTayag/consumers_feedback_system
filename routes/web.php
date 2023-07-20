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

    // roles and permissions
    Route::resource('roles', App\Http\Controllers\Auth\RoleController::class);

    // users routes
    Route::get('users', [App\Http\Controllers\Auth\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [App\Http\Controllers\Auth\UserController::class, 'create'])->name('users.create');
    Route::post('users', [App\Http\Controllers\Auth\UserController::class, 'store'])->name('userStore');
    Route::get('users/{id}/edit', [App\Http\Controllers\Auth\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [App\Http\Controllers\Auth\UserController::class, 'update'])->name('userUpdate');
    Route::delete('users/{id}', [App\Http\Controllers\Auth\UserController::class, 'destroy'])->name('userDestroy');

    // roles and permissions
    Route::resource('teller', App\Http\Controllers\PowerBill\TellerController::class);

});

Route::get('/survey', function () {
    return view('welcome');
});
Route::post('store-survey', [App\Http\Controllers\SurveyController::class, 'store']);
Route::post('api/fetch-municipalities', [App\Http\Controllers\AddressController::class, 'fetchMunicipalities']);
Route::post('api/fetch-barangays', [App\Http\Controllers\AddressController::class, 'fetchBarangays']);
