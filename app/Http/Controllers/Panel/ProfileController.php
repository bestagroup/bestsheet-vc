<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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




        $company = Auth::user()->company;
    if($company) {
        $projects = Project::with('company')->whereId($company->id)->first();
        $investsteps = DB::table('investsteps')->get();
    }else{
        $projects = null;
        $investsteps = null;
    }
        return view('panel.profile')->with(compact('thispage' , 'projects' , 'company' , 'investsteps'));
    }
}
