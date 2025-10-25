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
        Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('category');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('stock')->default(0);
                $table->string('main_image')->nullable();
                $table->json('gallery_images')->nullable();

                // ⭐ CAMPOS PARA API
                $table->enum('status', ['active', 'inactive', 'draft', 'out_of_stock'])->default('active');
                $table->boolean('featured')->default(false);
                $table->decimal('discount_percentage', 5, 2)->nullable();
                $table->unsignedBigInteger('views')->default(0);

                // Foreign Keys
                $table->foreignId('entrepreneur_id')->constrained('entrepreneurs')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

                $table->timestamps();

                // ⭐ SOFT DELETES PARA API
                $table->softDeletes();

                // ⭐ ÍNDICES PARA OPTIMIZACIÓN
                $table->index('category');
                $table->index('price');
                $table->index('stock');
                $table->index('status');
                $table->index('featured');
                $table->index('views');
            });
        }

        /**
        * Reverse the migrations.
        */
        public function down(): void
        {
            Schema::dropIfExists('products');
        }
    };