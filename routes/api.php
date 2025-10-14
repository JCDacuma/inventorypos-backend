<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductCategoryController;

Route::apiResource('category', ProductCategoryController::class);

Route::post('supplier/check-supliername', [SupplierController::class, 'checkSuppliername']); //Check supplier
Route::post('supplier/get-supplier', [SupplierController::class, 'getEditSupplier']); //get supplier by id
Route::patch('supplier/bulk-update', [SupplierController::class, 'bulkUpdate']);
Route::apiResource('supplier', SupplierController::class ); //crud Supplier

Route::apiResource('contact', ContactController::class); //crud Contact

Route::apiResource('account', UserController::class); //crud role
Route::post('account/check-email', [UserController::class, 'checkemail']); //checking availability email
Route::post('account/check-username', [UserController::class, 'checkusername']); //checking availability username

Route::apiResource('roles',RoleController::class); //crud role
Route::post('roles/check-rolename', [RoleController::class, 'checkRole']); //checking availability role

