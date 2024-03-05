<?php

use App\Http\Controllers\DesignAuthorizationController;
use App\Http\Controllers\QuoteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        
        return Inertia('Dashboard/Index');
    })->name('dashboard');
});

// ------------- quotes routes -----------------------------------
// ---------------------------------------------------------------
Route::resource('quotes', QuoteController::class);
Route::get('quotes-fetch', [QuoteController::class, 'fetchQuotes'])->middleware('auth')->name('quotes.fetch');
Route::post('quotes-store-signature/{quote}', [QuoteController::class, 'storeSignature'])->middleware('auth')->name('quotes.store-signature');
Route::put('quotes-mark-as-acepted/{quote}', [QuoteController::class, 'markAsAcepted'])->middleware('auth')->name('quotes.acepted');
Route::put('quotes-reject/{quote}', [QuoteController::class, 'rejectQuote'])->middleware('auth')->name('quotes.reject');


// ------------- design-authorizations routes -----------------------------------
// ----------------------------------------------------------------
Route::resource('design-authorizations', DesignAuthorizationController::class);
Route::get('design-authorizations-fetch', [DesignAuthorizationController::class, 'fetchDesigns'])->middleware('auth')->name('design-authorizations.fetch');
Route::post('design-authorizations-store-signature/{design_authorization}', [DesignAuthorizationController::class, 'storeSignature'])->middleware('auth')->name('design-authorizations.store-signature');
Route::put('design-authorizations-mark-as-acepted/{design_authorization}', [DesignAuthorizationController::class, 'markAsAcepted'])->middleware('auth')->name('design-authorizations.acepted');
Route::put('design-authorizations-reject/{design_authorization}', [DesignAuthorizationController::class, 'rejectDesign'])->middleware('auth')->name('design-authorizations.reject');
