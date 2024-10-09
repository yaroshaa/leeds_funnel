<?php

use App\Http\Controllers\Web\{LeadsController};

Route::any('dd', function (\Illuminate\Http\Request $request) {
    dd(

    );
});

Route::get('/', [LeadsController::class, 'pipedrive'])->name('leads.pipedrive');
Route::get('/channels', [LeadsController::class, 'channels'])->name('leads.channels');
