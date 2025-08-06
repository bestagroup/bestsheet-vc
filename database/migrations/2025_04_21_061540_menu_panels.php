<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('menu_panels', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا پنل منو');
            $table->unsignedBigInteger('priority')->comment('شماره اولویت');
            $table->string('label')->comment('عنوان فارسی پنل منو');
            $table->string('title')->comment('عنوان پنل منو');
            $table->string('slug')->comment('آدرس منو یا شناسه منو');
            $table->string('icon')->nullable()->comment('آیکون پنل منو');
            $table->boolean('submenu')->default(0)->comment('آیا منو زیرمنو دارد؟');
            $table->string('class')->nullable()->comment('کلاس لاراول');
            $table->string('level')->nullable()->comment('سطح نمایش');
            $table->string('controller')->nullable()->comment('کنترلر مرتبط با منو');
            $table->boolean('status')->default(0)->comment('وضعیت منو (فعال/غیرفعال)');
            $table->unsignedBigInteger('user_id')->comment('شناسه کاربری که این نقش را ایجاد کرده');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        $menuPanels = [
            [1, 1, 'داشبورد'        , 'dashboard'       , 'dashboard'       , null  , 0 , 'index'   , null, 'IndexController'   , 4 , 1],
            [2, 2, 'مدیریت داشبورد' , 'dashboard manage', 'dashboard-manage', null  , 1 , 'panel'   , null, 'IndexController'   , 4 , 1],
            [3, 3, 'مدیریت کاربران' , 'user manage'     , 'user-manage'     , null  , 1 , 'index'   , null, 'UserController'    , 4 , 1],
            [4, 4, 'مدیریت سایت'    , 'site manage'     , 'site-manage'     , null  , 1 , 'index'   , null, 'SiteController'    , 4 , 1],
            [5, 5, 'مدیریت فایل ها' , 'file manage'     , 'file-manage'     , null  , 1 , 'index'   , null, 'FileController'    , 4 , 1],
            [6, 6, 'مدیریت تنظیمات' , 'config manage'   , 'config-manage'   , null  , 0 , 'index'   , null, 'SettingController' , 4 , 1],
        ];

        DB::table('menu_panels')->insert(
            array_map(fn($c) => [
                'id'         => $c[0],
                'priority'   => $c[1],
                'label'      => $c[2],
                'title'      => $c[3],
                'slug'       => $c[4],
                'icon'       => $c[5],
                'submenu'    => $c[6],
                'class'      => $c[7],
                'level'      => $c[8],
                'controller' => $c[9],
                'status'     => $c[10],
                'user_id'    => $c[11],
                'created_at' => now(),
                'updated_at' => now(),
            ], $menuPanels));
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_panels');
    }
};
