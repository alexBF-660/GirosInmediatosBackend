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
        Schema::create('usuarios', function (Blueprint $table) {
            //campos de la tabla
            $table->id();
            $table->string("nombres", 100);
            $table->string("ap_paterno", 100);
            $table->string("ap_materno", 100)->nullable();
            $table->string("ci", 20)->unique();
            $table->string("celular", 20)->nullable();
            $table->string("foto", 255)->nullable();
            $table->string("genero");
            $table->date("fecha_nacimiento")->nullable();
            $table->string("email", 150)->unique();
            $table->string("password", 255);
            $table->rememberToken();
            
            //campos de llaves foraneas
            $table->foreignId("rol_id")->nullable()->constrained("roles")->nullOnDelete();
            $table->foreignId("sucursal_id")->nullable()->constrained("sucursales")->nullOnDelete();

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
        Schema::dropIfExists('usuarios');
    }
};
