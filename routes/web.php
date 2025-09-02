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

use App\Http\Controllers\MunicipalController;
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
    Route::post('/cra/family', [App\Http\Controllers\CustomerController::class, 'lookup'])->name('cra.lookup2');


Route::get('/', function () {
    return view('welcome');
});

    Route::get('/RegisterView', [App\Http\Controllers\CustomerController::class, 'registerCustomerByAdmin2'])->name('register-customerr');
        Route::get('/customers/register/step2', [App\Http\Controllers\CustomerController::class, 'test2'])->name('customers.register.step2');

    Route::get('/agents/performance', [App\Http\Controllers\insuranceperformance::class, 'insuranceData'])
    ->name('agents.performance.index');

   Route::get('/agents/{agent}/services/customers', [App\Http\Controllers\insuranceperformance::class, 'servicesCustomers'])
    ->name('agents.services.customers');


    Route::get('/agents/{agent}/services/institutions',[App\Http\Controllers\insuranceperformance::class, 'servicesInstitutions'])
    ->name('agents.services.institutions');
     
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
    


Auth::routes();

Route::group(['middleware' => ['auth', 'permission']], function () {

Route::resource('roles', RolesController::class);

Route::resource('permissions', PermissionsController::class);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('users/users', [App\Http\Controllers\UserController::class, 'users'])->name('users.users');

Route::resource('users', userController::class);
Route::patch('subscriptions/toggle-status/{id}', [SubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggleStatus');
Route::resource('subscriptions', SubscriptionController::class);


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