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
            $table->string('title')->comment('عنوان شاخص');
            $table->string('type')->comment('شاخص مبنا');
            $table->string('type_value')->comment('شاخص اندازه گیری');
            $table->string('value')->comment('مقدار');
            $table->string('time_step')->comment('زمان شاخص');
            $table->unsignedBigInteger('project_id')->nullable()->comment('شرکت ');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('factor_number')->comment('شماره شاخص');
            $table->string('file_link')->comment('لینک مستند شاخص');
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
