<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\UserController;

Route::group([
    'prefix' => 'user',
    'middleware' => ['permission:read_users']
], function () {
    Route::get('/list', [UserController::class, 'index'])->name('user.list');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/create', [UserController::class, 'store'])->name('user.store');
    //Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update'])
        ->middleware('permission:write_user')
        ->name('user.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:write_user')
        ->name('user.delete');
    /*Route::get('/impersonate/{id}', [UserController::class, 'impersonate'])
        ->middleware('permission:impersonate_users')
        ->name('user.impersonate');*/
});
