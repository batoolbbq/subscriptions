<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\InsuranceAgentsController;
use App\Http\Controllers\beneficiarieSupCategoryController;
use App\Http\Controllers\beneficiariesCategoriesController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\insuranceperformance;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MunicipalController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\WorkplaceCodeController;

use Illuminate\Support\Facades\Validator;



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



// Route::get('/cities/send-to-api', [CityController::class, 'sendCitiesToApi'])
//     ->name('cities.sendToApi');



    Auth::routes();

   
    Route::resource('workplace_codes', \App\Http\Controllers\WorkplaceCodeController::class);

    Route::get('create-child', [WorkplaceCodeController::class, 'createChild'])->name('workplace_codes.create_child');
    Route::post('workplace_codes', [WorkplaceCodeController::class, 'store'])->name('workplace_codes.store');

    Route::get('/workplace-codes/{parent}/children', [WorkplaceCodeController::class, 'children'])->name('workplace_codes.children');

    Route::post('/institucion/storefromsubscriberview', [App\Http\Controllers\InstitucionController::class, 'storefromsubscriberview'])
    ->name('institucion.storefromsubscriberview');

    Route::post('/search/customers', [CustomerController::class, 'searchUnified'])
        ->name('search.customers');
    Route::post('/cra/family', [App\Http\Controllers\CustomerController::class, 'lookup'])->name('cra.lookup2');


    Route::post('/verify-otp', [CustomerController::class, 'verifyOtp'])
    ->name('verify.otp');

    Route::get('/customers/lookup', [CustomerController::class, 'showLookupForm'])->name('customers.lookup');
    Route::post('/customers/lookup-do', [CustomerController::class, 'doLookup'])->name('customers.get');
    Route::get('/customers/{customer}/print-card', [CustomerController::class, 'printCard'])->name('customers.print.card');


    Route::get('/customers/search', [CustomerController::class, 'searchForm'])
        ->name('customers.search.form');

     Route::post('/send-otp', [App\Http\Controllers\CustomerController::class, 'sendOtps'])
     ->name('customers.send-otp');


        Route::get('/customers/searchEditForm', [App\Http\Controllers\CustomerController::class, 'searchEditForm'])->name('customer.searchEditForm');
        Route::post('/customers/search', [App\Http\Controllers\CustomerController::class, 'searchEdit'])->name('customer.searchEdit');
        Route::get('/customer/{id}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customer.edit');
        Route::put('/customer/{id}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customer.update');
        Route::get('/customers/{customer}/print-one', [App\Http\Controllers\CustomerController::class, 'printOne'])
        ->name('customer.print-one');

             Route::get('/customers/{customer}/fakad', [App\Http\Controllers\CustomerController::class, 'fakad'])
        ->name('customer.fakad');

        Route::get('/customers/show/{id}', [App\Http\Controllers\CustomerController::class, 'show'])->name('customers.show');

        Route::get('/customers/{customer}/print-all', [App\Http\Controllers\CustomerController::class, 'printAll'])
         ->name('customers.printAll');

    


    Route::get('/RegisterView', [App\Http\Controllers\CustomerController::class, 'registerCustomerByAdmin2'])->name('register-customerr');
        Route::get('/customers/register/step2', [App\Http\Controllers\CustomerController::class, 'test2'])->name('customers.register.step2');

    Route::get('/agents/performance', [App\Http\Controllers\insuranceperformance::class, 'insuranceData'])
    ->name('agents.performance.index');

   Route::get('/agents/{agent}/services/customers', [App\Http\Controllers\insuranceperformance::class, 'servicesCustomers'])
    ->name('agents.services.customers');


    Route::get('/agents/{agent}/services/institutions',[App\Http\Controllers\insuranceperformance::class, 'servicesInstitutions'])
    ->name('agents.services.institutions');
    //   Route::post('institucions/{institucion}/transfer-customers', [\App\Http\Controllers\InstitucionController::class, 'transferCustomers'])
    // ->name('institucions.transfer-customers');

    // صفحة اختيار الجهة للنقل
Route::get('institucions/{institucion}/transfer', [\App\Http\Controllers\InstitucionController::class, 'transferView'])
    ->name('institucions.transferview');

// تنفيذ عملية النقل
Route::post('institucions/{institucion}/transfer', [\App\Http\Controllers\InstitucionController::class, 'transferStore'])
    ->name('institucions.transferstore');


    

    // // routes/web.php
    // Route::get('/agents/performance/search', [App\Http\Controllers\insuranceperformance::class, 'searchForm'])
    // ->name('agents.performance.search');

     
    // Route::get('/agents/performance/logs', [insuranceperformance::class, 'logs'])
    //     ->name('agents.performance.logs');


    Route::get('/banks/{bank}/branches', function($bankId) {
    return \App\Models\BankBranch::where('bank_id', $bankId)->distinct('name')->get(['id','name']);
    })->name('banks.branches');

    Route::post('/customers/register/step3', [App\Http\Controllers\CustomerController::class, 'test44'])->name('customers.register.step3');

    Route::post('/CheckCustomer', [App\Http\Controllers\CustomerController::class, 'test'])->name('check-customer');
    // Route::post('/StoreCustomer', [App\Http\Controllers\CustomerController::class, 'saveCustomersByAdmin'])->name('store-customer');

Route::get('/sendotp/{phone}', [App\Http\Controllers\CustomerController::class, 'OTP'])->name('send-otp');
Route::get('/insurance-agents/create', [InsuranceAgentsController::class, 'create'])
    ->name('insuranceAgents.create');

    Route::post('/insurance-agents/store', [InsuranceAgentsController::class, 'store'])
    ->name('insuranceAgents.store');
   Route::get('/municipals/by-city/{city}', [MunicipalController::class, 'byCity'])
     ->name('municipals.byCity');
    



      //----------------------------cards-----------------------------------------------------------------       
    Route::get('cards/index', [App\Http\Controllers\CardController::class, 'index'])->name('cards/index');
    Route::get('cards/cardsetting', [App\Http\Controllers\CardController::class, 'editcard'])->name('cards/cardsetting');
    Route::post('cards/index', [App\Http\Controllers\CardController::class, 'quary'])->name('cards/quary');
    Route::post('cards/store/{id}', [App\Http\Controllers\CardController::class, 'store'])->name('cards/store');
    Route::post('cards/printed/{id}', [App\Http\Controllers\CardController::class, 'printed'])->name('cards/printed');
    Route::post('cards/allowPrint/{id}', [App\Http\Controllers\CardController::class, 'allowPrint'])->name('print_allowed');
    // Route::post('cards/storesv/{id}', [App\Http\Controllers\Dashbord\CardController::class, 'storephotosave'])->name('cards/storesv');



Route::group(['middleware' => ['auth', 'permission']], function () {

Route::resource('roles', RolesController::class);

Route::resource('permissions', PermissionsController::class);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home2');

Route::get('users/users', [App\Http\Controllers\UserController::class, 'users'])->name('users.users');

Route::resource('users', userController::class);
Route::patch('subscriptions/toggle-status/{id}', [SubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggleStatus');
Route::resource('subscriptions', SubscriptionController::class);
 
    // Route::get('/', function () {
    //     return view('welcome');
    // });

//=================================================== InsuranceAgents ======================================================================================


// عرض الصفحة الرئيسية للوكلاء


Route::get('/insurance-agents', [InsuranceAgentsController::class, 'index'])
    ->name('insuranceAgents.index');

// API لجلب بيانات الوكلاء للـ DataTables
Route::get('/insurance-agents/data', [InsuranceAgentsController::class, 'get_index'])
    ->name('InsuranceAgents-get-index');

     Route::post('/insurance-agents/{id}/activate', [InsuranceAgentsController::class, 'activate'])
        ->name('insurance-agents.activate');

// عرض وكيل واحد
Route::get('/insurance-agents/{id}', [InsuranceAgentsController::class, 'show'])
    ->name('InsuranceAgents-show');

 Route::get('/insurance-agents/{id}/edit', [InsuranceAgentsController::class, 'edit'])
    ->name('insuranceAgents-edit');

   Route::put('/insurance-agents/{id}', [InsuranceAgentsController::class, 'update'])
    ->name('insuranceAgents.update');

    Route::post('/insurance-agents/{id}/deactivate', [InsuranceAgentsController::class, 'deactivate'])
    ->name('insurance-agents.deactivate');





// // إنشاء وكيل جديد
// Route::get('/insurance-agents/create', [InsuranceAgentsController::class, 'create'])
//     ->name('insuranceAgents.create');
// Route::post('/insurance-agents', [InsuranceAgentsController::class, 'store'])
//     ->name('insuranceAgents.store');

// تفعيل وكيل
Route::post('/insurance-agents/{id}/activate', [InsuranceAgentsController::class, 'activate'])
    ->name('insurance-agents.activate');

// إلغاء تفعيل وكيل
Route::post('/insurance-agents/{id}/deactivate', [InsuranceAgentsController::class, 'deactivate'])
    ->name('insurance-agents.deactivate');

    Route::resource('beneficiariescategory', beneficiariesCategoriesController::class);
    Route::resource('beneficiaries-sup-categories', beneficiarieSupCategoryController::class);

    Route::resource('institucions', \App\Http\Controllers\InstitucionController::class);
 

Route::patch('institucions/{institucion}/toggle-status', [\App\Http\Controllers\InstitucionController::class, 'toggleStatus'])
    ->name('institucions.toggle-status');

  

});