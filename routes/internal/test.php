<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;

Route::group([
    'prefix' => 'tada'
], function () {
    Route::get('/testee', [TestController::class, 'test']);
    Route::get('/testrandom', [TestController::class, 'testRandom']);
    Route::get('/testagent', [TestController::class, 'getRandomUserAgent']);
    Route::get('/testEvent', [TestController::class, 'basic_email']);

});

