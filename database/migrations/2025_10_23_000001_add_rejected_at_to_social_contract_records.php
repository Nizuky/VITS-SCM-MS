<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_contract_records', function (Blueprint $table) {
            if (!Schema::hasColumn('social_contract_records', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_contract_records', function (Blueprint $table) {
            if (Schema::hasColumn('social_contract_records', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};
