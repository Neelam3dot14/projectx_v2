<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\PermissionController;

Route::group([
    'prefix' => 'permission',
    //'middleware' => ['permission:access_permission_management']
], function () {
    Route::get('/list', [PermissionController::class, 'index'])->name('permission.list');
    Route::post('/create', [PermissionController::class, 'store'])
        ->name('permission.create');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');
    /*Route::get('/all', [PermissionController::class, 'getAllPermissionByName']);
    
    Route::get('/{id}', [PermissionController::class, 'show']);
    
    */
});