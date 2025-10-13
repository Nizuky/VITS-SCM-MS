<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            // use default id() primary key
            $table->id();
            $table->foreignId('contract_id')->constrained('social_contracts')->onDelete('cascade');
            // supervisor can be null; ensure nullable before applying FK and null-on-delete
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verification_date')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
