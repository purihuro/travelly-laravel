<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destination_tickets', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('location');
            $table->string('open_hours', 100)->nullable()->after('category');
            $table->unsignedInteger('duration_minutes')->nullable()->after('open_hours');
            $table->string('audience', 100)->nullable()->after('duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('destination_tickets', function (Blueprint $table) {
            $table->dropColumn(['category', 'open_hours', 'duration_minutes', 'audience']);
        });
    }
};
