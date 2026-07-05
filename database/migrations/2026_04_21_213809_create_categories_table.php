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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());

            $table->uuid('parent_id')->nullable();

            $table->string('name', 150);
            $table->string('slug', 150)->unique();

            $table->text('description')->nullable();
            $table->text('image_url')->nullable();

            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);


            $table->index('parent_id');

            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
