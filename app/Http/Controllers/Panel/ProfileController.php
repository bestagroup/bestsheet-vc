<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Commitment;
use App\Models\Company;
use App\Models\MediaFile;
use App\Models\Minute;
use App\Models\Project;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
        $states         = State::select('id' , 'title')->whereStatus(4)->orderBy('title')->get();
        $cities         = City::select('id' , 'title')->whereStatus(4)->orderBy('title')->get();
        //$company        = Auth::user()->project;
        $commitments    = Commitment::whereStatus(4)->get();
        $investsteps    = DB::table('investsteps')->get();
        if(Auth::user()->project) {
            $project        = Auth::user()->project;
            $files          = MediaFile::where('project_id', $project->id)->whereRole(1)->get();
            $minutes        = Minute::where('project_id', $project->id)->get();
        }else{
            $project       = null;
            $investsteps    = null;
            $files          = null;
            $minutes        = null;
    }
        return view('panel.profile')->with(compact('thispage' , 'project' , 'investsteps' , 'files' , 'minutes' , 'commitments','states' , 'cities'));
    }

    public function userdata(Request $request)
    {
        if ($request->ajax()) {
            $data = User::leftjoin('roles' , 'roles.id' , '=','users.role_id' )
                ->where('users.id' , Auth::user()->id)
                ->select('users.id' ,'users.name' ,'users.level' ,'users.national_id' ,'users.father_name' ,
                    'users.email' ,'users.phone' ,'users.gender' ,'users.postalcode' ,'users.status' ,'users.address' , 'roles.title_fa as role_name')
                ->get();

            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->id);
                })
                ->addColumn('name', function ($data) {
                    return ($data->name?? '');
                })
                ->addColumn('userlevel', function ($data) {
                    if ($data->level == "admin") {
                        return "مدیر (سرمایه گذار)";
                    } elseif ($data->level == "applicant") {
                        return "مدیرعامل (سرمایه پذیر)";
                    }
                })
                ->addColumn('national_id', function ($data) {
                    return ($data->national_id?? '');
                })
                ->addColumn('father_name', function ($data) {
                    return ($data->father_name?? '');
                })
                ->addColumn('email', function ($data) {
                    return ($data->email?? '');
                })
                ->addColumn('phone', function ($data) {
                    return ($data->phone?? '');
                })
                ->addColumn('gender', function ($data) {
                    return ($data->gender?? '');
                })
                ->addColumn('postalcode', function ($data) {
                    return ($data->postalcode?? '');
                })
                ->addColumn('status', function ($data) {
                    return ($data->status?? '');
                })
                ->addColumn('role_name', function ($data) {
                    return ($data->role_name?? '');
                })
                ->addColumn('address', function ($data) {
                    return ($data->address?? '');
                })
                ->make(true);
        }
    }

}
