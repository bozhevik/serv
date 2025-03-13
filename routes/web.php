<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;
use Illuminate\Http\Request; 

Route::prefix('info')->group(function () {
    Route::get('/server', [InfoController::class, 'server']);
    
    Route::get('/client', function (Request $request) {
        return response()->json([
            'ip' => $request->ip(),
            'useragent' => $request->userAgent(),
        ]);
    });

    Route::get('/database', [InfoController::class, 'database']);
});


