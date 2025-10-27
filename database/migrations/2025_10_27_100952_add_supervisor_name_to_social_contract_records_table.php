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
            $table->string('supervisor_name')->nullable()->after('organization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_contract_records', function (Blueprint $table) {
            $table->dropColumn('supervisor_name');
        });
    }
};
