<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'estado',
    ];

    // 1 cliente - m facturas / boletas
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
