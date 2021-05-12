<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\KeywordController;

Route::group([
    'namespace' => 'Keyword',
    'as' => 'keyword.',
    'middleware' => ['permission:access_campaigns']
], function () {
    Route::get('/keyword-group/{group_id}/keyword-ads/{id}', [KeywordController::class, 'getAllKeywordAds'])
        ->name('adInstance.view');

    Route::group([
        'prefix' => 'keyword',
    ], function () {
        Route::get('/{keyword}/ads', [KeywordController::class, 'getKeywordAds'])->name('find.ads');
   
        Route::get('/ads/{id}/trace', [KeywordController::class, 'getAdTraces'])->name('ad.traces');

        Route::get('/{alert_id}/html', [KeywordController::class, 'getKeywordHtml'])
            ->withoutMiddleware(['auth:sanctum', 'verified', 'permission:view_internal', 'permission:access_campaigns'])
            ->name('html');
    });
});

