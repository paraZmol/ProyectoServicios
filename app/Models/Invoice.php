<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'estado',
        'tipo_pago',
        'subtotal',
        'impuesto',
        'total',
        'client_id',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // m facturas - 1 cliente
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // m facturas - 1 usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 1 facturas - m detalles
    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
