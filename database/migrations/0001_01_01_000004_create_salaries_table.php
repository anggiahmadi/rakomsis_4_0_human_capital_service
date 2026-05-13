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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('work_agreement_id')->unsigned()->index();
            $table->bigInteger('employee_id')->unsigned()->index(); // auto populate from work agreement
            $table->string('employee_name');
            $table->double('basic_salary')->default(0);
            $table->json('allowances')->nullable(); // e.g. {"transportation": 100, "meal": 50}
            $table->json('deductions')->nullable(); // e.g. {"tax": 150, "social_security": 75}
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->enum('payment_frequency', ['weekly', 'biweekly', 'monthly', 'yearly'])->default('monthly');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['tenant_id', 'employee_id', 'start_date']);
            $table->foreign('work_agreement_id')->references('id')->on('work_agreements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
