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

    // employees
    Route::resource('employee', App\Http\Controllers\EmployeeController::class);

    // roles and permissions
    Route::resource('teller', App\Http\Controllers\PowerBill\TellerController::class);

    // roles and permissions
    Route::resource('service-connect-order', App\Http\Controllers\ServiceConnectOrderController::class);
    Route::get('service-connect-order-cm', [App\Http\Controllers\ServiceConnectOrderController::class, 'indexCM'])->name('indexCM');
    Route::get('service-connect-order-cm-create', [App\Http\Controllers\ServiceConnectOrderController::class, 'createCM'])->name('createCM');
    Route::post('service-connect-order-cm-store', [App\Http\Controllers\ServiceConnectOrderController::class, 'storeCM'])->name('storeCM');
    Route::get('service-connect-order-cm-edit/{id}', [App\Http\Controllers\ServiceConnectOrderController::class, 'editCM'])->name('editCM');
    Route::put('service-connect-order-cm-update/{id}', [App\Http\Controllers\ServiceConnectOrderController::class, 'updateCM'])->name('updateCM');
    Route::post('service-connect-order-post-meter', [App\Http\Controllers\ServiceConnectOrderController::class, 'meterPostingCM'])->name('meterPostingCM');
    Route::post('validate-meter-no', [App\Http\Controllers\ServiceConnectOrderController::class, 'validateMeterPosting'])->name('validateMeterPosting');
    Route::get('pdf-sco-cm/{id}', [App\Http\Controllers\ServiceConnectOrderController::class, 'printChangeMeterRequest'])->name('printChangeMeterRequest');
    // Route::get('fetch-applications', [App\Http\Controllers\ServiceConnectOrderController::class, 'fetchServiceConnectApplications'])->name('fetchServiceConnectApplications');
    Route::get('service-connect-order-cm/search', [App\Http\Controllers\ServiceConnectOrderController::class, 'searchCM'])->name('cm.search');

    // lifeline
    Route::resource('lifeline', App\Http\Controllers\LifelineController::class);
    Route::post('lifeline-store-non-poor', [App\Http\Controllers\LifelineController::class, 'storeNonPoor'])->name('lifeline.store.non_poor');
    Route::get('fetch-accounts-records', [App\Http\Controllers\LifelineController::class, 'getAccountDetails'])->name('fetchAccounts');
    Route::get('lifeline-approval', [App\Http\Controllers\LifelineController::class, 'approveLifelineIndex'])->name('approvedLifelineIndex');
    Route::put('lifeline-approval/{id}', [App\Http\Controllers\LifelineController::class, 'approveLifeline'])->name('LifelineUpdate');
    Route::put('lifeline-approval', [App\Http\Controllers\LifelineController::class, 'approveLifelineMultiple'])->name('LifelineMassUpdate');
    Route::get('lifeline-generate-pdf/{id}', [App\Http\Controllers\LifelineController::class, 'lifelineCoverageCertificate'])->name('lifelineCoverageCertificate');
    Route::get('lifeline-report', [App\Http\Controllers\LifelineController::class, 'lifelineReport'])->name('lifeline.report');
    Route::get('lifeline-report-generate', [App\Http\Controllers\LifelineController::class, 'lifelineGenerateReport'])->name('lifeline.generate.report');
    Route::get('fetch-lifelines', [App\Http\Controllers\LifelineController::class, 'fetchLifelineApplication'])->name('fetchLifelineApplication');
    Route::get('upload-lifelines', [App\Http\Controllers\LifelineController::class, 'uploadLifeline'])->name('uploadLifeline');

    // Strucutures
    Route::resource('structure', App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class);
    Route::get('fetch-items', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'fetchItemsFromCmbis'])->name('fetchItems');

    // TEMP Structures
    Route::post('edit-structure-items', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'updateStructureItem'])->name('updateStructureItem');
    Route::post('temp-structure-edit-quantity', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'structureUpdateQuantity'])->name('structureUpdateQuantity');
    Route::post('temp-structure-edit-cost', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'structureUpdateCost'])->name('structureUpdateCost');
    Route::delete('temp-structure-delete-item', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'structureDeleteItem'])->name('structureDeleteItem');

    // MRF
    Route::resource('material-requisition-form', App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class);
    Route::get('fetch-materials', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'getItems'])->name('getItems');
    Route::post('mrf-edit-materials', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfUpdateItems'])->name('mrfUpdateItems');
    Route::post('mrf-edit-material', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfUpdateItem'])->name('mrfUpdateItem');
    Route::post('mrf-edit-material-code', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfUpdateItemCode'])->name('mrfUpdateItemCode');
    Route::post('mrf-edit-material-quantity', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfUpdateItemQuantity'])->name('mrfUpdateItemQuantity');
    Route::post('mrf-edit-material-cost', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfUpdateItemCost'])->name('mrfUpdateItemCost');
    Route::delete('mrf-delete-material', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfDeleteItem'])->name('mrfDeleteItem');
    Route::get('mrf-approval', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfApprovalIndex'])->name('mrfApprovalIndex');
    Route::put('mrf-approval/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfApprovalUpdate'])->name('mrfApprovalUpdate');
    Route::get('mrf-print-pdf/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfPrintPdf'])->name('mrfPrintPdf');
    Route::get('mrf-liquidate/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidate'])->name('mrfLiquidate');
    Route::get('fetch-mrv-records', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'getMrvs'])->name('fetchMrvs');
    Route::get('fetch-seriv-records', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'getSerivs'])->name('fetchSerivs');
    Route::put('mrf-create-liquidation/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationCreate'])->name('mrfLiquidationCreate');
    Route::get('view-liquidated-mrf/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'viewLiquidatedMrf'])->name('viewLiquidatedMrf');
    Route::get('mrf-liquidation-report-pdf', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationReport'])->name('mrfLiquidationReport');


    //TEMP MRF
    Route::post('edit-materials', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'updateItems'])->name('updateItems');
    Route::post('edit-material', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'updateItem'])->name('updateItem');
    Route::post('edit-material-code', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'updateItemCode'])->name('updateItemCode');
    Route::post('edit-material-quantity', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'updateItemQuantity'])->name('updateItemQuantity');
    Route::post('edit-material-cost', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'updateItemCost'])->name('updateItemCost');
    Route::delete('delete-material', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'deleteItem'])->name('removeItem');

    // ELECTRICIAN
    Route::resource('electrician', App\Http\Controllers\ElectricianController::class);
    Route::get('electrician-complaints', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintIndex'])->name('electricianComplaintIndex');
    Route::get('electrician-complaint-create', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintCreate'])->name('electricianComplaintCreate');
    Route::post('electrician-complaint-store', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintStore'])->name('electricianComplaintStore');
    Route::delete('electrician-complaint-delete/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintDelete'])->name('electricianComplaintDelete');
    Route::get('electrician-complaint-edit/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintEdit'])->name('electricianComplaintEdit');
    Route::put('electrician-complaint-update/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintUpdate'])->name('electricianComplaintUpdate');
    Route::get('electrician-complaint-view/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintView'])->name('electricianComplaintView');
    Route::get('fetch-electrician', [App\Http\Controllers\ElectricianController::class, 'fetchElectricianApplication'])->name('fetchElectricianApplication');
    // Strucutures
    // Route::resource('signatory', App\Http\Controllers\SignatoryController::class);
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
