<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CircularController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ImpLinksController;
use App\Http\Controllers\LatestUpdateController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\OfficersController;
use App\Http\Controllers\GovCouncilController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\EventVidController;
use App\Http\Controllers\HomemenuController;
use App\Http\Controllers\MapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login'])->name('postlogin');

Route::middleware(['auth:api'])->group(function () {
    Route::get('whoAmI', [AuthController::class, 'index'])->name('whoAmI');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/latestUpdates', [LatestUpdateController::class, 'index']);
    Route::post('/latestUpdates', [LatestUpdateController::class, 'store']);
    Route::delete('/latestUpdates', [LatestUpdateController::class, 'delete']);
    Route::post('/latestUpdates/disable', [LatestUpdateController::class, 'disable']);
    Route::post('/latestUpdates/edit', [LatestUpdateController::class, 'edit']);

    Route::get('/photo', [PhotoController::class, 'index']);
    Route::post('/photo', [PhotoController::class, 'store']);
    Route::delete('/photo/{id}', [PhotoController::class, 'destroy']);
    Route::post('/photo/update', [PhotoController::class, 'edit']);
    Route::post('/photo/disable', [PhotoController::class, 'disable']);

    Route::get('/circulars', [CircularController::class, 'index']);
    Route::post('/circulars', [CircularController::class, 'store']);
    Route::delete('/circulars', [CircularController::class, 'delete']);
    Route::put('/circulars', [CircularController::class, 'edit']);
    Route::post('/circulars/disable', [CircularController::class, 'disable']);
    Route::post('/circulars/edit', [CircularController::class, 'edit']);

    Route::get('/impLinks', [ImpLinksController::class, 'index']);
    Route::post('/impLinks', [ImpLinksController::class, 'store']);
    Route::delete('/impLinks', [ImpLinksController::class, 'delete']);
    Route::put('/impLinks', [ImpLinksController::class, 'edit']);
    Route::post('/impLinks/disable', [ImpLinksController::class, 'disable']);

    Route::get('/events', [EventsController::class, 'index']);
    Route::post('/events', [EventsController::class, 'store']);
    Route::delete('/events', [EventsController::class, 'delete']);
    Route::put('/events', [EventsController::class, 'edit']);

    Route::get('/Officers', [OfficersController::class, 'index']);
    Route::post('/Officers', [OfficersController::class, 'store']);
    Route::delete('/Officers/{id}', [OfficersController::class, 'destroy']);
    Route::post('/Officers/update', [OfficersController::class, 'edit']);
    Route::post('/Officers/disable', [OfficersController::class, 'disable']);

    Route::get('/councils', [GovCouncilController::class, 'index']);
    Route::post('/councils', [GovCouncilController::class, 'store']);
    Route::delete('/councils', [GovCouncilController::class, 'delete']);
    Route::post('/councils/update', [GovCouncilController::class, 'edit']);
    Route::post('/councils/disable', [GovCouncilController::class, 'disable']);

    Route::get('/boards', [BoardController::class, 'index']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::delete('/boards', [BoardController::class, 'delete']);
    Route::post('/boards/update', [BoardController::class, 'edit']);
    Route::post('/boards/disable', [BoardController::class, 'disable']);

    Route::get('/EventVideos', [EventVidController::class, 'index']);
    Route::post('/EventVideos', [EventVidController::class, 'store']);
    Route::delete('/EventVideos', [EventVidController::class, 'delete']);
    Route::post('/EventVideos/update', [EventVidController::class, 'edit']);
    Route::post('/EventVideos/disable', [EventVidController::class, 'disable']);

    Route::get('/homemenu', [HomemenuController::class, 'index']);
    Route::post('/homemenu', [HomemenuController::class, 'store']);
    Route::delete('/homemenu', [HomemenuController::class, 'delete']);
    Route::put('/homemenu', [HomemenuController::class, 'edit']);
    Route::post('/homemenu/disable', [HomemenuController::class, 'disable']);

    Route::post('/events/update', [EventsController::class, 'edit']);
    Route::post('/events/disable', [EventsController::class, 'disable']);

    Route::get('/search', [CircularController::class, 'search']);
    Route::get('/search', [ImpLinksController::class, 'search']);

    Route::get('/map', [MapController::class, 'index']);
});
