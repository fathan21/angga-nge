<?php

use App\Http\Controllers\Api\Open\JidRegistController;
use App\Http\Controllers\Api\Open\XmppRegistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('V1')->group(function () {
    Route::get('/', function (Request $request) {
        return response(['message' => 'open api V1']);
    });

    Route::get('/xmpp-acccounts/config', [XmppRegistController::class, 'config']);
    Route::post('/xmpp-acccounts/regist', [XmppRegistController::class, 'regist']);
    Route::post('/xmpp-acccounts/reset-password', [XmppRegistController::class, 'resetPassword']);
});
