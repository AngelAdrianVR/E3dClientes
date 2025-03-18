<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Models\Quote;
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
        //
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
        $quote = QuoteResource::make(Quote::with('catalogProducts')->findOrFail($quote->id));

        // return $quote;
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
