<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeywordController;
use App\Http\Controllers\Api\KeywordAdsController;

Route::group([
    'namespace' => 'Keyword',
    'as' => 'keyword.',
    'middleware' => ['jwt.verify', 'permission:access_campaigns']
], function () {
    Route::get('/keyword-group/{group_id}/keyword-ads/{id}', [KeywordController::class, 'getAllKeywordAds']);

    Route::group([
        'prefix' => 'keyword',
    ], function () {
        Route::get('/{keyword}', [KeywordController::class, 'show'])
            ->name('keyword.show');
        /*Route::get('/{keyword}/html', [KeywordController::class, 'getKeywordHtml'])
            ->name('keyword.html');*/

        Route::get('/{keyword}/ads', [KeywordController::class, 'getKeywordAds']);
   
        Route::get('/ads/{id}/trace', [KeywordAdsController::class, 'getAdTraces']);
    });
});

