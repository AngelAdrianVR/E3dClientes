<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
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


    public function storeSignature(Request $request, Design $design)
    {
        // Elimina la firma que ya esté registrada 
        $design->clearMediaCollection('signature');

        // Guardar el archivo en la colección 'signature'
        $design->addMediaFromRequest('signature')->toMediaCollection('signature');

        $this->markAsAcepted($design);
    }

    
    public function show(Design $design)
    {
        $design = DesignResource::make(Design::with('user:id,name')->findOrFail($design->id));

        // return $design;
        return inertia('Dashboard/Tabs/Design/Show', compact('design'));
    }

    
    public function edit(Design $design)
    {
        //
    }

    
    public function update(Request $request, Design $design)
    {
        //
    }

    
    public function destroy(Design $design)
    {
        //
    }

    public function fetchDesigns()
    {
        $designs = DesignResource::collection(Design::where('company_branch_name', auth()->user()->name)->whereNotNull('authorized_at')->get());

        return response()->json(['items' => $designs]);
    }


    public function markAsAcepted(Design $design)
    {
        $design->update([
            'rejected_razon' => null, // limpia la razon de rechazo en caso de haber sido rechazada
            'responded_at' => now(),
            'design_acepted' => true,
        ]);
    }
}
