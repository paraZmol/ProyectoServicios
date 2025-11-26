<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_empresa');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('simbolo_moneda')->default('$');
            $table->decimal('iva_porcentaje', 5, 2)->default(13.00);
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
