<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Entrepreneur;
use App\Models\EntrepreneurAddress;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Service;
use App\Models\ServiceMedia;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\ProductReview;
use App\Models\ServiceReview;
use App\Models\EntrepreneurReview;
use App\Models\ProductFavorite;
use App\Models\ServiceFavorite;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeding...');

        // ====================================
        // 1. USUARIOS
        // ====================================
        $this->command->info('👤 Creando usuarios...');
        
        // Usuario de prueba principal
        $mainUser = User::factory()->create([
            'name' => 'Juan',
            'last_name' => 'Pérez',
            'username' => 'juanperez',
            'email' => 'juan@test.com',
            'password' => bcrypt('password'),
        ]);

        UserAddress::factory()->main()->create(['user_id' => $mainUser->id]);
        UserAddress::factory()->count(2)->create(['user_id' => $mainUser->id]);

        // 50 usuarios aleatorios
        User::factory()
            ->count(50)
            ->create()
            ->each(function ($user) {
                UserAddress::factory()->main()->create(['user_id' => $user->id]);
                UserAddress::factory()->count(rand(0, 3))->create(['user_id' => $user->id]);
            });

        $this->command->info('✓ Usuarios: 51');

        // ====================================
        // 2. EMPRENDEDORES
        // ====================================
        $this->command->info('🏪 Creando emprendedores...');
        
        // Emprendedor de prueba
        $mainEntrepreneur = Entrepreneur::factory()->verified()->create([
            'business_name' => 'Tienda Demo',
            'email' => 'tienda@test.com',
            'password' => bcrypt('password'),
        ]);

        EntrepreneurAddress::factory()->main()->create(['entrepreneur_id' => $mainEntrepreneur->id]);

        // 30 emprendedores aleatorios
        Entrepreneur::factory()
            ->count(30)
            ->create()
            ->each(function ($entrepreneur) {
                EntrepreneurAddress::factory()->main()->create(['entrepreneur_id' => $entrepreneur->id]);
                EntrepreneurAddress::factory()->count(rand(0, 2))->create(['entrepreneur_id' => $entrepreneur->id]);
            });

        $this->command->info('✓ Emprendedores: 31');

        // ====================================
        // 3. CATEGORÍAS
        // ====================================
        $this->command->info('📁 Creando categorías...');
        
        $productCategories = [
            ['name' => 'Alimentos y Bebidas', 'description' => 'Productos alimenticios y bebidas', 'icon' => 'food.svg'],
            ['name' => 'Ropa y Accesorios', 'description' => 'Prendas de vestir y complementos', 'icon' => 'clothing.svg'],
            ['name' => 'Artesanías', 'description' => 'Productos artesanales hechos a mano', 'icon' => 'craft.svg'],
            ['name' => 'Belleza y Cuidado', 'description' => 'Productos de belleza y cuidado personal', 'icon' => 'beauty.svg'],
            ['name' => 'Hogar y Decoración', 'description' => 'Artículos para el hogar', 'icon' => 'home.svg'],
            ['name' => 'Tecnología', 'description' => 'Productos tecnológicos', 'icon' => 'tech.svg'],
            ['name' => 'Libros y Educación', 'description' => 'Material educativo y literario', 'icon' => 'books.svg'],
            ['name' => 'Deportes', 'description' => 'Artículos deportivos', 'icon' => 'sports.svg'],
        ];

        foreach ($productCategories as $category) {
            ProductCategory::create($category);
        }

        $serviceCategories = [
            ['name' => 'Plomería', 'description' => 'Servicios de plomería e instalaciones', 'icon' => 'plumbing.svg'],
            ['name' => 'Electricidad', 'description' => 'Servicios eléctricos', 'icon' => 'electric.svg'],
            ['name' => 'Carpintería', 'description' => 'Trabajos en madera y carpintería', 'icon' => 'carpentry.svg'],
            ['name' => 'Limpieza', 'description' => 'Servicios de limpieza', 'icon' => 'cleaning.svg'],
            ['name' => 'Jardinería', 'description' => 'Cuidado de jardines', 'icon' => 'garden.svg'],
            ['name' => 'Belleza', 'description' => 'Servicios de belleza y estética', 'icon' => 'beauty-service.svg'],
            ['name' => 'Reparaciones', 'description' => 'Reparación de equipos', 'icon' => 'repair.svg'],
            ['name' => 'Catering', 'description' => 'Servicios de alimentación', 'icon' => 'catering.svg'],
        ];

        foreach ($serviceCategories as $category) {
            ServiceCategory::create($category);
        }

        $this->command->info('✓ Categorías: 16');

        // ====================================
        // 4. PRODUCTOS
        // ====================================
        $this->command->info('📦 Creando productos...');
        
        $entrepreneurs = Entrepreneur::all();
        $productCategoriesCollection = ProductCategory::all();

        $entrepreneurs->each(function ($entrepreneur) use ($productCategoriesCollection) {
            $productCount = rand(3, 15);
            
            for ($i = 0; $i < $productCount; $i++) {
                $product = Product::factory()->create([
                    'entrepreneur_id' => $entrepreneur->id,
                    'product_category_id' => $productCategoriesCollection->random()->id,
                ]);

                // 2-5 imágenes por producto
                $mediaCount = rand(2, 5);
                for ($j = 0; $j < $mediaCount; $j++) {
                    ProductMedia::factory()->create([
                        'product_id' => $product->id,
                        'order' => $j,
                    ]);
                }
            }
        });

        $this->command->info('✓ Productos: ' . Product::count());

        // ====================================
        // 5. SERVICIOS
        // ====================================
        $this->command->info('🛠️ Creando servicios...');
        
        $serviceCategoriesCollection = ServiceCategory::all();

        $entrepreneurs->random(20)->each(function ($entrepreneur) use ($serviceCategoriesCollection) {
            $serviceCount = rand(1, 5);
            
            for ($i = 0; $i < $serviceCount; $i++) {
                $service = Service::factory()->create([
                    'entrepreneur_id' => $entrepreneur->id,
                    'service_category_id' => $serviceCategoriesCollection->random()->id,
                ]);

                // 2-4 imágenes por servicio
                $mediaCount = rand(2, 4);
                for ($j = 0; $j < $mediaCount; $j++) {
                    ServiceMedia::factory()->create([
                        'service_id' => $service->id,
                        'order' => $j,
                    ]);
                }
            }
        });

        $this->command->info('✓ Servicios: ' . Service::count());

        // ====================================
        // 6. MÉTODOS DE PAGO
        // ====================================
        $this->command->info('💳 Creando métodos de pago...');
        
        $paymentMethods = [
            ['name' => 'Tarjeta de Crédito', 'description' => 'Pago con tarjeta de crédito', 'active' => true],
            ['name' => 'Tarjeta de Débito', 'description' => 'Pago con tarjeta de débito', 'active' => true],
            ['name' => 'PSE', 'description' => 'Pago electrónico desde cuenta bancaria', 'active' => true],
            ['name' => 'Efectivo', 'description' => 'Pago en efectivo contra entrega', 'active' => true],
            ['name' => 'Nequi', 'description' => 'Pago mediante Nequi', 'active' => true],
            ['name' => 'Daviplata', 'description' => 'Pago mediante Daviplata', 'active' => true],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('✓ Métodos de pago: 6');

        // ====================================
        // 7. CARRITOS
        // ====================================
        $this->command->info('🛒 Creando carritos...');
        
        $users = User::all();
        $products = Product::where('available', true)->get();

        // 30% de usuarios tienen carrito
        $users->random(min(15, $users->count()))->each(function ($user) use ($products) {
            $cart = Cart::factory()->create(['user_id' => $user->id]);

            $products->random(min(rand(1, 5), $products->count()))->each(function ($product) use ($cart) {
                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'unit_price' => $product->price,
                ]);
            });
        });

        $this->command->info('✓ Carritos: ' . Cart::count());

        // ====================================
        // 8. ÓRDENES
        // ====================================
        $this->command->info('📋 Creando órdenes...');
        
        $paymentMethodsCollection = PaymentMethod::where('active', true)->get();

        for ($i = 0; $i < 100; $i++) {
            $user = $users->random();
            $userAddress = $user->addresses()->where('is_main', true)->first() 
                ?? $user->addresses()->first();

            if (!$userAddress) continue;

            $order = Order::factory()->create([
                'user_id' => $user->id,
                'user_address_id' => $userAddress->id,
            ]);

            // 1-5 items por orden
            $orderProducts = $products->random(min(rand(1, 5), $products->count()));
            $subtotal = 0;

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $itemSubtotal = $quantity * $product->price;
                $subtotal += $itemSubtotal;

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Actualizar totales
            $tax = $subtotal * 0.19;
            $shippingCost = rand(5000, 15000);
            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $tax + $shippingCost,
            ]);

            // Historial de estados
            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
            $currentStatusIndex = array_search($order->status, $statuses);

            for ($j = 0; $j <= $currentStatusIndex; $j++) {
                OrderStatus::factory()->create([
                    'order_id' => $order->id,
                    'status' => $statuses[$j],
                    'changed_at' => now()->subDays($currentStatusIndex - $j),
                ]);
            }

            // Crear pago
            Payment::factory()->create([
                'order_id' => $order->id,
                'payment_method_id' => $paymentMethodsCollection->random()->id,
                'amount' => $order->total,
                'status' => $order->status === 'delivered' ? 'completed' : 'pending',
                'paid_at' => $order->status === 'delivered' ? $order->ordered_at : null,
            ]);
        }

        $this->command->info('✓ Órdenes: ' . Order::count());

        // ====================================
        // 9. RESEÑAS
        // ====================================
        $this->command->info('⭐ Creando reseñas...');
        
        // Reseñas de productos
        $products->random(min(80, $products->count()))->each(function ($product) use ($users) {
            $reviewCount = rand(1, 5);
            $reviewUsers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($reviewUsers as $user) {
                ProductReview::factory()->create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ]);
            }

            // Recalcular rating promedio
            $product->update([
                'average_rating' => $product->reviews()->avg('rating'),
            ]);
        });

        // Reseñas de servicios
        $services = Service::all();
        $services->random(min(30, $services->count()))->each(function ($service) use ($users) {
            $reviewCount = rand(1, 4);
            $reviewUsers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($reviewUsers as $user) {
                ServiceReview::factory()->create([
                    'service_id' => $service->id,
                    'user_id' => $user->id,
                ]);
            }

            // Recalcular rating promedio
            $service->update([
                'average_rating' => $service->reviews()->avg('rating'),
            ]);
        });

        // Reseñas de emprendedores
        $entrepreneurs->random(min(20, $entrepreneurs->count()))->each(function ($entrepreneur) use ($users) {
            $reviewCount = rand(1, 8);
            $reviewUsers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($reviewUsers as $user) {
                EntrepreneurReview::factory()->create([
                    'entrepreneur_id' => $entrepreneur->id,
                    'user_id' => $user->id,
                ]);
            }

            // Recalcular rating promedio
            $entrepreneur->update([
                'average_rating' => $entrepreneur->reviews()->avg('rating'),
            ]);
        });

        $this->command->info('✓ Reseñas de productos: ' . ProductReview::count());
        $this->command->info('✓ Reseñas de servicios: ' . ServiceReview::count());
        $this->command->info('✓ Reseñas de emprendedores: ' . EntrepreneurReview::count());

        // ====================================
        // 10. FAVORITOS
        // ====================================
        $this->command->info('❤️ Creando favoritos...');
        
        // Favoritos de productos
        $users->random(min(30, $users->count()))->each(function ($user) use ($products) {
            $favoriteProducts = $products->random(min(rand(1, 10), $products->count()));
            
            foreach ($favoriteProducts as $product) {
                ProductFavorite::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        });

        // Favoritos de servicios
        $users->random(min(20, $users->count()))->each(function ($user) use ($services) {
            $favoriteServices = $services->random(min(rand(1, 5), $services->count()));
            
            foreach ($favoriteServices as $service) {
                ServiceFavorite::factory()->create([
                    'user_id' => $user->id,
                    'service_id' => $service->id,
                ]);
            }
        });

        $this->command->info('✓ Favoritos de productos: ' . ProductFavorite::count());
        $this->command->info('✓ Favoritos de servicios: ' . ServiceFavorite::count());

        // ====================================
        // RESUMEN FINAL
        // ====================================
        $this->command->info('');
        $this->command->info('✅ Seeding completado exitosamente!');
        $this->command->info('================================');
        $this->command->info('📊 Resumen de datos creados:');
        $this->command->info('   • Usuarios: ' . User::count());
        $this->command->info('   • Emprendedores: ' . Entrepreneur::count());
        $this->command->info('   • Productos: ' . Product::count());
        $this->command->info('   • Servicios: ' . Service::count());
        $this->command->info('   • Órdenes: ' . Order::count());
        $this->command->info('   • Carritos: ' . Cart::count());
        $this->command->info('================================');
    }
}