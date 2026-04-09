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
        if (! Schema::hasTable('culinary_venues')) {
            return;
        }

        Schema::table('culinary_venues', function (Blueprint $table) {
            if (! Schema::hasColumn('culinary_venues', 'phone')) {
                $table->string('phone', 50)->nullable()->after('location');
            }
            if (! Schema::hasColumn('culinary_venues', 'website')) {
                $table->string('website', 255)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('culinary_venues', 'cuisine_type')) {
                $table->string('cuisine_type', 100)->nullable()->after('website');
            }
            if (! Schema::hasColumn('culinary_venues', 'opening_time')) {
                $table->string('opening_time', 10)->nullable()->after('cuisine_type');
            }
            if (! Schema::hasColumn('culinary_venues', 'closing_time')) {
                $table->string('closing_time', 10)->nullable()->after('opening_time');
            }
            if (! Schema::hasColumn('culinary_venues', 'rating')) {
                $table->decimal('rating', 3, 2)->nullable()->default(null)->after('closing_time');
            }
            if (! Schema::hasColumn('culinary_venues', 'review_count')) {
                $table->unsignedInteger('review_count')->default(0)->after('rating');
            }
            if (! Schema::hasColumn('culinary_venues', 'capacity')) {
                $table->unsignedInteger('capacity')->nullable()->after('review_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('culinary_venues')) {
            return;
        }

        Schema::table('culinary_venues', function (Blueprint $table) {
            $columns = ['phone', 'website', 'cuisine_type', 'opening_time', 'closing_time', 'rating', 'review_count', 'capacity'];
            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('culinary_venues', $column)));
            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
