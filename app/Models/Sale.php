<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_company',
        'freight_cost',
        'status',
        'oce_name',
        'order_via',
        'tracking_guide',
        'promise_date',
        'invoice',
        'notes',
        'is_high_priority',
        'authorized_user_name',
        'authorized_at',
        'recieved_at',
        'user_id',
        'company_branch_id',
        'contact_id',
        'oportunity_id'
    ];

    protected $casts = [
        'authorized_at' => 'datetime',
        'recieved_at' => 'datetime',
        'promise_date' => 'date',
    ];

    //relationships
    // public function contact(): BelongsTo
    // {
    //     return $this->belongsTo(Contact::class);
    // }

    // public function oportunity(): BelongsTo
    // {
    //     return $this->belongsTo(Oportunity::class);
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyBranch(): BelongsTo
    {
        return $this->belongsTo(CompanyBranch::class);
    }

    // public function catalogProductCompanySales(): HasMany
    // {
    //     return $this->hasMany(CatalogProductCompanySale::class);
    // }

    public function productions()
    {
        return $this->hasManyThrough(Production::class, CatalogProductCompanySale::class, 'sale_id', 'catalog_product_company_sale_id');
    }

    // methods
    // public function getTotalSoldAmount()
    // {
    //     $totalAmount = 0;

    //     if ($this->authorized_at) { // solo si la orden esta autorizada
    //         // Obtén todas las relaciones de CatalogProductCompanySale asociadas a esta venta
    //         $catalogProductCompanySales = CatalogProductCompanySale::where('sale_id', $this->id)->get();

    //         foreach ($catalogProductCompanySales as $catalogProductCompanySale) {
    //             // Accede al modelo CatalogProductCompany a través de la relación
    //             $catalogProductCompany = $catalogProductCompanySale->catalogProductCompany;

    //             if ($catalogProductCompany) {
    //                 // Calcula el monto total para este producto
    //                 $quantity = $catalogProductCompanySale->quantity;
    //                 $price = $catalogProductCompany->new_price;

    //                 $totalProductAmount = $quantity * $price;

    //                 // Agrega el monto total de este producto al total general
    //                 $totalAmount += $totalProductAmount;
    //             }
    //         }
    //     }

    //     return $totalAmount;
    // }

    // public function getStatus()
    // {
    //     $hasStarted = $this->productions?->whereNotNull('started_at')->count();
    //     $hasNotFinished = $this->productions?->whereNull('finished_at')->count();
    //     $lowStock = $this->productions?->where('has_low_stock', true)->count();

    //     if ($this->authorized_at == null) {
    //         $status = [
    //             'label' => 'Esperando autorización',
    //             'text-color' => 'text-amber-500',
    //             'border-color' => 'border-amber-500',
    //         ];
    //     } elseif ($this->productions) {
    //         if ($lowStock) {
    //             $status = [
    //                 'label' => 'Falta de materia prima',
    //                 'text-color' => 'text-red-500',
    //                 'border-color' => 'border-red-500',
    //             ];
    //         } elseif (!$hasStarted) {
    //             $status = [
    //                 'label' => 'Producción sin iniciar',
    //                 'text-color' => 'text-gray-500',
    //                 'border-color' => 'border-gray-500',
    //             ];
    //         } elseif ($hasStarted && $hasNotFinished) {
    //             $status = [
    //                 'label' => 'Producción en proceso',
    //                 'text-color' => 'text-blue-500',
    //                 'border-color' => 'border-blue-500',
    //             ];
    //         } else {
    //             $status = [
    //                 'label' => 'Producción terminada',
    //                 'text-color' => 'text-green-500',
    //                 'border-color' => 'border-green-500',
    //             ];
    //         }
    //     } else {
    //         $status = [
    //             'label' => 'Autorizado sin orden de producción',
    //             'text-color' => 'text-amber-500',
    //             'border-color' => 'border-amber-500',
    //         ];
    //     }

    //     return $status;
    // }
}
