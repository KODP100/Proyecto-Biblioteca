<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Editorial;

class EditorialSeeder extends Seeder
{
    public function run(): void
    {
        $editoriales = [
            [
                'nombre' => 'Océano',
                'direccion' => 'Av. Reforma 123, Ciudad de México',
                'telefono' => '555-123-4567',
                'correo' => 'contacto@oceano.com',
            ],
            [
                'nombre' => 'Santillana',
                'direccion' => 'Calle Local 45, San Salvador',
                'telefono' => '503-2222-3333',
                'correo' => 'info@santillana.com.sv',
            ],
            [
                'nombre' => 'Penguin Random House',
                'direccion' => '1745 Broadway, Nueva York, NY',
                'telefono' => '212-782-9000',
                'correo' => 'info@penguinrandomhouse.com',
            ],
            [
                'nombre' => 'HarperCollins',
                'direccion' => '195 Broadway, Nueva York, NY',
                'telefono' => '212-207-7000',
                'correo' => 'support@harpercollins.com',
            ],
            [
                'nombre' => 'Planeta',
                'direccion' => 'Diagonal 547, Barcelona, España',
                'telefono' => '+34 933 16 06 00',
                'correo' => 'editorial@planeta.es',
            ],
        ];

        foreach ($editoriales as $editorial) {
            Editorial::create($editorial);
        }
    }
}
