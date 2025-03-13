<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\BlueSnapController;

Route::get('/admin/shoppers', [BlueSnapController::class, 'index'])->name('shoppers.index');

Route::get('/admin/create-shopper', [BlueSnapController::class, 'create'])->name('shoppers.create');



Route::post('/admin/store-shopper', [BlueSnapController::class, 'store'])->name('store.shopper');

Route::post('/admin/update-payment-link', [BlueSnapController::class, 'updatePaymentLink'])->name('update.payment.link');

Route::post('/admin/send-payment-link', [BlueSnapController::class, 'sendPaymentLink'])->name('send.payment.link');


