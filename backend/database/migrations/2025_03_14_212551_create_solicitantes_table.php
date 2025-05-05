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
        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('edad');
            $table->string('telefono');
            $table->string('correo')->unique();
            $table->foreignId('id_departamento')->constrained('departamentos')->onDelete('cascade');;
            $table->foreignId('id_municipio')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('id_distrito')->constrained('distritos')->onDelete('cascade');
            $table->date('alta_solicitante')->nullable(); // Fecha de alta
            $table->date('baja_solicitante')->nullable(); // Fecha de baja
            $table->string('direccion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitantes');
    }
};
