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
        Schema::table('buses', function (Blueprint $table) {
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            $table->string('conductor_name')->nullable();
            $table->string('customer_service_name_1')->nullable();
            $table->string('customer_service_contact_1')->nullable();
            $table->string('customer_service_name_2')->nullable();
            $table->string('customer_service_contact_2')->nullable();
            $table->string('customer_service_name_3')->nullable();
            $table->string('customer_service_contact_3')->nullable();
            $table->string('customer_service_name_4')->nullable();
            $table->string('customer_service_contact_4')->nullable();
            $table->string('bus_model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn([
                'driver_name',
                'driver_contact',
                'conductor_name',
                'customer_service_name_1',
                'customer_service_contact_1',
                'customer_service_name_2',
                'customer_service_contact_2',
                'customer_service_name_3',
                'customer_service_contact_3',
                'customer_service_name_4',
                'customer_service_contact_4',
                'bus_model'
            ]);
        });
    }
};
