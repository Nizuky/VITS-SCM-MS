<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table tracks all status changes for social contract records.
     * Provides a complete audit trail of status transitions.
     */
    public function up(): void
    {
        Schema::create('social_contract_record_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_record_id')
                ->constrained('social_contract_records', 'id', 'scr_history_record_fk')
                ->onDelete('cascade');
            $table->enum('old_status', ['Pending', 'Verified', 'Rejected', 'Approved'])->nullable();
            $table->enum('new_status', ['Pending', 'Verified', 'Rejected', 'Approved']);
            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('admin_users', 'id', 'scr_history_admin_fk')
                ->nullOnDelete();
            $table->timestamp('changed_at');
            $table->text('change_reason')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['social_contract_record_id', 'changed_at'], 'scr_history_record_date_idx');
            $table->index('new_status', 'scr_history_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contract_record_status_history');
    }
};
