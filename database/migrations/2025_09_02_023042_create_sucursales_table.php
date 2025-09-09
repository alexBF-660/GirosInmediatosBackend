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
        Schema::create('sucursales', function (Blueprint $table) {
            //campos de la tabla
            $table->id();
            $table->string("nombre", 100)->unique();
            $table->text("descripcion")->nullable();
            $table->string("direccion")->nullable();
            $table->string("telefono", 20)->nullable();
            $table->decimal("capital_actual", 15, 2)->default(0);

            //campos de llaves foraneas
            $table->foreignId("departamento_id")->nullable()->constrained("departamentos")->nullOnDelete();

            //campos de auditoria
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
