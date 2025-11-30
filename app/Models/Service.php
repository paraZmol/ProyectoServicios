<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'codigo',
        'nombre_servicio',
        'descripcion',
        'precio',
    ];

    // 1 factura - m deatlles
    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}