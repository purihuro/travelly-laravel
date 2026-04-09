<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('culinary_venues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('location', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('cuisine_type', 100)->nullable();
            $table->string('opening_time', 10)->nullable();
            $table->string('closing_time', 10)->nullable();
            $table->decimal('rating', 3, 2)->nullable()->default(null);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('capacity')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('culinary_venues');
    }
};
