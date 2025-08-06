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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا سطح دسترسی');
            $table->string('title')->comment('عنوان سطح دسترسی (نمایش در UI)');
            $table->string('label')->comment('عنوان سطح دسترسی (نمایش در UI)');
            $table->string('slug')->comment('نام یکتا سطح دسترسی برای استفاده در کد (مثلاً view-users)');
            $table->integer('menu_panel_id')->nullable()->comment('شناسه منوی اصلی که این دسترسی به آن مربوط می‌شود');
            $table->integer('submenu_panel_id')->nullable()->comment('شناسه زیرمنو (در صورت وجود)');
            $table->unsignedBigInteger('user_id')->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
