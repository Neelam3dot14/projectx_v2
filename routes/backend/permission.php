<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\PermissionController;

Route::group([
    'prefix' => 'permission',
    'middleware' => ['permission:access_permission_management']
], function () {
    Route::get('/list', [PermissionController::class, 'index'])->name('permission.list');
    Route::get('/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/create', [PermissionController::class, 'store'])->name('permission.store');
    Route::get('/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');
    Route::get('/all', [PermissionController::class, 'getAllPermissionByName'])->name('permission.all');
});