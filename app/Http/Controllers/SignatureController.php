<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
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

    public function show(Signature $signature)
    {
        //
    }

    public function edit(Signature $signature)
    {
        //
    }

    public function update(Request $request, Signature $signature)
    {
        //
    }

    public function destroy(Signature $signature)
    {
        // return $signature;
        // Eliminar el archivo
        Storage::disk('public')->delete($signature->path);

        // Eliminar registro
        $signature->delete();
    }

    public function saveSignInServer(Request $request)
    {   
        if ($request->hasFile('firma')) {
            $file = $request->file('firma');

            // Guardar en storage/app/public/signatures
            $path = $file->store('signatures', 'public');

            // Registrar en la base de datos
            Signature::create([
                'user_id' => auth()->id(),
                'path' => $path,
            ]);
            
            return response()->json([
                'message' => 'Firma guardada correctamente',
                'url' => Storage::url($path),
            ]);

        }

        return response()->json(['error' => 'No se recibió ningún archivo'], 400);
    }

    public function getSignatures()
    {
        $user_id = auth()->id();
    
        // Supongamos que tienes un modelo Firma con campo 'user_id' y 'path'
        $signarures = Signature::where('user_id', $user_id)->get();

        return response()->json($signarures);
    }
}
