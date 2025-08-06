<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Site\Exception;
use App\Models\MenuPanel;
use App\Models\SubmenuPanel;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SiteuserController extends Controller
{
    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'submenu' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $typeusers      = TypeUser::all();
        $users          = User::leftjoin('type_users' , 'type_users.id' , '=' , 'users.type_id')
            ->select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'type_users.title_fa' , 'users.status' , 'users.level' , 'users.birthday' , 'users.national_id' , 'users.type_id')
            ->where('users.level','=','site')->get();
        $thispage       = [
            'title'   => 'مدیریت  کاربران سایت ',
            'list'    => 'لیست  کاربران سایت ',
            'add'     => 'افزودن  کاربران سایت ',
            'create'  => 'ایجاد  کاربران سایت ',
            'enter'   => 'ورود  کاربران سایت ',
            'edit'    => 'ویرایش  کاربران سایت ',
            'delete'  => 'حذف  کاربران سایت ',
        ];

        if ($request->ajax()) {
            $data = User::leftjoin('type_users' , 'type_users.id' , '=' , 'users.type_id')
                ->select('users.id' , 'users.name' , 'users.email' , 'users.phone' , 'type_users.title_fa' , 'users.status')
                ->where('users.level','=','site')->get();

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
        return view('panel.siteuser')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'users' , 'typeusers']));
    }

    public function store(Request $request)
    {
        try {

            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $this->validator($request->all())->validate();

            event(new Registered($user = $this->create($request->all())));

            if ($result1 = $this->registered($request, $user)) {
                return $result1;
            }

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
        $user->birthday     = $request->input('birthday');
        $user->gender       = $request->input('gender');
        if ($request->input('password')) {
            $user->password = $request->input('password');
        }
        $user->status       = $request->input('status');
        $result = $user->update();
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
