<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'Auth'], function () {
    Route::post('login', [AuthController::class, 'Login']);
});


Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::group(['prefix' => 'Invoice'], function () {
            Route::post('/invoices-create', [InvoiceController::class, 'create']);
            Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
        });
    }
);
