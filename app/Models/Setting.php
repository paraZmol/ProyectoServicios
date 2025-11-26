<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_empresa',
        'telefono',
        'email',
        'simbolo_moneda',
        'iva_porcentaje',
        'direccion',
        'ciudad',
    ];

    public $timestamps = true;
}