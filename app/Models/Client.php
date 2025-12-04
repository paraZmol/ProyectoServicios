<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'estado',
        'dni',
    ];

    // enviar datos a java script
    protected $appends = ['tipo_documento', 'documento_oculto'];

    // para detectar si es dni o ruc
    public function getTipoDocumentoAttribute()
    {
        $numero = preg_replace('/[^0-9A-Za-z]/', '', $this->dni);
        $largo = strlen($numero);

        if ($largo === 8) return 'DNI';
        if ($largo === 11) return 'RUC';

        // cualquier otro documento con mas de 11 digitos
        return 'OTRO';
    }

    // logica para los asteriscos
    public function getDocumentoOcultoAttribute()
    {
        $numero = $this->dni;

        // si no hay numero, devolvemos guion
        if (!$numero) return '-';

        $largo = strlen($numero);

        if ($largo <= 4) return $numero;

        return str_repeat('*', $largo - 4) . substr($numero, -4);
    }

    // 1 cliente - m facturas / boletas
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
