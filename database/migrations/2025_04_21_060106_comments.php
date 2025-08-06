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
        Schema::create('comments', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا کامنت');
            $table->string('commentable_id')->comment('شناسه مدل مرتبط با کامنت');
            $table->string('commentable_type')->comment('نوع مدل مرتبط (مثلاً Post, Product و...)');
            $table->unsignedBigInteger('user_id')->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('شناسه کامنت والد برای ریپلای‌ها');
            $table->boolean('approved')->default(false)->comment('وضعیت تأیید کامنت');
            $table->text('comment')->comment('متن کامل کامنت');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
