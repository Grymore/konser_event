<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ScannerController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\RequestPaymentController;
use App\Http\Controllers\SentEmailController;
use App\Http\Controllers\DownloadTicketController;


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
    return view('index');
});


Route::post('/payment', [RequestPaymentController::class, "payment"]);

Route::get('/redirect/{request}', [CallbackController::class, "callback"]);
Route::get('/print_ticket/{request}', [CallbackController::class, "callback"]);

Route::get('/scanner', [ScannerController::class, "scanner"]);
Route::post('/validasi', [ScannerController::class, "validasi"]);

Route::get('/pesanemail', [SentEmailController::class, "kirimemail"]);


Route::get('/download/{request}', [DownloadTicketController::class, "print"]);

