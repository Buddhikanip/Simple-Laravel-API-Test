<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'suppliers'], function () {

    // save supplier
    Route::post('/', [SupplierController::class, 'store']);

    // get all suppliers
    Route::get('/', [SupplierController::class, 'index']);

    // search suppliers
    Route::get('/search', [SupplierController::class, 'search']);

    // get supplier by id
    Route::get('/{id}', [SupplierController::class, 'get']);

    // update supplier
    Route::post('/{id}', [SupplierController::class, 'update']);

    // delete supplier
    Route::delete('/{id}', [SupplierController::class, 'destroy']);


});

Route::get('/{pathMatch}', function () {
    return view('welcome');
})->where('pathMatch', '.*');
