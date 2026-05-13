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
        Schema::create('work_agreements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index();
            $table->bigInteger('division_id')->unsigned()->index();
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->string('code');
            $table->string('location_name');
            $table->string('division_name');
            $table->string('employee_name');
            $table->string('position');
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('yearly_leave_allowance')->default(0); // in days
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['tenant_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_agreements');
    }
};
