<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PrestamoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $libros = DB::table('libros')->pluck('id')->toArray();
        $multas = DB::table('multas')->pluck('id')->toArray();

        foreach (range(1, 15) as $i) {
            $fechaPrestamo = $faker->dateTimeBetween('-2 months', 'now');
            $fechaDevolucion = (clone $fechaPrestamo)->modify('+7 days');

            $tieneMulta = $faker->boolean(40); // 40% de probabilidad de tener multa

            DB::table('prestamos')->insert([
                'id_libro' => $faker->randomElement($libros),
                'numero_ejemplares' => $faker->numberBetween(1, 3),
                'fecha_prestamo' => $fechaPrestamo->format('Y-m-d'),
                'fecha_devolucion_esperada' => $fechaDevolucion->format('Y-m-d'),
                'id_multa' => $tieneMulta ? $faker->randomElement($multas) : null,
                'monto_multa' => $tieneMulta ? $faker->randomFloat(2, 1, 20) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
