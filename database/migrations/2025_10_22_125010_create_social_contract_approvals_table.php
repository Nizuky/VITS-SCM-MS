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
        Schema::create('social_contract_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contract_record_id')->constrained('social_contract_records')->onDelete('cascade');
            $table->string('student_id');
            $table->string('student_name');
            $table->string('event_name');
            $table->string('organization');
            $table->string('venue')->nullable();
            $table->integer('hours_rendered');
            $table->date('date');
            $table->enum('status', ['Verified', 'Approved', 'Rejected'])->default('Verified');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admin_users')->onDelete('set null'); // Admin who verified
            $table->foreignId('approved_by')->nullable()->constrained('super_admins')->onDelete('set null'); // Super admin who approved/rejected
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contract_approvals');
    }
};
