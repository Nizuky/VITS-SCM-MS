<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('superadmin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('super_admin_id')->nullable()->constrained('super_admins')->onDelete('cascade');
            $table->string('action');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('superadmin_activity_logs');
    }
};
