<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $thispage       = [
            'title'   => 'مدیریت حساب کاربری',
            'list'    => 'لیست حساب کاربری',
            'add'     => 'افزودن حساب کاربری',
            'create'  => 'ایجاد حساب کاربری',
            'enter'   => 'ورود حساب کاربری',
            'edit'    => 'ویرایش حساب کاربری',
            'delete'  => 'حذف حساب کاربری',
        ];


        $projects = Auth::user()->project;

        return view('panel.profile')->with(compact('thispage' , 'projects'));
    }
}
