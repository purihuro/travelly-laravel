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
        if (! Schema::hasTable('culinary_packages')) {
            return;
        }

        Schema::table('culinary_packages', function (Blueprint $table) {
            if (! Schema::hasColumn('culinary_packages', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('price_per_person');
            }
            if (! Schema::hasColumn('culinary_packages', 'final_price')) {
                $table->decimal('final_price', 12, 2)->nullable()->after('discount_amount');
            }
            if (! Schema::hasColumn('culinary_packages', 'availability_from')) {
                $table->date('availability_from')->nullable()->after('final_price');
            }
            if (! Schema::hasColumn('culinary_packages', 'availability_to')) {
                $table->date('availability_to')->nullable()->after('availability_from');
            }
            if (! Schema::hasColumn('culinary_packages', 'max_bookings')) {
                $table->integer('max_bookings')->nullable()->after('availability_to');
            }
            if (! Schema::hasColumn('culinary_packages', 'current_bookings')) {
                $table->integer('current_bookings')->default(0)->after('max_bookings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('culinary_packages')) {
            return;
        }

        Schema::table('culinary_packages', function (Blueprint $table) {
            $columns = ['discount_amount', 'final_price', 'availability_from', 'availability_to', 'max_bookings', 'current_bookings'];
            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('culinary_packages', $column)));
            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
