<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\GeoTargetController;
use App\Http\Controllers\Internal\ApiController;

Route::group([
    'namespace' => 'Geotarget',
    'as' => 'geotarget.',
    'prefix' => 'geotargets',
    'middleware' => ['permission:access_campaigns']
], function () {
    Route::post('/statelist/default', [GeoTargetController::class, 'getGeoTargetByCountryCode'])
    ->name('state.list.default');

    Route::post('/state/search', [GeoTargetController::class, 'geoTargetSearch'])
    ->name('state.search');

    Route::get('/countrylist', [GeoTargetController::class, 'getCountryList'])
    ->name('country.list');
});
//crawler api
Route::get('/crawler/account/info', [ApiController::class, 'getScraperApiAccDetails'])
    ->name('crawler.api.details');