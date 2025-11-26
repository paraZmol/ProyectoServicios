<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_servicio',
        'cantidad',
        'precio_unitario_final',
        'total_linea',
        'invoice_id',
        'service_id',
    ];

    // m detales - 1 factura
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // m detalles - 1 servicio
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withDefault();
    }
}
