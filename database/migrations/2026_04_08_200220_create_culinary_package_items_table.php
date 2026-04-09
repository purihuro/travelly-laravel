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
        if (Schema::hasTable('culinary_package_items')) {
            return;
        }

        Schema::create('culinary_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('culinary_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('culinary_menu_item_id')->constrained('culinary_menu_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['culinary_package_id', 'culinary_menu_item_id'], 'uniq_pkg_item');
            $table->index(['culinary_package_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culinary_package_items');
    }
};
