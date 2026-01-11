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
        Schema::create('cash_counts', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_cierre');
            $table->time('hora_cierre')->nullable();
            $table->foreignId('user_id')->constrained('users');
            // finanzas
            $table->decimal('total_recaudado', 10, 2);
            $table->decimal('total_por_cobrar', 10, 2)->default(0);
            // conteos
            $table->integer('cantidad_efectuadas')->default(0);
            $table->integer('cantidad_pendientes')->default(0);
            $table->integer('cantidad_anuladas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_counts');
    }
};
