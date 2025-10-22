<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table tracks when a verified social contract record is approved (final approval).
     * This is the final stage after verification.
     */
    public function up(): void
    {
        Schema::create('social_contract_record_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_record_id')
                ->constrained('social_contract_records', 'id', 'scr_approve_record_fk')
                ->onDelete('cascade');
            $table->foreignId('approved_by')
                ->constrained('admin_users', 'id', 'scr_approve_admin_fk')
                ->onDelete('cascade');
            $table->timestamp('approved_at');
            $table->text('approval_notes')->nullable();
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['social_contract_record_id', 'approved_at'], 'scr_approve_record_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contract_record_approvals');
    }
};
