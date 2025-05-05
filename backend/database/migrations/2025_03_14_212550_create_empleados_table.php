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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('edad');
            $table->string('telefono')->nullable();
            $table->unsignedBigInteger('id_departamento');
            $table->unsignedBigInteger('id_municipio');
            $table->date('alta_empleado')->nullable();
            $table->date('baja_empleado')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
        
            // Claves foraneas corregidas
            $table->foreign('id_departamento')->references('id')->on('departamentos')->onDelete('cascade');
            $table->foreign('id_municipio')->references('id')->on('municipios')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
