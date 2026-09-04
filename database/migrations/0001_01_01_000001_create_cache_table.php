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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('custom_code_generators', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->string('table_name');
            $table->string('code_format');
            $table->string('type');
            $table->timestamps();
            $table->unique(['tenant_id', 'table_name']);
        });

        Schema::create('custom_last_numbers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tenant_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->nullable()->index();
            $table->string('table_name');
            $table->integer('year');
            $table->integer('last_number')->default(1);
            $table->string('type');
            $table->timestamps();
            $table->unique(['tenant_id', 'location_id', 'table_name', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('custom_code_generators');
        Schema::dropIfExists('custom_last_numbers');
    }
};
