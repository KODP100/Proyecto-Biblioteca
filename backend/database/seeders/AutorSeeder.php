<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['nombre' => 'Gabriel', 'apellido' => 'García Márquez', 'nacionalidad' => 'Colombiana'],
            ['nombre' => 'J.K.', 'apellido' => 'Rowling', 'nacionalidad' => 'Británica'],
            ['nombre' => 'George', 'apellido' => 'Orwell', 'nacionalidad' => 'Británica'],
            ['nombre' => 'Isabel', 'apellido' => 'Allende', 'nacionalidad' => 'Chilena'],
            ['nombre' => 'Ernest', 'apellido' => 'Hemingway', 'nacionalidad' => 'Estadounidense'],
            ['nombre' => 'Julio', 'apellido' => 'Cortázar', 'nacionalidad' => 'Argentina'],
            ['nombre' => 'Haruki', 'apellido' => 'Murakami', 'nacionalidad' => 'Japonesa'],
            ['nombre' => 'Mario', 'apellido' => 'Vargas Llosa', 'nacionalidad' => 'Peruana'],
            ['nombre' => 'Jorge Luis', 'apellido' => 'Borges', 'nacionalidad' => 'Argentina'],
            ['nombre' => 'Jane', 'apellido' => 'Austen', 'nacionalidad' => 'Británica'],
            ['nombre' => 'Mark', 'apellido' => 'Twain', 'nacionalidad' => 'Estadounidense'],
            ['nombre' => 'Carlos', 'apellido' => 'Fuentes', 'nacionalidad' => 'Mexicana'],
            ['nombre' => 'Leo', 'apellido' => 'Tolstói', 'nacionalidad' => 'Rusa'],
            ['nombre' => 'Miguel', 'apellido' => 'de Cervantes', 'nacionalidad' => 'Española'],
            ['nombre' => 'Franz', 'apellido' => 'Kafka', 'nacionalidad' => 'Alemana'],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}
