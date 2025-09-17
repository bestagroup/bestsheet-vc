<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Site\Exception;
use App\Models\Company;
use App\Models\MenuPanel;
use App\Models\SubmenuPanel;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SiteuserController extends Controller
{
    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'submenu' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $typeusers      = TypeUser::all();
        $companies      = Company::select('id' , 'company_name','commercial_name' , 'user_id')->orderBy('company_name' , 'ASC')->get();
        $users          = User::select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'users.status' , 'users.level' , 'users.birthday' , 'users.national_id' , 'users.role_id' , 'roles.title_fa', 'users.gender')
            ->leftjoin('role_user' , 'role_user.user_id' , '=' , 'users.id')
            ->leftjoin('roles' , 'roles.id' , '=' , 'role_user.role_id')
            ->where('users.level','=','applicant')->get();
        $thispage       = [
            'title'   => 'مدیریت  کاربران شرکت ها ',
            'list'    => 'لیست  کاربران شرکت ها ',
            'add'     => 'افزودن  کاربران شرکت ها ',
            'create'  => 'ایجاد  کاربران شرکت ها ',
            'enter'   => 'ورود  کاربران شرکت ها ',
            'edit'    => 'ویرایش  کاربران شرکت ها ',
            'delete'  => 'حذف  کاربران شرکت ها ',
        ];

        if ($request->ajax()) {
            $data = User::leftjoin('roles' , 'roles.id' , '=' , 'users.role_id')
                ->select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'roles.title_fa' , 'users.status')
                ->where('users.level','=','applicant')->get();

            return Datatables::of($data)
                ->addColumn('name', function ($data) {
                    return ($data->name);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title_fa);
                })
                ->addColumn('email', function ($data) {
                    return ($data->email);
                })
                ->addColumn('phone', function ($data) {
                    return ($data->phone);
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == "0") {
                        return "غیر فعال";
                    } elseif ($data->status == "4") {
                        return "فعال";
                    }
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'" id="#deletesubmit_'.$data->id.'" data-id="#deletesubmit_'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.siteuser')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'users' , 'typeusers' , 'companies']));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $validator->validate();

            $user = new User();
            $user->name         = $request->input('name');
            $user->phone        = $request->input('phone');
            $user->email        = $request->input('email');
            $user->role_id      = 5;
            $user->gender       = $request->input('gender');
            $user->level        = 'applicant';
            $user->status       = 4;
            $user->password     = Hash::make($request->input('password'));
            $result1 = $user->save();

            Company::whereId($request->input('company_id'))->update([
                'user_id' => $user->id,
            ]);


            DB::table('role_user')->insert([
                'role_id' => $user->role_id,
                'user_id' => $user->id,
            ]);

            if ($result1 == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات منو با موفقیت ثبت شد';
            }
            elseif($result1 == true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات دسترسی ثبت نشد، لطفا مجددا تلاش نمایید';
            }
            elseif($result1 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات منو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات منو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }
        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }
    public function update(Request $request)
    {
        $user               = User::findOrfail($request->input('user_id'));
        $user->name         = $request->input('name');
        $user->phone        = $request->input('phone');
        $user->email        = $request->input('email');
        $user->gender       = $request->input('gender');
        if ($request->input('password')) {
            $user->password     = Hash::make($request->input('password'));
        }

        $result = $user->update();

        Company::whereId($request->input('company_id'))->update([
            'user_id' => $request->input('user_id'),
        ]);

        try{
            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت ثبت شد';
            }
            else {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::findOrfail($request->input('id'));
            $result1 = $user->delete();

            if ($result1 == true) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت پاک شد';
            }elseif($result1 == true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات دسترسی ثبت نشد، لطفا مجددا تلاش نمایید';
            }
            elseif($result1 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات منو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            $message = 'اطلاعات پاک نشد،لطفا بعدا مجدد تلاش نمایید ';
        }
        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }
}
