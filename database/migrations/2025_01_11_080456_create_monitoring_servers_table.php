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
        Schema::create('monitoring_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Server name');
            $table->string('ip_address')->comment('Server IP address');
            $table->unsignedInteger('port')->comment('Port number to monitor');
            $table->string('status')->default('Unknown')->comment('Server status: Operational or Down');
            $table->decimal('uptime_percentage', 5, 2)->default(0.00)->comment('Server uptime percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_servers');
    }
};
