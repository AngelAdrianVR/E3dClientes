<?php

use App\Http\Controllers\DesignController;
use App\Http\Controllers\QuoteController;
use App\Http\Resources\DesignResource;
use App\Models\Design;
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
        
        // $designs = DesignResource::collection(Design::where('company_branch_name', auth()->user()->name)->whereNotNull('authorized_at')->get());

        // return $designs;
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


// ------------- designs routes -----------------------------------
// ----------------------------------------------------------------
Route::resource('designs', DesignController::class);
Route::get('designs-fetch', [DesignController::class, 'fetchDesigns'])->middleware('auth')->name('designs.fetch');
Route::post('designs-store-signature/{design}', [DesignController::class, 'storeSignature'])->middleware('auth')->name('designs.store-signature');
