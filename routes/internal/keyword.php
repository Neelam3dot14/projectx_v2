<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\KeywordController;
use App\Http\Controllers\Internal\KeywordAdsController;

Route::group([
    'namespace' => 'Keyword',
    'as' => 'keyword.',
    'middleware' => ['permission:access_campaigns']
], function () {
    Route::get('/keyword-group/{group_id}/keyword-ads/{id}', [KeywordController::class, 'getAllKeywordAds']);

    Route::group([
        'prefix' => 'keyword',
    ], function () {
        Route::get('/{keyword}', [KeywordController::class, 'show'])
            ->name('keyword.show');

        Route::get('/{keyword}/ads', [KeywordController::class, 'getKeywordAds']);
   
        Route::get('/ads/{id}/trace', [KeywordAdsController::class, 'getAdTraces']);

        Route::get('/{alert_id}/html', [KeywordController::class, 'getKeywordHtml'])
            ->withoutMiddleware(['permission:access_campaigns', 'auth:sanctum', 'verified'])
            ->name('html');
    });
});

