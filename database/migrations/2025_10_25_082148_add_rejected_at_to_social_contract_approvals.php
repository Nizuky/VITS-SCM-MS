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
        Schema::table('social_contract_approvals', function (Blueprint $table) {
            if (!Schema::hasColumn('social_contract_approvals', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_contract_approvals', function (Blueprint $table) {
            if (Schema::hasColumn('social_contract_approvals', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};
