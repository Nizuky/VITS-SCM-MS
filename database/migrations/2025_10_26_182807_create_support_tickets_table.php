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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('student_name');
            $table->string('issue_type');
            $table->text('details');
            $table->enum('status', ['Pending', 'Resolved', 'Closed'])->default('Pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index for faster queries
            $table->index('student_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
