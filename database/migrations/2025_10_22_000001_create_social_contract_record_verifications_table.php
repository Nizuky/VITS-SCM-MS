<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table tracks when a social contract record is verified by an admin.
     * Each record can have multiple verification attempts tracked.
     */
    public function up(): void
    {
        Schema::create('social_contract_record_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_record_id')
                ->constrained('social_contract_records', 'id', 'scr_verif_record_fk')
                ->onDelete('cascade');
            $table->foreignId('verified_by')
                ->constrained('admin_users', 'id', 'scr_verif_admin_fk')
                ->onDelete('cascade');
            $table->timestamp('verified_at');
            $table->text('verification_notes')->nullable();
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['social_contract_record_id', 'verified_at'], 'scr_verif_record_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contract_record_verifications');
    }
};
