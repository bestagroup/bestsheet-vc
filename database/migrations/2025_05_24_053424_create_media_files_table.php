<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('نام اصلی');
            $table->string('slug')->nullable()->comment('نام قابل استفاده');
            $table->string('original_name')->nullable()->comment('نام اصلی فایل');;
            $table->string('type')->nullable()->comment('نوع فایل');
            $table->text('file_path')->nullable()->comment('آدرس فایل');
            $table->bigInteger('size')->nullable()->comment('سایز فایل');
            $table->unsignedBigInteger('user_id')->nullable()->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
