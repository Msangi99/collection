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
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('has_excess_luggage')->default(false)->after('age_group');
            $table->integer('excess_luggage_fee')->default(0)->after('has_excess_luggage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('has_excess_luggage');
            $table->dropColumn('excess_luggage_fee');
        });
    }
};
