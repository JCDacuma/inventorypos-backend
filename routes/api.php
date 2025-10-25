<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductUnitController;
use App\Http\Controllers\ProductSupplierController;
use App\Http\Controllers\BatchStockController;

//inventory
Route::get('getproduct{id}/stocks', [BatchStockController::class, 'getStockBatchById']);
Route::get('get-inventory', [BatchStockController::class, 'getInventoryDisplay']);


//ProducSupplier pivot
Route::post('bulk-supplier-unnasign',[ProductSupplierController::class, 'bulkSupplierUnnasign']); //updating
Route::post('bulk/supplier-assign',[ProductSupplierController::class, 'bulkSupplierAssign']); //updating
Route::post('supplier-unnasign', [ProductSupplierController::class, 'unassignProductToSupplier']); //updating
Route::post('supplier-assign', [ProductSupplierController::class, 'AssignProductToSupplier']); //updating
Route::post('products/suppliers/bulk', [ProductSupplierController::class, 'supplierFromBulkProduct']); //getting
Route::get('get/{id}/unnasigned-supplier' , [ProductSupplierController::class, 'getUnassignedSuppliers']); //getting
Route::get('product/{id}/supplier', [ProductSupplierController::class ,'getSupplierFromProduct']); //getting

//product
Route::patch('product-batchupdate', [ProductController::class, 'batchupdate']);
Route::post('product-get/{id}', [ProductController::class, 'getEditProduct']);
Route::apiResource('product', ProductController::class);

//Product unit
Route::patch('productunit-delete/{id}', [ProductUnitController::class, 'softdelete']);
Route::apiResource('productunit', ProductUnitController::class);

//product category
Route::patch('productcategory-delete/{id}', [ProductCategoryController::class, 'softdelete']);
Route::apiResource('category', ProductCategoryController::class);

//supplier
Route::post('supplier/check-supliername', [SupplierController::class, 'checkSuppliername']); //Check supplier
Route::post('supplier/get-supplier', [SupplierController::class, 'getEditSupplier']); //get supplier by id
Route::patch('supplier/bulk-update', [SupplierController::class, 'bulkUpdate']);
Route::apiResource('supplier', SupplierController::class ); //crud Supplier

//supplier contact
Route::apiResource('contact', ContactController::class); //crud Contact

//account
Route::apiResource('account', UserController::class); //crud role
Route::post('account/check-email', [UserController::class, 'checkemail']); //checking availability email
Route::post('account/check-username', [UserController::class, 'checkusername']); //checking availability username
//account role
Route::apiResource('roles',RoleController::class); //crud role
Route::post('roles/check-rolename', [RoleController::class, 'checkRole']); //checking availability role

