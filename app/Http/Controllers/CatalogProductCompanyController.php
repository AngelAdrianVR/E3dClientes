<?php

namespace App\Http\Controllers;

use App\Models\CatalogProductCompany;
use Illuminate\Http\Request;

class CatalogProductCompanyController extends Controller
{
    public function index()
    {   
        $catalog_products_company = CatalogProductCompany::with('catalogProduct.media')
            ->where('company_id', auth()->id())
            ->latest()
            ->get(['id', 'new_date', 'new_price', 'new_currency', 'catalog_product_id', 'company_id']);

            // return $catalog_products_company;
        return inertia('CatalogProductCompany/Index', compact('catalog_products_company'));
    }

    public function show(CatalogProductCompany $catalogProductCompany)
    {
        //
    }
}