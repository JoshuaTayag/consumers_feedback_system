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

Route::get('agmm/on-site/registration', [App\Http\Controllers\AgmmController::class, 'agmmRegistration'])->name('agmmRegistration');
Route::post('agmm/register', [App\Http\Controllers\AgmmController::class, 'agmmRegisterPost'])->name('agmmRegisterPost');
Route::get('agmm/qr-print/guest/{id}', [App\Http\Controllers\AgmmController::class, 'printRegistrationQRGuest'])->name('printRegistrationQRGuest');

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
    
    Route::get('agmm/verify', [App\Http\Controllers\AgmmController::class, 'agmmAccounts'])->name('agmmAccounts');
    Route::post('agmm/verify/{id}', [App\Http\Controllers\AgmmController::class, 'agmmVerifyAccount'])->name('agmmVerifyAccount');
    Route::get('agmm/qr-print/{id}', [App\Http\Controllers\AgmmController::class, 'printRegistrationQR'])->name('printRegistrationQR');
    Route::get('agmm/scanner', [App\Http\Controllers\AgmmController::class, 'scanQR'])->name('scanAllowanceQR');
    Route::get('agmm/status', [App\Http\Controllers\AgmmController::class, 'statusCount'])->name('agmmStatus');
    Route::get('agmm/raffle', [App\Http\Controllers\AgmmController::class, 'agmmRaffle'])->name('agmmRaffle');
    Route::get('agmm-issue-transportation-allowance/{id}', [App\Http\Controllers\AgmmController::class, 'issueAllowance'])->name('issueTranspoAllowance');
    Route::get('agmm-transportation-allowance/{id}', [App\Http\Controllers\AgmmController::class, 'getAllowance'])->middleware('auth:sanctum')->name('transpoAllowance');
    Route::get('agmm-registration/from-preregistration/{id}', [App\Http\Controllers\AgmmController::class, 'agmmVerifyAccountFromPreReg'])->middleware('auth:sanctum')->name('verifyPreRegistration');
    Route::get('agmm/raffle/remove/{id}', [App\Http\Controllers\AgmmController::class, 'agmmRaffleRemove'])->name('agmmRaffleRemove');
    Route::get('agmm/online-raffle/remove/{id}', [App\Http\Controllers\AgmmController::class, 'agmmOnlineRaffleRemove'])->name('agmmOnlineRaffleRemove');
    Route::get('/export-onsite-winners', [App\Http\Controllers\AgmmController::class, 'exportOsiteWinners']);
    Route::get('/export-online-winners', [App\Http\Controllers\AgmmController::class, 'exportOnlineWinners']);

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
    Route::get('change-meter-request-index', [App\Http\Controllers\ChangeMeterRequestController::class, 'index'])->name('indexCM');
    Route::get('change-meter-request-create', [App\Http\Controllers\ChangeMeterRequestController::class, 'create'])->name('createCM');
    Route::post('change-meter-request-store', [App\Http\Controllers\ChangeMeterRequestController::class, 'store'])->name('storeCM');
    Route::get('change-meter-request-edit/{id}', [App\Http\Controllers\ChangeMeterRequestController::class, 'edit'])->name('editCM');
    Route::put('change-meter-request-update/{id}', [App\Http\Controllers\ChangeMeterRequestController::class, 'update'])->name('updateCM');
    Route::post('change-meter-request-posting', [App\Http\Controllers\ChangeMeterRequestController::class, 'meterPosting'])->name('meterPostingCM');
    Route::post('change-meter-request-dispatching', [App\Http\Controllers\ChangeMeterRequestController::class, 'cmDispatched'])->name('cmDispatching');
    Route::post('change-meter-request-transfer-dispatching', [App\Http\Controllers\ChangeMeterRequestController::class, 'cmTransferOfDispatching'])->name('cmTransferOfDispatching');
    Route::post('validate-meter-no', [App\Http\Controllers\ChangeMeterRequestController::class, 'validateMeterPosting'])->name('validateMeterPosting');
    Route::get('pdf-sco-cm/{id}', [App\Http\Controllers\ChangeMeterRequestController::class, 'printChangeMeterRequest'])->name('printChangeMeterRequest');
    // Route::get('fetch-applications', [App\Http\Controllers\ServiceConnectOrderController::class, 'fetchServiceConnectApplications'])->name('fetchServiceConnectApplications');
    Route::get('change-meter-request/search', [App\Http\Controllers\ChangeMeterRequestController::class, 'search'])->name('cm.search');
    Route::get('change-meter-request/{id}', [App\Http\Controllers\ChangeMeterRequestController::class, 'destroy'])->name('deleteCM');
    Route::get('change-meter-request-view/{id}', [App\Http\Controllers\ChangeMeterRequestController::class, 'view'])->name('viewCM');
    Route::get('change-meter-request-report', [App\Http\Controllers\ChangeMeterRequestController::class, 'viewReport'])->name('viewReport');
    Route::get('change-meter-request-report/pdf', [App\Http\Controllers\ChangeMeterRequestController::class, 'generateReport'])->name('generateReport');
    Route::get('cm-fetch-accounts-records', [App\Http\Controllers\ChangeMeterRequestController::class, 'getAccountDetails'])->name('cmFetchAccounts');

    Route::resource('change-meter-request', App\Http\Controllers\ChangeMeterRequestController::class);

    // lifeline
    Route::resource('lifeline', App\Http\Controllers\LifelineController::class);
    Route::post('lifeline-store-non-poor', [App\Http\Controllers\LifelineController::class, 'storeNonPoor'])->name('lifeline.store.non_poor');
    Route::get('fetch-accounts-records', [App\Http\Controllers\LifelineController::class, 'getAccountDetailsNotExisting'])->name('fetchAccounts');
    Route::get('fetch-accounts', [App\Http\Controllers\LifelineController::class, 'getAccountDetails'])->name('fetchAllAccounts');
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
    Route::get('fetch-items', [App\Http\Controllers\PowerHouse\DataManagement\Warehousing\StructureController::class, 'fetchItemsFromPrism'])->name('fetchItems');

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
    Route::get('mrf-approval/liquidation', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationApprovalIndex'])->name('mrfLiquidationApprovalIndex');
    Route::put('mrf-approval/liquidation/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationApprovalUpdate'])->name('mrfLiquidationApprovalUpdate');
    Route::get('mrf-approval/liquidation/view/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationApprovalView'])->name('mrfLiquidationApprovalView');
    Route::put('mrf-approval/liquidation/disapproved/{id}', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'mrfLiquidationIadDisapproved'])->name('mrfLiquidationIadDisapproved');
    Route::get('fetch-MER', [App\Http\Controllers\PowerHouse\Warehousing\MaterialRequisitionFormController::class, 'fetchMaterialRequisitionRecords'])->name('fetchMaterialRequisitionRecords');

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
    Route::delete('electrician-activity-delete/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianActivityDelete'])->name('electricianActivityDelete');
    Route::get('electrician-complaint-edit/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintEdit'])->name('electricianComplaintEdit');
    Route::put('electrician-complaint-update/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintUpdate'])->name('electricianComplaintUpdate');
    Route::put('electrician-activity-update/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianActivityUpdate'])->name('electricianActivityUpdate');
    Route::get('electrician-masterlist-report', [App\Http\Controllers\ElectricianController::class, 'electricianMasterlistReport'])->name('electricianMasterlistReport');
    Route::get('electrician-masterlist-report-pdf', [App\Http\Controllers\ElectricianController::class, 'electricianMasterlistReportPdf'])->name('electricianMasterlistReportPdf');
    Route::get('electrician-complaint-view/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianComplaintView'])->name('electricianComplaintView');
    Route::get('fetch-electrician', [App\Http\Controllers\ElectricianController::class, 'fetchElectricianApplication'])->name('fetchElectricianApplication');
    Route::get('electrician-activities', [App\Http\Controllers\ElectricianController::class, 'electricianActivityIndex'])->name('electricianActivityIndex');
    Route::get('electrician-activities/create', [App\Http\Controllers\ElectricianController::class, 'electricianActivityCreate'])->name('electricianActivityCreate');
    Route::get('fetch-electricians', [App\Http\Controllers\ElectricianController::class, 'fetchElectricians'])->name('fetchElectricians');
    Route::post('electrician-activity-store', [App\Http\Controllers\ElectricianController::class, 'electricianActivityStore'])->name('electricianActivityStore');
    Route::get('electrician-activity-edit/{id}', [App\Http\Controllers\ElectricianController::class, 'electricianActivityEdit'])->name('electricianActivityEdit');
    // Strucutures
    // Route::resource('signatory', App\Http\Controllers\SignatoryController::class);

    // LEDGER
    Route::get('ledger', [App\Http\Controllers\AccountLedgerController::class, 'indexLedger'])->name('ledger.index');
    Route::get('ledger/search', [App\Http\Controllers\AccountLedgerController::class, 'searchLedger'])->name('ledger.search');
    Route::get('fetch-accounts-records-by-name', [App\Http\Controllers\AccountLedgerController::class, 'getAccountName'])->name('getAccountName');

    // POWER BILL
    Route::resource('change-meter-request-transact', App\Http\Controllers\ChangeMeterRequestTransactionController::class);
    Route::get('change-meter-request-pay/search', [App\Http\Controllers\ChangeMeterRequestTransactionController::class, 'createCMSearch'])->name('cmTransactionSearch');
    Route::get('change-meter-request-pay/receipt/{id}', [App\Http\Controllers\ChangeMeterRequestTransactionController::class, 'changeMeterReceipt'])->name('changeMeterReceipt');

    Route::resource('payment-transact', App\Http\Controllers\PaymentTransactionController::class);
    // Route::get('change-meter-request-pay/search', [App\Http\Controllers\ChangeMeterRequestTransactionController::class, 'createCMSearch'])->name('cmTransactionSearch');
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
