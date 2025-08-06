<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Site\Exception;
use App\Models\MenuPanel;
use App\Models\Role;
use App\Models\SubmenuPanel;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PaneluserController extends Controller
{
    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'submenu' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $roles          = Role::all();
        $users          = User::select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'users.status' , 'users.level' , 'users.birthday' , 'users.national_id' , 'users.role_id' , 'roles.title_fa', 'users.gender')
                                ->leftjoin('role_user' , 'role_user.user_id' , '=' , 'users.id')
                                ->leftjoin('roles' , 'roles.id' , '=' , 'role_user.role_id')
                                ->where('users.level','=','admin')->get();
        $thispage       = [
            'title'   => 'مدیریت  کاربران داشبورد ',
            'list'    => 'لیست  کاربران داشبورد ',
            'add'     => 'افزودن  کاربران داشبورد ',
            'create'  => 'ایجاد  کاربران داشبورد ',
            'enter'   => 'ورود  کاربران داشبورد ',
            'edit'    => 'ویرایش  کاربران داشبورد ',
            'delete'  => 'حذف  کاربران داشبورد ',
        ];

        if ($request->ajax()) {
            $data = User::leftjoin('roles' , 'roles.id' , '=' , 'users.role_id')
                ->select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'roles.title_fa' , 'users.status')
                ->where('users.level','=','admin')->get();

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
                    $actionBtn = '';
                    if (Gate::allows('can-access', ['paneluser', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary">
                        <i class="mdi mdi-pencil-outline"></i>
                      </button> ';
                    }
                    if (Gate::allows('can-access', ['paneluser', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $data->id . '" id="#deletesubmit_' . $data->id . '" data-id="#deletesubmit_' . $data->id . '">
                        <i class="mdi mdi-delete-outline"></i>
                       </button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.paneluser')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'users' , 'roles']));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'typeuser_id'   => ['required'],
                'name'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password'      => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $validator->validate();

            $user = new User();
            $user->name         = $request->input('name');
            $user->phone        = $request->input('phone');
            $user->email        = $request->input('email');
            $user->national_id  = $request->input('national_id');
//            $user->type_id      = $request->input('typeuser_id');
            $user->role_id      = $request->input('typeuser_id');
            $user->birthday     = $request->input('birthday');
            $user->gender       = $request->input('gender');
            $user->level        = 'admin';
            $user->status       = 4;
            $user->password     = Hash::make($request->input('password'));
            $result1 = $user->save();

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
        $user               = User::findOrfail($request->input('id'));
        $user->name         = $request->input('name');
        $user->phone        = $request->input('phone');
        $user->email        = $request->input('email');
        $user->national_id  = $request->input('national_id');
        $user->type_id      = $request->input('typeuser_id');
        $user->role_id      = $request->input('typeuser_id');
        $user->birthday     = $request->input('birthday');
        $user->gender       = $request->input('gender');
        if ($request->input('password')) {
            $user->password     = Hash::$request->input('password');
        }
        $user->status       = $request->input('status');
        $result = $user->update();
        $user->role()->sync($request->input('typeuser_id'));

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

    function convertPersianNumbersToEnglish($string) {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];

        $string = str_replace($persian, $english, $string);
        $string = str_replace($arabic, $english, $string);
        return $string;
    }

}
