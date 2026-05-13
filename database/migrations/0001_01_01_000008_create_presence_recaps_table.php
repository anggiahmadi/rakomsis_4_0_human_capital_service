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
        Schema::create('presence_recaps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index();
            $table->bigInteger('division_id')->unsigned()->index()->nullable();
            $table->string('code');
            $table->string('location_name');
            $table->string('division_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_generated')->default(false);
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('presence_recap_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('presence_recap_id')->unsigned()->index();
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->string('employee_name');
            $table->integer('total_working_days')->default(0);
            $table->integer('total_leave_days')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->timestamps();
            $table->foreign('presence_recap_id')->references('id')->on('presence_recaps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_recaps');
        Schema::dropIfExists('presence_recap_details');
    }
};
