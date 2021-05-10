<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\CampaignController;


Route::group([
    'namespace' => 'Campaign',
    'as' => 'campaign.',
    //'middleware' => ['jwt.verify', 'permission:access_campaigns']
], function () {
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('list');

    Route::get('/campaigns/export', [CampaignController::class, 'exportAll'])->name('campaign.export.all');

    Route::get('/campaigns/export/all', [CampaignController::class, 'exportAllTest']);

    Route::group([
        'prefix' => 'campaign'
    ], function () {
        Route::get('/create', [CampaignController::class, 'create'])
            ->name('create');

        Route::post('/create', [CampaignController::class, 'store'])
            ->name('store');

        Route::get('/{campaign}', [CampaignController::class, 'show'])
            ->name('edit');

        Route::put('/{campaign}', [CampaignController::class, 'update'])
            ->name('update');

        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])
            ->name('delete');

        Route::post('/{campaign}/execute', [CampaignController::class, 'execute'])
            ->name('execute');
        
        Route::get('/{campaign}/pause', [CampaignController::class, 'pause'])
            ->name('pause');

        Route::get('/{campaign}/reactivate', [CampaignController::class, 'reActivate'])
            ->name('reactivate');

        Route::get('/{campaign}/export', [CampaignController::class, 'export'])
            ->name('export');
        
        Route::get('/{campaign}/exporttest', [CampaignController::class, 'exportTestCamp'])
            ->name('exporttest');

        Route::get('/{campaign}/keyword', [CampaignController::class, 'getCampaignKeywords'])
            ->name('keyword');
    });
});
