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
        Schema::table('student_notifications', function (Blueprint $table) {
            // Make social_contract_record_id nullable
            $table->unsignedBigInteger('social_contract_record_id')->nullable()->change();
            
            // Add title column
            $table->string('title')->nullable()->after('social_contract_record_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_notifications', function (Blueprint $table) {
            // Revert social_contract_record_id to NOT NULL
            $table->unsignedBigInteger('social_contract_record_id')->nullable(false)->change();
            
            // Drop title column
            $table->dropColumn('title');
        });
    }
};
