<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Libro;
use App\Models\Editorial;
use App\Models\Categoria;
use App\Models\EstadoLibro;
use App\Models\Autor;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [
            ['titulo' => 'Cien años de soledad', 'anio_publicacion' => 1967, 'num_paginas' => 471],
            ['titulo' => '1984', 'anio_publicacion' => 1949, 'num_paginas' => 328],
            ['titulo' => 'Harry Potter y la piedra filosofal', 'anio_publicacion' => 1997, 'num_paginas' => 309],
            ['titulo' => 'Don Quijote de la Mancha', 'anio_publicacion' => 1905, 'num_paginas' => 863],
            ['titulo' => 'Orgullo y prejuicio', 'anio_publicacion' => 1914, 'num_paginas' => 432],
            ['titulo' => 'Rayuela', 'anio_publicacion' => 1963, 'num_paginas' => 736],
            ['titulo' => 'El viejo y el mar', 'anio_publicacion' => 1952, 'num_paginas' => 127],
            ['titulo' => 'Crónica de una muerte anunciada', 'anio_publicacion' => 1981, 'num_paginas' => 122],
            ['titulo' => 'Kafka en la orilla', 'anio_publicacion' => 2002, 'num_paginas' => 505],
            ['titulo' => 'La ciudad y los perros', 'anio_publicacion' => 1963, 'num_paginas' => 409],
            ['titulo' => 'Fahrenheit 451', 'anio_publicacion' => 1953, 'num_paginas' => 249],
            ['titulo' => 'El Aleph', 'anio_publicacion' => 1949, 'num_paginas' => 157],
            ['titulo' => 'La casa de los espíritus', 'anio_publicacion' => 1982, 'num_paginas' => 496],
            ['titulo' => 'En busca del tiempo perdido', 'anio_publicacion' => 1913, 'num_paginas' => 4215],
            ['titulo' => 'El extranjero', 'anio_publicacion' => 1942, 'num_paginas' => 123],
        ];

        foreach ($libros as $libro) {
            Libro::create([
                'titulo' => $libro['titulo'],
                'anio_publicacion' => $libro['anio_publicacion'],
                'num_paginas' => $libro['num_paginas'],
                'id_editorial' => Editorial::inRandomOrder()->first()->id,
                'id_categoria' => Categoria::inRandomOrder()->first()->id,
                'id_estado_libro' => EstadoLibro::inRandomOrder()->first()->id,
                'id_autor' => Autor::inRandomOrder()->first()->id,
            ]);
        }
    }
}
