<?php

use App\Models\TypeUser;
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
        Schema::create('type_users', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا نوع کاربر');
            $table->string('title')->comment('عنوان نوع کاربر به انگلیسی ');
            $table->string('title_fa')->nullable()->comment('عنوان نوع کاربر به فارسی یا نمایش در UI');
            $table->boolean('status')->default(0)->comment('وضعیت منو (فعال/غیرفعال)');
            $table->timestamps();
        });

        $user = TypeUser::create([
            'title'      => 'superadmin',
            'title_fa'   => 'کاربر اصلی',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_users');
    }
};
