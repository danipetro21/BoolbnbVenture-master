<?php
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserPropertyController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/properties/update-lat-lon', [SearchController::class, 'updatePropertiesLatLon']);

Route::post('/search', [SearchController::class, 'search'])->name('property.search');

// Route::get('/properties/update-address', [SearchController::class, 'updatePropertiesAddress']);



Route::get('/search', function () {
  return Inertia::render('Properties/PropertySearch');
})->name('property.search.index');

// DASHBOARD
Route::get('/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SPONSORSHIP PAGE
Route::get('/sponsorship/{propid}', function ($propid) {
  return Inertia::render('Properties/Sponsorship', ['propid' => $propid]);
})->middleware(['auth', 'verified'])->name('prop.sponsorship');

// STATS PAGE
Route::get('/stats/{propid}', function ($propid) {
  return Inertia::render('Properties/Stats', ['propid' => $propid]);
})->middleware(['auth', 'verified'])->name('prop.stats');

// SHOW
Route::get('property/{id}', [PropertyController::class, 'propertyShow'])->name('property.show')->where('id', '[0-9]+');

// HOME
Route::get('/', function () {
  return Inertia::render('Home', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
  ]);
})->name('home');

//PAYMENT PAGE
Route::get('/payment/{cost}/{sponsorshipId}/{property}', function ($cost, $sponsorshipId, $property) {
  return Inertia::render('Properties/PaymentPage', [
    'cost' => $cost,
    'sponsorshipId' => $sponsorshipId,
    'property' => $property,
  ]);
})->middleware(['auth', 'verified'])->name('payment');

// CRUD Routes
Route::middleware(['auth', 'verified'])->resource('properties', \App\Http\Controllers\UserPropertyController::class);

// Creazione messaggio
Route::post('/properties/{property}/messages', [PropertyController::class, 'storeMessage'])->name('properties.messages.store');

// Eliminazione messaggio dalla dashboard
Route::delete('/messages/{message}', [PropertyController::class, 'destroyMessage'])->name('messages.destroy');

require __DIR__ . '/auth.php';