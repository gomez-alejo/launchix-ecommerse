<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Insertar categorías predefinidas
        $categories = [
            [
                'name' => 'Alimentos y Bebidas',
                'description' => 'Productos alimenticios, bebidas, snacks y comida preparada',
                'icon' => '🍔',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Artesanías',
                'description' => 'Productos hechos a mano, decoración y arte local',
                'icon' => '🎨',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ropa y Accesorios',
                'description' => 'Prendas de vestir, calzado, joyería y complementos',
                'icon' => '👕',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tecnología',
                'description' => 'Dispositivos electrónicos, accesorios tecnológicos y gadgets',
                'icon' => '💻',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hogar y Decoración',
                'description' => 'Muebles, decoración, textiles y artículos para el hogar',
                'icon' => '🏠',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Belleza y Cuidado Personal',
                'description' => 'Cosméticos, productos de cuidado personal y bienestar',
                'icon' => '💄',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Libros y Papelería',
                'description' => 'Libros, material de oficina, cuadernos y artículos escolares',
                'icon' => '📚',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deportes y Fitness',
                'description' => 'Equipamiento deportivo, ropa deportiva y accesorios fitness',
                'icon' => '⚽',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juguetes y Juegos',
                'description' => 'Juguetes para niños, juegos de mesa y entretenimiento',
                'icon' => '🧸',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mascotas',
                'description' => 'Alimentos, accesorios y productos para el cuidado de mascotas',
                'icon' => '🐾',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jardín y Exterior',
                'description' => 'Plantas, herramientas de jardinería y decoración exterior',
                'icon' => '🌱',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Automotriz',
                'description' => 'Accesorios para vehículos, repuestos y productos de limpieza',
                'icon' => '🚗',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Salud y Bienestar',
                'description' => 'Suplementos, productos naturales y equipamiento médico',
                'icon' => '💊',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Música e Instrumentos',
                'description' => 'Instrumentos musicales, accesorios y equipamiento de audio',
                'icon' => '🎵',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fotografía',
                'description' => 'Cámaras, lentes, accesorios fotográficos y servicios',
                'icon' => '📷',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bebés y Niños',
                'description' => 'Productos para bebés, ropa infantil y accesorios',
                'icon' => '👶',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Joyería y Relojes',
                'description' => 'Joyas, relojes, bisutería y accesorios elegantes',
                'icon' => '💍',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electrodomésticos',
                'description' => 'Aparatos eléctricos para el hogar y cocina',
                'icon' => '🔌',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Construcción y Herramientas',
                'description' => 'Herramientas, materiales de construcción y equipamiento',
                'icon' => '🔨',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oficina y Negocios',
                'description' => 'Mobiliario de oficina, equipamiento y suministros',
                'icon' => '💼',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Productos Ecológicos',
                'description' => 'Productos sostenibles, orgánicos y amigables con el ambiente',
                'icon' => '♻️',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Productos Digitales',
                'description' => 'Software, cursos online, ebooks y contenido digital',
                'icon' => '💾',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Otros',
                'description' => 'Productos que no encajan en las categorías anteriores',
                'icon' => '📦',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('product_categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};