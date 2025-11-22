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
        Schema::table('refund', function (Blueprint $table) {
            $table->foreignId("booking_id")->nullable()->change();
            $table->string("amount")->nullable()->change();
            $table->string("status")->default("pending")->change();
            $table->string("phone")->nullable()->change();
            $table->string("fullname")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund', function (Blueprint $table) {
            //
        });
    }
};
