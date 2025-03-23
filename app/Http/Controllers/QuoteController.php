<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use App\Models\User;
use App\Notifications\BasicNotification;
use App\Notifications\QuoteRequestNotification;
use App\Notifications\RejectNotification;
use Illuminate\Http\Request;

class QuoteController extends Controller
{

    public function index()
    {
        $quotes = Quote::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')
            ->with(['user:id,name,email', 'catalogProducts'])->get();

        return inertia('Quote/Index', compact('quotes'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*.id' => 'required',
            'selectedProducts.*.quantity' => 'required|integer|min:1',
        ]);

        $quote = Quote::create([
            'notes' => $validated['notes'],
            'company_branch_id' => auth()->id(),
        ]);

        // Adjuntar productos a la relación con datos adicionales en la tabla pivot
        $productsData = [];
        foreach ($validated['selectedProducts'] as $product) {
            $productsData[$product['id']] = [
                'quantity' => $product['quantity'],
                'price' => $product['price'] ?? 1,
                'show_image' => $product['show_image'] ?? true,
                'notes' => $product['notes'] ?? null,
            ];
        }

        $quote->catalogProducts()->attach($productsData);

        //notificar a vendedor y a dirección
        $subject = 'Cotización solicitada por cliente';
        $concept = 'Cotización';
        $folio = 'COT-' . str_pad($quote->id, 4, "0", STR_PAD_LEFT);
        $module = 'quote';
        if (app()->environment() === 'production') {
            $url = 'https://intranetemblems3d.dtw.com.mx/quotes';
        } else {
            $url = 'http://localhost:8000/quotes';
        }

        $direction = User::whereIn('id', [2,3,35])->get();
        foreach ($direction as $user) {
            $user->notify(new QuoteRequestNotification($subject, $concept, $folio, $module, $url));
        }

        return to_route('catalog-product-company.index');
    }


    public function storeSignature(Request $request, Quote $quote)
    {
        // Elimina la firma que ya esté registrada 
        $quote->clearMediaCollection('signature');

        // Guardar el archivo en la colección 'signature'
        $quote->addMediaFromRequest('signature')->toMediaCollection('signature');

        $this->markAsAcepted($quote, $request->approvedProducts);
    }


    public function show(Quote $quote)
    {
        $quote = QuoteResource::make(Quote::with(['catalogProducts', 'user'])->findOrFail($quote->id));

        return inertia('Quote/SpanishTemplate', compact('quote'));
    }


    public function edit(Quote $quote)
    {
        //
    }


    public function update(Request $request, Quote $quote)
    {
        //
    }


    public function destroy(Quote $quote)
    {
        //
    }


    public function fetchQuotes()
    {
        $quotes = QuoteResource::collection(Quote::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')
            ->with(['user:id,name,email', 'catalogProducts'])->get()->take(20));

        return response()->json(['items' => $quotes]);
    }

    public function markAsAcepted(Quote $quote, $approved_products)
    {
        $approved_products_array = array_map('intval', explode(',', $approved_products));

        $quote->update([
            'rejected_razon' => null, // limpia la razon de rechazo en caso de haber sido rechazada
            'responded_at' => now(),
            'quote_acepted' => true,
            'approved_products' => $approved_products_array,
        ]);

        //notificar a vendedor y a dirección
        $subject = 'Cotización aprobada por cliente';
        $concept = 'Cotización';
        $folio = 'COT-' . str_pad($quote->id, 4, "0", STR_PAD_LEFT);
        $module = 'quote';
        if (app()->environment() === 'production') {
            $url = 'https://intranetemblems3d.dtw.com.mx/quotes';
        } else {
            $url = 'http://localhost:8000/quotes';
        }

        $quote->companyBranch->company->seller?->notify(new BasicNotification($subject, $concept, $folio, $module, $url));
        $direction = User::whereIn('id', [2,3])->get();
        foreach ($direction as $user) {
            $user->notify(new BasicNotification($subject, $concept, $folio, $module, $url));
        }
    }

    public function rejectQuote(Request $request, Quote $quote)
    {
        $request->validate([
            'rejected_razon' => 'required|string|min:5|max:255'
        ]);

        // Elimina la firma si ya está registrada 
        $quote->clearMediaCollection('signature');

        $quote->update([
            'rejected_razon' => $request->rejected_razon,
            'responded_at' => now(),
            'quote_acepted' => false,
        ]);

        //notificar a vendedor y a dirección
        $subject = 'Cotización rechazada por cliente';
        $concept = 'Cotización';
        $folio = 'COT-' . str_pad($quote->id, 4, "0", STR_PAD_LEFT);
        $module = 'quote';
        if (app()->environment() === 'production') {
            $url = 'https://intranetemblems3d.dtw.com.mx/quotes';
        } else {
            $url = 'http://localhost:8000/quotes';
        }
        $quote->companyBranch->company->seller?->notify(new BasicNotification($subject, $concept, $folio, $module, $url));
        $direction = User::whereIn('id', [2,3])->get();

        foreach ($direction as $user) {
            $user->notify(new RejectNotification($subject, $concept, $folio, $module, $url));
        }
    }

    public function getItemsByPage($currentPage)
    {
        $offset = $currentPage * 20;

        // obtener todas las cotizaciones
        $all_quotes = QuoteResource::collection(Quote::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')
            ->with(['user:id,name,email', 'catalogProducts'])->get());

        $quotes = $all_quotes->splice($offset)->take(20);

        return response()->json(['items' => $quotes]);
    }
}
