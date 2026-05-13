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
        Schema::create('salary_parameters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index()->nullable();
            $table->bigInteger('division_id')->unsigned()->index()->nullable();
            $table->string('location_name')->nullable();
            $table->string('division_name')->nullable();
            $table->enum('allotment', ['general', 'specific location', 'specific division'])->default('general');
            $table->enum('type', ['allowance', 'deduction'])->default('allowance');
            $table->string('name');
            $table->enum('calculation_method', ['fixed', 'percentage', 'using parameters'])->default('fixed');
            $table->double('amount')->default(0); // for 'fixed' method
            $table->double('percentage')->default(0); // for 'percentage' method
            $table->json('parameters')->nullable(); // e.g. {"position": ["Manager", "Supervisor"], "grade": ["A", "B"]}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_parameters');
    }
};
