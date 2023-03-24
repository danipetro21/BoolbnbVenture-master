<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserPropertyController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

//prende le proprietà ordinando prima le sponsorizzate rispetto alle non sponsorizzate
Route::get('v1/sponsored', [PropertyController::class, 'getSponsorProperties']);

//prende solo le proprietà sponsorizzate
Route::get('v1/sponsored', [PropertyController::class, 'getSponsorProperties']);

// Mostra le proprieta' del utente loggato nella dashboard
Route::get('v1/{id}/properties', [UserPropertyController::class, 'getUserProperties']);

// tutte le sponsorship
Route::get('v1/sponsorship/{property_id}', [PropertyController::class, 'getSponsorships']);

// sponsorships associate a proprietà
Route::get('v1/sponsorships/{property_id}', [PropertyController::class, 'getPropertySponsorships']);

//sponsorship property associate 
Route::post('v1/sponsorship/buy', [PropertyController::class, 'buySponsorship']);

//messaggi
Route::get('v1/messages/{property_id}', [PropertyController::class, 'getPropertyMessages']);

route::post("v1/messages/store/{propid}" ,[PropertyController::class, 'storeMessage']);

// braintree test

Route::get('v1/token', [PaymentController::class, 'getToken']);

Route::post('v1/transaction/create', [PaymentController::class, 'createTransaction']);

// test stats

Route::get('v1/stats/{propid}', [PropertyController::class, 'getStats']);
