<?php

use App\Http\Controllers\Webhooks\{DealsController, LeadsController};

Route::group([
    'prefix' => 'webhooks/pipedrive',
    'middleware' => ['auth.token'],
], function () {
    Route::post('deals/updated', [DealsController::class, 'updated']);
});

Route::group([
    'prefix' => 'webhooks/leads',
    'middleware' => ['auth.token'],
], function () {
    Route::post('leadgen', [LeadsController::class, 'leadgen']);

    Route::get('facebook', [LeadsController::class, 'facebook']); // TODO for start webhook
    Route::post('facebook', [LeadsController::class, 'facebook']);

    Route::post('intercom', [LeadsController::class, 'intercom']);
});
