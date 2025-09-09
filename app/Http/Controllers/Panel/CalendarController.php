<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $users = User::select('id', 'name' , 'gender')->get();

        return view('panel.calendar')->with(compact('thispage' , 'users'));
    }

    public function store(Request $request){

        $success = true;
        $flag    = 'success';
        $subject = 'عملیات موفق';
        $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }
}
