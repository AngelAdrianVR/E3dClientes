<?php

namespace App\Http\Controllers;

use App\Models\CatalogProduct;
use App\Models\CatalogProductCompany;
use App\Models\CompanyBranch;
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

    public function show(CatalogProductCompany $catalog_product_company)
    {
        $catalog_product_company->load('catalogProduct.media');
        
        return inertia('CatalogProductCompany/Show', compact('catalog_product_company'));
    }

    public function getSuggested()
    {
        $branch = CompanyBranch::find(auth()->id());
        $suggested_array = $branch->company['suggested_products'];
        $suggested_products = CatalogProduct::with('media')->whereIn('id', $suggested_array)->get();

        return response()->json(compact('suggested_products'));
    }

    public function fetchAll()
    {
        $totalProducts = CatalogProductCompany::where('company_id', auth()->id())
            ->count();

        return response()->json(['items' => $totalProducts]);
    }
}