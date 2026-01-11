<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_cierre',
        'monto_total',
        'cantidad_ventas',
        'hora_cierre',
        'user_id',
    ];

    // 1 cierre - 1 usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}