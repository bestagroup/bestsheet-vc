<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        $thispage       = [
            'title'   => 'مدیریت رویداد تقویم',
            'list'    => 'لیست رویداد تقویم',
            'add'     => 'افزودن رویداد تقویم',
            'create'  => 'ایجاد رویداد تقویم',
            'enter'   => 'ورود رویداد تقویم',
            'edit'    => 'ویرایش رویداد تقویم',
            'delete'  => 'حذف رویداد تقویم',
        ];

        return view('panel.calendar')->with(compact('thispage'));
    }
}
