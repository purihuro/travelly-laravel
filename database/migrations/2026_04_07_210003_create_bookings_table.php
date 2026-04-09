<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->string('customer_first_name', 100);
            $table->string('customer_last_name', 100);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 50);
            $table->string('country', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('address_line_1', 180)->nullable();
            $table->string('address_line_2', 180)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->date('departure_date')->nullable();
            $table->unsignedInteger('participants')->default(1);
            $table->string('payment_method', 50);
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('booking_status', 30)->default('pending');
            $table->string('payment_status', 30)->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
