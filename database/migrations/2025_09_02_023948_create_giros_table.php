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
        Schema::create('giros', function (Blueprint $table) {
            //campos de la tabla
            $table->id();
            $table->string("nombre_remitente");
            $table->string("nombre_consignatario");
            $table->decimal("monto_enviado", 8, 2)->default(0);
            $table->decimal("comision_envio", 8, 2)->default(0);//10% del monto enviado
            $table->date("fecha_envio")->nullable();
            $table->date("fecha_entrega")->nullable();
            $table->string("ci_consignatario")->nullable();

            //campos de llaves foraneas
            $table->foreignId("sucursal_origen_id")->nullable()->constrained("sucursales")->nullOnDelete();
            $table->foreignId("sucursal_destino_id")->nullable()->constrained("sucursales")->nullOnDelete();
            $table->foreignId("usuario_envio_id")->nullable()->constrained("usuarios")->nullOnDelete();
            $table->foreignId("usuario_entrega_id")->nullable()->constrained("usuarios")->nullOnDelete();
            $table->foreignId("estado_id")->nullable()->constrained("estado__giros")->nullOnDelete();

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
        Schema::dropIfExists('giros');
    }
};
