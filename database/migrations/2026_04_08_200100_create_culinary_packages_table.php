<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('culinary_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('culinary_venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_per_person', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2)->nullable();
            $table->unsignedInteger('preparation_time')->nullable();
            $table->string('serving_size', 100)->nullable();
            $table->decimal('rating', 3, 2)->nullable()->default(null);
            $table->date('availability_from')->nullable();
            $table->date('availability_to')->nullable();
            $table->integer('max_bookings')->nullable();
            $table->integer('current_bookings')->default(0);
            $table->string('availability_status', 50)->default('available');
            $table->unsignedInteger('min_people')->default(1);
            $table->unsignedInteger('max_people')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('culinary_packages');
    }
};
