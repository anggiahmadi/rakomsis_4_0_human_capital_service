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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('work_agreement_id')->unsigned()->index();
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->string('employee_name');
            $table->integer('year'); // e.g. 2024
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration')->default(0); // in days
            $table->enum('type', ['sick', 'vacation', 'maternity', 'paternity', 'unpaid', 'other'])->default('vacation');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('responsed_by')->nullable();
            $table->timestamp('responsed_at')->nullable();
            $table->timestamps();
            $table->foreign('work_agreement_id')->references('id')->on('work_agreements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
