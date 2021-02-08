<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\MailListController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('mail')->group(function () {
    Route::post('/send', [EmailController::class, 'queue']);
    Route::get('/transactions', [EmailController::class, 'getAll']);
    Route::get('/transactions/{id}', [EmailController::class, 'getDetails']);
});

Route::prefix('admin')->group(function () {
    Route::post('/template', [TemplateController::class, 'create']);
    Route::put('/template/{id}', [TemplateController::class, 'edit']);
    Route::get('/transactions', [TransactionController::class, 'getAll']);
    Route::get('/transaction/{id}', [TransactionController::class, 'getDetails']);
    Route::get('/transactions/metrics', [TransactionController::class, 'getMetrics']);
    Route::post('/mail-list', [MailListController::class, 'create']);
    Route::put('/mail-list/{id}', [MailListController::class, 'edit']);
    Route::delete('/mail-list/{id}', [MailListController::class, 'delete']);
    Route::get('/mail-list/filter', [MailListController::class, 'filter']);
});

Route::prefix('transaction')->group(function () {
    Route::post('/', [TransactionController::class, 'create']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
