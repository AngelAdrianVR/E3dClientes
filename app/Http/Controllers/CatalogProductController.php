<?php

namespace App\Http\Controllers;

use App\Models\CatalogProduct;
use Illuminate\Http\Request;

class CatalogProductController extends Controller
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

    public function show(CatalogProduct $catalog_product)
    {   
        $catalog_product->load('media');
        
        return inertia('SuggestedProduct/Show', compact('catalog_product'));
    }

    public function edit(CatalogProduct $catalog_product)
    {
        //
    }

    public function update(Request $request, CatalogProduct $catalog_product)
    {
        //
    }

    public function destroy(CatalogProduct $catalog_product)
    {
        //
    }
}
