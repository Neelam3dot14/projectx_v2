<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeywordAdsController;

Route::group([
    'namespace' => 'Ads',
    'as' => 'ads.',
    'middleware' => ['jwt.verify', 'permission:access_campaigns']
], function () {
    Route::group([
        'prefix' => 'ads',
    ], function () {
        Route::get('/{campaign_id}/hijack', [KeywordAdsController::class, 'getAdHijacks'])
            ->name('ad.hijack');
        Route::get('/{ad_id}/hijack/traces', [KeywordAdsController::class, 'getAdHijackTraces'])
            ->name('ad.hijack.traces');
        Route::get('/{campaign_id}/competitor', [KeywordAdsController::class, 'getAdCompetitors'])
            ->name('ad.competitor');
    });
});

