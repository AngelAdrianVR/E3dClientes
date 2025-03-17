<?php

namespace App\Http\Controllers;

use App\Models\CatalogProductCompany;
use Illuminate\Http\Request;

class CatalogProductCompanyController extends Controller
{
    public function index()
    {
        return inertia('CatalogProductCompany/Index');
    }

    public function show(CatalogProductCompany $catalogProductCompany)
    {
        //
    }
}