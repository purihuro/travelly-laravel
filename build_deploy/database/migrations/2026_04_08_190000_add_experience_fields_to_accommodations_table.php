<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->string('room_type', 120)->nullable()->after('type');
            $table->string('highlight', 160)->nullable()->after('location');
            $table->text('amenities')->nullable()->after('highlight');
        });
    }

    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['room_type', 'highlight', 'amenities']);
        });
    }
};
