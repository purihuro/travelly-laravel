<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('title', 150);
            $table->string('category', 100);
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->unsignedInteger('duration_days')->default(1);
            $table->unsignedInteger('quota')->default(0);
            $table->string('location', 150)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
