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
        Schema::create('working_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index();
            $table->bigInteger('division_id')->unsigned()->index()->nullable();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->string('location_name');
            $table->string('division_name')->nullable();
            $table->string('employee_name')->nullable();
            $table->enum('type', ['office hours', 'shift'])->default('office hours');
            $table->enum('allotment', ['general', 'specific division', 'specific employee'])->default('general');
            $table->json('office_hours')->nullable(); // e.g. [["mon": "09:00-17:00"], ["tue": "09:00-17:00"], ...]
            $table->dateTime('shift_started_at')->nullable();
            $table->dateTime('shift_ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_times');
    }
};
