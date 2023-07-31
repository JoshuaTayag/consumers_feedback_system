<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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



Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        if(Auth::user()->hasRole('Consumer')){
            return view('consumer.dashboard');
        }
        else{
            return view('home');
        }
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

    // consumer online pre membership routes
    Route::get('consumer/pre-membership/create-step-one', [App\Http\Controllers\MembershipController::class, 'consumerFirstPreMembershipIndex'])->name('consumer_pre_membership.create.step.one');
    Route::post('consumer/pre-membership/create-step-one', [App\Http\Controllers\MembershipController::class, 'consumerFirstPreMembershipIndex'])->name('consumer_pre_membership.create.step.one.post');
    
    Route::get('consumer/pre-membership/create-step-two', [App\Http\Controllers\MembershipController::class, 'consumerSecondPreMembershipIndex'])->name('consumer_pre_membership.create.step.two');
    Route::post('consumer/pre-membership/create-step-two', [App\Http\Controllers\MembershipController::class, 'consumerSecondPreMembershipIndex'])->name('consumer_pre_membership.create.step.two.post');
    
    Route::get('consumer/pre-membership/create-step-three', [App\Http\Controllers\MembershipController::class, 'consumerThirdPreMembershipIndex'])->name('consumer_pre_membership.create.step.three');
    Route::post('consumer/pre-membership/create-step-three', [App\Http\Controllers\MembershipController::class, 'consumerThirdPreMembershipIndex'])->name('consumer_pre_membership.create.step.three.post');


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

    // roles and permissions
    Route::resource('service-connect-order', App\Http\Controllers\ServiceConnectOrderController::class);

    // lifeline
    Route::resource('lifeline', App\Http\Controllers\LifelineController::class);
    Route::get('fetch-accounts-records', [App\Http\Controllers\LifelineController::class, 'getAccountDetails'])->name('fetchAccounts');
    Route::get('lifeline-approval', [App\Http\Controllers\LifelineController::class, 'approveLifelineIndex'])->name('approvedLifelineIndex');
    Route::put('lifeline-approval/{id}', [App\Http\Controllers\LifelineController::class, 'approveLifelineUpdate'])->name('LifelineUpdate');
});

Route::get('online-pre-membership', [App\Http\Controllers\MembershipController::class, 'onlineSeminarQuestionare'])->name('online.pms')->middleware(['auth', 'verified']);



Route::get('/survey', function () {
    return view('welcome');
});
Route::get('/survey-custcare', function () {
    return view('welcome_custcare');
});
Route::post('store-survey', [App\Http\Controllers\SurveyController::class, 'store']);
Route::post('store-survey-custcare', [App\Http\Controllers\SurveyController::class, 'storeCustcareSurvey'])->name('store.custcare.survey');
Route::post('api/fetch-municipalities', [App\Http\Controllers\AddressController::class, 'fetchMunicipalities']);
Route::post('api/fetch-barangays', [App\Http\Controllers\AddressController::class, 'fetchBarangays']);
