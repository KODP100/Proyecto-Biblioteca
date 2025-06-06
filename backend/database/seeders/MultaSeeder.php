<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MultaSeeder extends Seeder
{
    public function run()
    {
        $multas = [];

        for ($i = 1; $i <= 15; $i++) {
            $rango = rand(1, 30); // rango de días entre 1 y 30
            $monto = round($rango * 0.75, 2); // cálculo ejemplo: 0.75 por día

            $multas[] = [
                'rango_dias' => $rango,
                'monto' => $monto,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('multas')->insert($multas);
    }
}
