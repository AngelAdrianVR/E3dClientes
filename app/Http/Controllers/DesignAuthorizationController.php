<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignAuthorizationResource;
use App\Models\DesignAuthorization;
use App\Models\User;
use App\Notifications\BasicNotification;
use App\Notifications\RejectNotification;
use Illuminate\Http\Request;

class DesignAuthorizationController extends Controller
{

    public function index()
    {
        $designs = DesignAuthorization::where('company_branch_id', auth()->id())->whereNotNull('authorized_at')->get();

        return inertia('Design/Index', compact('designs'));
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


    public function show($design_authorization)
    {
        $design_authorization = DesignAuthorizationResource::make(DesignAuthorization::with('seller:id,name', 'companyBranch.contacts')->findOrFail($design_authorization));

        // return $design_authorization;
        return inertia('Design/Show', compact('design_authorization'));
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

        //notificar a dirección
        $subject = 'Diseño rechazado por cliente';
        $concept = 'Diseño';
        $folio = $design_authorization->name;
        $module = 'design-authorization';
        if (app()->environment() === 'production') {
            $url = 'https://intranetemblems3d.dtw.com.mx/design-authorizations';
        } else {
            $url = 'http://localhost:8000/design-authorizations';
        }
        $direction = User::whereIn('id', [2, 3])->get();

        foreach ($direction as $user) {
            $user->notify(new RejectNotification($subject, $concept, $folio, $module, $url));
        }
    }

    public function markAsAcepted(DesignAuthorization $design_authorization)
    {
        $design_authorization->update([
            'rejected_razon' => null, // limpia la razon de rechazo en caso de haber sido rechazada
            'responded_at' => now(),
            'design_accepted' => true,
        ]);

        //notificar a dirección
        $subject = 'Diseño aprobado por cliente';
        $concept = 'Diseño';
        $folio = $design_authorization->name;
        $module = 'design-authorization';
        if (app()->environment() === 'production') {
            $url = 'https://intranetemblems3d.dtw.com.mx/design-authorizations';
        } else {
            $url = 'http://localhost:8000/design-authorizations';
        }

        $direction = User::whereIn('id', [2, 3])->get();
        foreach ($direction as $user) {
            $user->notify(new BasicNotification($subject, $concept, $folio, $module, $url));
        }
    }
}
