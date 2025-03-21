<?php

use App\Http\Controllers\CatalogProductCompanyController;
use App\Http\Controllers\DesignAuthorizationController;
use App\Http\Controllers\QuoteController;
use App\Models\Quote;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::redirect('/', 'login');

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $totalQuotes = Quote::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')->count();

        return Inertia('Dashboard/Index', compact('totalQuotes'));
    })->name('dashboard');
});


// ------------- catalog-product-company routes -----------------------------------
// ---------------------------------------------------------------
Route::resource('catalog-product-company', CatalogProductCompanyController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);


// ------------- quotes routes -----------------------------------
// ---------------------------------------------------------------
Route::resource('quotes', QuoteController::class)->middleware('auth');
Route::get('quotes-fetch', [QuoteController::class, 'fetchQuotes'])->middleware('auth')->name('quotes.fetch');
Route::post('quotes-store-signature/{quote}', [QuoteController::class, 'storeSignature'])->middleware('auth')->name('quotes.store-signature');
Route::put('quotes-mark-as-acepted/{quote}', [QuoteController::class, 'markAsAcepted'])->middleware('auth')->name('quotes.acepted');
Route::put('quotes-reject/{quote}', [QuoteController::class, 'rejectQuote'])->middleware('auth')->name('quotes.reject');
Route::get('quotes-get-by-page/{currentPage}', [QuoteController::class, 'getItemsByPage'])->name('quotes.get-by-page')->middleware('auth');


// ------------- designs routes -----------------------------------
// ----------------------------------------------------------------
Route::resource('designs', DesignAuthorizationController::class)->middleware('auth');
Route::get('designs-fetch', [DesignAuthorizationController::class, 'fetchDesigns'])->middleware('auth')->name('designs.fetch');
Route::post('designs-store-signature/{design_authorization}', [DesignAuthorizationController::class, 'storeSignature'])->middleware('auth')->name('designs.store-signature');
Route::put('designs-mark-as-acepted/{design_authorization}', [DesignAuthorizationController::class, 'markAsAcepted'])->middleware('auth')->name('designs.acepted');
Route::put('designs-reject/{design_authorization}', [DesignAuthorizationController::class, 'rejectDesign'])->middleware('auth')->name('designs.reject');


Route::get('/ejecutar-comando-storage-link', function () {
    try {
        // Ejecuta el comando de enlace de almacenamiento
        Artisan::call('storage:link');

        return '¡Comando ejecutado con éxito!';
    } catch (\Exception $e) {
        return 'Error al ejecutar el comando: ' . $e->getMessage();
    }
});
