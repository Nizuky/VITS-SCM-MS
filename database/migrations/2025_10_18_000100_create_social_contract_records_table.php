<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_contract_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_id')->constrained('social_contracts')->onDelete('cascade');
            $table->date('date');
            $table->string('event_name');
            $table->string('venue');
            $table->string('organization');
            $table->unsignedInteger('hours_rendered');
            $table->enum('status', ['Pending', 'Verified', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_contract_records');
    }
};
