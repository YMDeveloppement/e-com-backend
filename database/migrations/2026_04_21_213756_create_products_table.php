<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());

            $table->uuid('category_id')->nullable();
            $table->uuid('brand_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();

            $table->longText('description')->nullable();
            $table->string('short_desc', 500)->nullable();

            $table->decimal('base_price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            $table->decimal('tax_rate', 5, 2)->default(20.00);
            $table->integer('weight_grams')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();

            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('sold_count')->default(0);


            // Indexes
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('is_active');

            // Fulltext (MySQL only)
            $table->fullText('name');

            // Foreign keys
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->nullOnDelete();
            $table->timestamps();
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
