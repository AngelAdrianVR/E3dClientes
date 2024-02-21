<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
   
    public function index()
    {
        //
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
        // Elimina la firma que ya esté reggistrada 
        $quote->clearMediaCollection('signature');

        // Guardar el archivo en la colección 'signature'
        $quote->addMediaFromRequest('signature')->toMediaCollection('signature');

        $this->markAsAcepted($quote);
    }

    
    public function show(Quote $quote)
    {
        $quote = QuoteResource::make(Quote::with('catalogProducts')->findOrFail($quote->id));

        // return $quote;
        return inertia('Dashboard/Tabs/Quote/SpanishTemplate', compact('quote'));
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
        $quotes = QuoteResource::collection(Quote::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')->with(['user:id,name,email', 'catalogProducts'])->get());

        return response()->json(['items' => $quotes]);
    }

    public function markAsAcepted(Quote $quote)
    {
        $quote->update([
            'responded_at' => now(),
            'quote_acepted' => true,
        ]);
    }
}
