<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Ciencia Ficción', 'descripcion' => 'Libros con elementos de ciencia avanzada, viajes espaciales y tecnología.'],
            ['nombre' => 'Fantasía', 'descripcion' => 'Historias con magia, mundos imaginarios y criaturas míticas.'],
            ['nombre' => 'Novela Histórica', 'descripcion' => 'Ficción basada en eventos históricos.'],
            ['nombre' => 'Romance', 'descripcion' => 'Narraciones centradas en relaciones amorosas.'],
            ['nombre' => 'Misterio', 'descripcion' => 'Tramas con crímenes, secretos o enigmas por resolver.'],
            ['nombre' => 'Terror', 'descripcion' => 'Relatos diseñados para provocar miedo o suspenso.'],
            ['nombre' => 'Biografía', 'descripcion' => 'Historias reales de vida de personas destacadas.'],
            ['nombre' => 'Autoayuda', 'descripcion' => 'Libros para el desarrollo personal y emocional.'],
            ['nombre' => 'Psicología', 'descripcion' => 'Temas relacionados con la mente y comportamiento humano.'],
            ['nombre' => 'Filosofía', 'descripcion' => 'Textos reflexivos sobre la existencia, el conocimiento y la ética.'],
            ['nombre' => 'Ciencia', 'descripcion' => 'Contenido educativo sobre física, química, biología, etc.'],
            ['nombre' => 'Educación', 'descripcion' => 'Obras para el aprendizaje y pedagogía.'],
            ['nombre' => 'Tecnología', 'descripcion' => 'Libros sobre avances y herramientas tecnológicas.'],
            ['nombre' => 'Arte', 'descripcion' => 'Temas de pintura, escultura, música y expresión artística.'],
            ['nombre' => 'Literatura Clásica', 'descripcion' => 'Obras universales de autores reconocidos históricamente.'],
        ];

        foreach ($categorias as $cat) {
            Categoria::create($cat);
        }
    }
}
