<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\InboxApiController;

Route::post('/webhook/fonnte', [WebhookController::class, 'fonnte']);
Route::get('/inbox/{chat_id}/updates', [InboxApiController::class, 'getUpdates']);
Route::get('/notifications', [InboxApiController::class, 'getNotifications']);
