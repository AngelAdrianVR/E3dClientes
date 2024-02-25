<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignAuthorizationResource;
use App\Models\DesignAuthorization;
use Illuminate\Http\Request;

class DesignAuthorizationController extends Controller
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


    public function storeSignature(Request $request, DesignAuthorization $design_authorization)
    {
        // Elimina la firma que ya esté registrada 
        $design_authorization->clearMediaCollection('signature');

        // Guardar el archivo en la colección 'signature'
        $design_authorization->addMediaFromRequest('signature')->toMediaCollection('signature');

        $this->markAsAcepted($design_authorization);
    }

    
    public function show(DesignAuthorization $design_authorization)
    {
        $design_authorization = DesignAuthorizationResource::make(DesignAuthorization::with('seller:id,name', 'companyBranch.contacts')->findOrFail($design_authorization->id));

        // return $design_authorization;
        return inertia('Dashboard/Tabs/Design/Show', compact('design_authorization'));
    }

    
    public function edit(DesignAuthorization $design_authorization)
    {
        //
    }

    
    public function update(Request $request, DesignAuthorization $design_authorization)
    {
        //
    }

    
    public function destroy(DesignAuthorization $design_authorization)
    {
        //
    }


    public function fetchDesigns()
    {
        $design_authorization = DesignAuthorizationResource::collection(DesignAuthorization::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')->get());

        return response()->json(['items' => $design_authorization]);
    }

    public function rejectDesign(Request $request, DesignAuthorization $design_authorization)
    {   
        $request->validate([
            'rejected_razon' => 'required|string|min:5|max:255'
        ]);

        // Elimina la firma si ya está registrada 
        $design_authorization->clearMediaCollection('signature');

        $design_authorization->update([
            'rejected_razon' => $request->rejected_razon,
            'responded_at' => now(),
            'design_accepted' => false,
        ]);
    }


    public function markAsAcepted(DesignAuthorization $design_authorization)
    {
        $design_authorization->update([
            'rejected_razon' => null, // limpia la razon de rechazo en caso de haber sido rechazada
            'responded_at' => now(),
            'design_accepted' => true,
        ]);
    }
}
