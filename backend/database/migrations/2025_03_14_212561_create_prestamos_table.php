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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_libro')->constrained('libros')->onDelete('cascade');
            $table->integer('numero_ejemplares');
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion_esperada');
            $table->foreignId('id_existencia')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('id_multa')->nullable()->constrained('multas')->onDelete('set null');
            $table->decimal('monto_multa', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
