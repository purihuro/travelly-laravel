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
        if (Schema::hasTable('culinary_menu_items')) {
            return;
        }

        Schema::create('culinary_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('culinary_venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 150);
            $table->text('description')->nullable();
            $table->enum('category', ['appetizer', 'main', 'side', 'drink', 'dessert'])->default('main');
            $table->decimal('price', 12, 2)->default(0);
            $table->json('ingredients')->nullable();
            $table->json('allergies')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['culinary_venue_id', 'slug']);
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culinary_menu_items');
    }
};
