<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_cierre',
        'hora_cierre',
        'user_id',

        // financiero
        'total_recaudado',
        'total_por_cobrar',

        // conteo
        'cantidad_efectuadas',
        'cantidad_pendientes',
        'cantidad_anuladas',
    ];

    // 1 cierre - 1 usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
