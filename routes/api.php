<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CardController;


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
// Route::get('/customers/inactive', [CustomerController::class, 'getInactiveCustomers']);




// كستمر واحد بالـ uuid + active condition
Route::get('/customers/{uuid}/active', [CustomerController::class, 'getpendingCustomerByUuid']);
Route::get('/customers/{uuid}/inactive', [CustomerController::class, 'getInactiveCustomerByUuid']);


Route::patch('/customers/bulk-activate-to-1', [CustomerController::class, 'bulkActivateToTwo']);

Route::patch('/customers/{uuid}/activate-to-1', [CustomerController::class, 'activateToTwo']);


Route::post('/customers/bulk-activate-payment', [CustomerController::class, 'bulkActivateAndPayment']);


Route::post('/customers/{uuid}/activate-payment', [CustomerController::class, 'activateAndPayment']);





