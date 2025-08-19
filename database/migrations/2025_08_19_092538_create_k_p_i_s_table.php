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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->comment('شرکت ');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('factor_number')->comment('شماره شاخص');
            $table->string('file_link')->comment('لینک مستند شاخص');
            $table->string('title')->comment('عنوان');
            $table->string('time_step')->comment('زمان این مرحله');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
