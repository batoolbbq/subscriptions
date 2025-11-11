<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceAgentsController;

use App\Http\Controllers\CardController;
use App\Http\Controllers\Api\AuthController;



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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
//     // Route::post('/cra/family', [CraController::class, 'lookup'])->name('cra.lookup');

// });


Route::get('/customers', [CardController::class, 'indexApi']);


Route::get('/customers/active', [CustomerController::class, 'getpendingCustomers']);

Route::get('/customers/{uuid}/active', [CustomerController::class, 'getpendingCustomerByUuid']);
Route::get('/customers/{uuid}/inactive', [CustomerController::class, 'getInactiveCustomerByUuid']);


Route::patch('/customers/bulk-activate-to-1', [CustomerController::class, 'bulkActivateToTwo']);

Route::patch('/customers/{uuid}/activate', [CustomerController::class, 'changeCustomerStatus']);


Route::post('/customers/bulk-activate-payment', [CustomerController::class, 'bulkActivateAndPayment']);


Route::post('/customers/{uuid}/activate-payment', [CustomerController::class, 'activateAndPayment']);

Route::post('/main-subscriber/send/{agentId?}', [CustomerController::class, 'postMainSubscriberToApi'])
    ->name('api.main-subscriber.send');


Route::post('/beneficiaries/send', [CustomerController::class, 'postBeneficiariesToApi'])
    ->name('api.beneficiaries.send');

Route::post('/added-service/send/{agentId?}', [InsuranceAgentsController::class, 'postAddedServiceTransactionToApi'])
    ->name('added-service.send');


Route::post('/send-sms', [CustomerController::class, 'sendSms']);
Route::post('/send-otp', [CustomerController::class, 'sendOtp']);