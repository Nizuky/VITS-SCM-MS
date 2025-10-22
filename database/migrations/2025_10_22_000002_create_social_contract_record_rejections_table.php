<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table tracks when a social contract record is rejected by an admin.
     * Includes reason for rejection which can be communicated to the student.
     */
    public function up(): void
    {
        Schema::create('social_contract_record_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_record_id')
                ->constrained('social_contract_records', 'id', 'scr_reject_record_fk')
                ->onDelete('cascade');
            $table->foreignId('rejected_by')
                ->constrained('admin_users', 'id', 'scr_reject_admin_fk')
                ->onDelete('cascade');
            $table->timestamp('rejected_at');
            $table->text('rejection_reason')->nullable();
            $table->text('rejection_notes')->nullable();
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['social_contract_record_id', 'rejected_at'], 'scr_reject_record_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contract_record_rejections');
    }
};
