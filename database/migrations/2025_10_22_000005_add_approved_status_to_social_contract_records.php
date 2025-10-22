<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add 'Approved' status to social_contract_records status enum.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the enum
        DB::statement("ALTER TABLE social_contract_records MODIFY COLUMN status ENUM('Pending', 'Verified', 'Rejected', 'Approved') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE social_contract_records MODIFY COLUMN status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending'");
    }
};
