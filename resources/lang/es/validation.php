<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Líneas de Lenguaje de Validación
    |--------------------------------------------------------------------------
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    // ... muchas reglas más ...

    // --- La regla que necesitas para 'ya ha sido tomado' ---
    'unique'               => 'El :attribute ya ha sido registrado.',

    // 'url'                => 'El campo :attribute no es un formato válido.',
    // ... muchas reglas más ...


    /*
    |--------------------------------------------------------------------------
    | Atributos de Validación Personalizados
    |--------------------------------------------------------------------------
    | Aquí mapeamos el nombre del campo en la base de datos a un nombre legible.
    */

    'attributes' => [
        // Nombre de los campos en el formulario/DB (la variable :attribute)
        'dni' => 'DNI o N° de Identificación',
        'email' => 'correo electrónico',
        'nombre' => 'nombre del cliente',
        // 'telefono' => 'teléfono',
    ],

];
