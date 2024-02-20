<?php

use App\Http\Controllers\QuoteController;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use App\Models\Sale;
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
        $quotes = QuoteResource::collection(Quote::where('company_branch_id', auth()->id())->with(['user:id,name,email', 'catalogProducts'])->get());

        return $quotes;
        return Inertia('Dashboard/Index', compact('quotes'));
    })->name('dashboard');
});

// ------------- quote routes -----------------------------------
// --------------------------------------------------------------
Route::resource('quotes', QuoteController::class);
Route::post('quotes/massive-delete', [QuoteController::class, 'massiveDelete'])->name('quotes.massive-delete');
Route::put('quotes/authorize/{quote}', [QuoteController::class, 'authorizeQuote'])->name('quotes.authorize');
