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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('کاربر ');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('company_name')->nullable()->comment('نام رسمی شرکت');
            $table->string('commercial_name')->nullable()->comment('نام تجاری شرکت (در صورت وجود)');
            $table->string('registration_number')->nullable()->comment('شماره ثبت شرکت');
            $table->date('registration_date')->nullable()->comment('تاریخ ثبت شرکت');
            $table->string('national_id')->nullable()->comment('شناسه ملی شرکت');
            $table->string('economic_code')->nullable()->comment('کد اقتصادی شرکت');
            $table->string('legal_type')->nullable()->comment('نوع شخصیت حقوقی (مانند سهامی خاص، مسئولیت محدود و...)');
            $table->string('phone')->nullable()->comment('شماره تلفن شرکت');
            $table->string('email')->nullable()->comment('ایمیل شرکت');
            $table->string('website')->nullable()->comment('وب‌سایت شرکت');
            $table->string('province')->nullable()->comment('استان محل استقرار شرکت');
            $table->string('city')->nullable()->comment('شهر محل استقرار شرکت');
            $table->text('address')->nullable()->comment('آدرس کامل شرکت');
            $table->string('postal_code')->nullable()->comment('کد پستی شرکت');
            $table->string('ceo_name')->nullable()->comment('نام مدیرعامل یا نماینده شرکت');
            $table->string('ceo_national_code')->nullable()->comment('کد ملی مدیرعامل یا نماینده شرکت');
            $table->boolean('is_verified')->default(false)->comment('وضعیت تایید شرکت توسط مدیر سامانه');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
