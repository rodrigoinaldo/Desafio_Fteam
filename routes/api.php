<?php

use App\Http\Controllers\Integracao\FakeStoreController;
use App\Http\Controllers\Integracao\CatalogController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Testing\Fakes\Fake;

Route::middleware('integracao')->group(function () {

    Route::get('/fake-store', [FakeStoreController::class, 'allEstoque']);

    Route::get('/produtos', [CatalogController::class, 'index']);
    Route::get('/produtos/{id}', [CatalogController::class, 'show']);

});