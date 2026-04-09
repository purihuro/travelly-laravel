<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_culinaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('culinary_venue_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('culinary_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('venue_name', 150);
            $table->string('package_name', 150);
            $table->date('reservation_date');
            $table->time('arrival_time');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_culinaries');
    }
};
