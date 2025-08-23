<?php

use App\Http\Controllers\Integracao\FakeStoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Testing\Fakes\Fake;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/fake-store', [FakeStoreController::class, 'novo']);
Route::get('/teste', [FakeStoreController::class, 'teste']);

Route::middleware('integracao')->group(function () {

});