<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoLibro;

class EstadoLibroSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Completo', 'descripcion' => 'El libro se encuentra en perfecto estado, sin páginas faltantes.'],
            ['nombre' => 'Incompleto', 'descripcion' => 'El libro tiene páginas o secciones faltantes.'],
            ['nombre' => 'En revisión', 'descripcion' => 'El libro está siendo evaluado para determinar su estado.'],
            ['nombre' => 'Desconocido', 'descripcion' => 'No se tiene información precisa sobre el estado del libro.'],
            ['nombre' => 'Dañado', 'descripcion' => 'El libro presenta daños físicos importantes.'],
            ['nombre' => 'Restaurado', 'descripcion' => 'El libro fue reparado y está nuevamente en circulación.'],
        ];

        foreach ($estados as $estado) {
            EstadoLibro::create($estado);
        }
    }
}
