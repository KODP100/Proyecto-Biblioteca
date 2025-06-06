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
            $table->foreignId('id_departamento')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('id_municipio')->constrained('municipios')->onDelete('cascade');
            $table->foreignId('id_distrito')->constrained('distritos')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Relación con users
            $table->date('alta_solicitante')->nullable();
            $table->date('baja_solicitante')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
