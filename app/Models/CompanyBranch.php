<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class CompanyBranch extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;

    protected $fillable = [
        'name',
        'password', // se utiliza para ingresar a la app de clientes
        'address',
        'post_code',
        'sat_method',
        'sat_type',
        'sat_way',
        'company_id',
        'important_notes',
        'days_to_reactivate',
    ];

    protected $hidden = [
        'password'
    ];

    //relationships
    public function quotes():HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function sales():HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
