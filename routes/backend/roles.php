<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\RoleController;

Route::group([
    'prefix' => 'role',
    'middleware' => ['permission:access_role_management']
], function () {
    Route::get('/list', [RoleController::class, 'index'])->name('role.list');
    Route::post('/create', [RoleController::class, 'store'])->name('role.create');
    Route::put('/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('role.delete');
    Route::get('/check', [RoleController::class, 'checkRole'])
        ->withoutMiddleware(['permission:view_backend','permission:access_role_management', 'auth:sanctum', 'verified'])
        ->name('role.check');
    /*Route::get('/all', [RoleController::class, 'getAllRoleNames']);
    Route::get('/{id}', [RoleController::class, 'show']);
    
    */
    
});
