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
        Schema::create('movimientos_administrativos', function (Blueprint $table) {
            // Campos de la tabla
            $table->id();
            $table->date('fecha')->nullable()->index();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->integer('tipo')->nullable();
            $table->string("descripcion")->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            
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
        Schema::dropIfExists('movimientos_administrativos');
    }
};
