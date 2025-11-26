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
        'correo_electronico',
        'simbolo_moneda',
        'iva_porcentaje',
        'direccion',
        'region',
        'provincia',
        'ciudad',
        'codigo_postal',
        'logo_path',
    ];

    public $timestamps = true;
}
