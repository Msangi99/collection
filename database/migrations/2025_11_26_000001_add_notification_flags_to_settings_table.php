<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('enable_customer_sms_notifications')->default(true)->after('service_percentage');
            $table->boolean('enable_customer_email_notifications')->default(true)->after('enable_customer_sms_notifications');
            $table->boolean('enable_conductor_sms_notifications')->default(true)->after('enable_customer_email_notifications');
            $table->boolean('enable_conductor_email_notifications')->default(true)->after('enable_conductor_sms_notifications');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'enable_customer_sms_notifications',
                'enable_customer_email_notifications',
                'enable_conductor_sms_notifications',
                'enable_conductor_email_notifications',
            ]);
        });
    }
};

