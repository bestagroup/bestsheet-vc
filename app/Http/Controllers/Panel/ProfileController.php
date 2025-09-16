<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Commitment;
use App\Models\Company;
use App\Models\MediaFile;
use App\Models\Minute;
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

        $company        = Auth::user()->company;
        $commitments    = Commitment::whereStatus(4)->get();
        $investsteps    = DB::table('investsteps')->get();
        if($company) {
            $projects       = Project::with('company')->where('company_id', $company->id)->get();
            $files          = MediaFile::where('company_id', $company->id)->whereRole(1)->get();
            $minutes        = Minute::where('company_id', $company->id)->get();
        }else{
            $projects       = null;
            $investsteps    = null;
            $files          = null;
            $minutes        = null;
    }
        return view('panel.profile')->with(compact('thispage' , 'projects' , 'company' , 'investsteps' , 'files' , 'minutes' , 'commitments'));
    }
}
