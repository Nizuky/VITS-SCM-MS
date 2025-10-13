<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_contracts', function (Blueprint $table) {
            // use default id() primary key so other tables can reference social_contracts.id
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('submission_date')->nullable();
            $table->string('status')->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_contracts');
    }
};
