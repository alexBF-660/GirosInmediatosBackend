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
        Schema::create('departamentos', function (Blueprint $table) {
            //campos de la tabla
            $table->id();
            $table->string("nombre", 150)->unique();

            //campos de llaves foraneas
            $table->foreignId("pais_id")->nullable()->constrained("paises")->nullOnDelete();
            
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
        Schema::dropIfExists('departamentos');
    }
};
