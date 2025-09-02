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
        Schema::create('minutes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->comment('شرکت ');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('title')->comment('عنوان');
            $table->string('date')->comment('تاریخ');
            $table->string('type')->comment('نوع');
            $table->string('file_path')->comment('فایل');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutes');
    }
};
