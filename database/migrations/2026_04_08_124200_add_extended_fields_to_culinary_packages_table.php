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
            if (! Schema::hasColumn('culinary_packages', 'image')) {
                $table->string('image')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('culinary_packages', 'preparation_time')) {
                $table->unsignedInteger('preparation_time')->nullable()->after('price_per_person');
            }
            if (! Schema::hasColumn('culinary_packages', 'serving_size')) {
                $table->string('serving_size', 100)->nullable()->after('preparation_time');
            }
            if (! Schema::hasColumn('culinary_packages', 'rating')) {
                $table->decimal('rating', 3, 2)->nullable()->default(null)->after('serving_size');
            }
            if (! Schema::hasColumn('culinary_packages', 'availability_status')) {
                $table->string('availability_status', 50)->default('available')->after('rating');
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
            $columns = ['image', 'preparation_time', 'serving_size', 'rating', 'availability_status'];
            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('culinary_packages', $column)));
            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
