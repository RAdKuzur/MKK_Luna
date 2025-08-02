<?php

use App\Http\Controllers\CompanyApiController;
use App\Http\Middleware\ApiMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware([ApiMiddleware::class])->group(function () {
    Route::get('/companies', [CompanyApiController::class, 'index'])->name('companies.index');
    Route::get('/companies/{id}', [CompanyApiController::class, 'show'])->name('companies.show');
    Route::get('/companies/{id}/buildings', [CompanyApiController::class, 'buildings'])->name('companies.buildings');

    Route::get('/activities/{id}/companies', [CompanyApiController::class, 'companiesByActivity'])->name('activities.companies');
    Route::get('/activities/{id}/companies/with-children', [CompanyApiController::class, 'companiesByParentActivity'])->name('activities.companies.with-children');
});

