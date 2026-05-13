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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('presence_recap_id')->unsigned()->index()->nullable(); // type is regular if presence_recap_id is not null, bonus if presence_recap_id is null
            $table->bigInteger('location_id')->unsigned()->index(); // auto-filled from presence_recap if type is regular, must be filled if type is bonus
            $table->bigInteger('division_id')->unsigned()->index(); // auto-filled from presence_recap if type is regular, must be filled if type is bonus
            $table->bigInteger('employee_id')->unsigned()->index(); // auto-filled from presence_recap if type is regular, must be filled if type is bonus
            $table->enum('type', ['regular', 'bonus'])->default('regular');
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->string('location_name');
            $table->string('division_name');
            $table->string('employee_name');
            $table->date('payroll_date');
            $table->double('base_salary')->default(0);
            $table->double('total_allowances')->default(0);
            $table->double('total_deductions')->default(0);
            $table->double('net_salary')->default(0);
            $table->timestamps();
            $table->foreign('presence_recap_id')->references('id')->on('presence_recaps')->onDelete('set null');
        });

        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('payroll_id')->unsigned()->index();
            $table->bigInteger('salary_parameter_id')->unsigned()->index()->nullable(); // for bonus payroll, this can be null
            $table->string('parameter_name')->nullable(); // e.g. "transportation allowance", "tax deduction"
            $table->enum('type', ['allowance', 'deduction'])->default('allowance');
            $table->double('amount')->default(0);
            $table->timestamps();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('payroll_details');
    }
};
