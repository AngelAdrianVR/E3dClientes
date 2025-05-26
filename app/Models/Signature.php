<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = 'public/firmas/' . $value;
    }
}
