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
        Schema::table('social_contract_records', function (Blueprint $table) {
            // First, modify the status enum to include 'Approved'
            $table->enum('status', ['Pending', 'Verified', 'Approved', 'Rejected'])
                  ->default('Pending')
                  ->change();
            
            // Add verification tracking columns if they don't exist
            if (!Schema::hasColumn('social_contract_records', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('social_contract_records', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            }
            
            // Add approval tracking columns if they don't exist
            if (!Schema::hasColumn('social_contract_records', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('verified_by');
            }
            if (!Schema::hasColumn('social_contract_records', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            }
            
            // Add rejected_by if it doesn't exist (rejected_at should already exist)
            if (!Schema::hasColumn('social_contract_records', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            }
        });
        
        // Add foreign key constraints in a separate statement
        // Check if columns were just created before adding foreign keys
        Schema::table('social_contract_records', function (Blueprint $table) {
            try {
                $table->foreign('verified_by')->references('id')->on('admin_users')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key might already exist, skip
            }
            
            try {
                $table->foreign('approved_by')->references('id')->on('super_admins')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key might already exist, skip
            }
            // rejected_by can be either admin or superadmin, so we'll skip the foreign key for flexibility
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_contract_records', function (Blueprint $table) {
            // Drop foreign keys first if they exist
            try {
                $table->dropForeign(['verified_by']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            try {
                $table->dropForeign(['approved_by']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            // Drop columns if they exist
            $columnsToCheck = [
                'verified_at',
                'verified_by',
                'approved_at',
                'approved_by',
                'rejected_by'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('social_contract_records', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Revert status enum back to original
            $table->enum('status', ['Pending', 'Verified', 'Rejected'])
                  ->default('Pending')
                  ->change();
        });
    }
};
