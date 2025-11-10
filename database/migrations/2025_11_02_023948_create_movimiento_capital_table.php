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
        Schema::create('movimiento_capital', function (Blueprint $table) {
            // Campos de la tabla
            $table->id();
            $table->date('fecha')->nullable()->index();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();

            $table->decimal('total_enviado', 12, 2)->default(0);
            $table->decimal('total_recibido', 12, 2)->default(0);
            $table->decimal('balance_dia', 12, 2)->default(0);
            $table->decimal('capital_inicial', 12, 2)->default(0);
            $table->decimal('capital_actual', 12, 2)->default(0);

            // Campos de auditoría
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_capital');
    }
};
