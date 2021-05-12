<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\KeywordAdsController;

Route::group([
    'namespace' => 'Ads',
    'as' => 'ads.',
    'middleware' => ['permission:access_campaigns']
], function () {
    Route::group([
        'prefix' => 'ads',
    ], function () {
        Route::get('/{campaign_id}/hijack', [KeywordAdsController::class, 'getAdHijacks'])
            ->name('hijack');
        Route::get('/{ad_id}/hijack/traces', [KeywordAdsController::class, 'getAdHijackTraces'])
            ->name('hijack.traces');
        Route::get('/{campaign_id}/competitor', [KeywordAdsController::class, 'getAdCompetitors'])
            ->name('competitor');
    });
});

