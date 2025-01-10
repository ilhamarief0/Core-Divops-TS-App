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
        Schema::create('client_website_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->boolean('is_active')->default(true);
            $table->foreignId('client_monitoring_id')->constrained('client_monitorings')->onDelete('cascade');
            $table->foreignId('website_monitoring_type_id')->constrained('website_monitoring_types')->onDelete('cascade');
            $table->integer('warning_threshold')->default(5000);
            $table->integer('down_threshold')->default(10000);
            $table->integer('notify_user_interval')->default(5);
            $table->timestamp('last_check_at')->nullable(); // Ubah ke timestamp
            $table->timestamp('last_notify_user_at')->nullable();
            $table->timestamp('visibility')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_website_monitorings');
    }
};
