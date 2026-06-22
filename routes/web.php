<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/customer',  [CustomerController::class, 'index'])->name('customers.index');

Route::get('/customer/create', [CustomerController::class, 'create'])->name('customers.create');

Route::post('/customer', [CustomerController::class, 'store'])->name('customers.store');

Route::get('customer/{customer}', [CustomerController::class, 'show'])->name('customers.show');

Route::get('/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');

Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('customers.update');

Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
