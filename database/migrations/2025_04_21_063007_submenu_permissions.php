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
        Schema::create('submenu_permissions', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا دسترسی به زیرمنو');
            $table->unsignedBigInteger('submenu_id')->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('submenu_id')->references('id')->on('submenu_panels')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('can_create')->default(0)->comment('دسترسی به افزودن');
            $table->boolean('can_edit')->default(0)->comment('دسترسی به ویرایش');
            $table->boolean('can_delete')->default(0)->comment('دسترسی به حذف');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submenu_permissions');
    }
};
