<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MenuPanel;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SubmenuPanel;
use App\Models\TypeUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UseraccessController extends Controller
{
    public function index(Request $request)
    {
        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'submenu' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $roles          = Role::all();
        $thispage       = [
            'title'   => 'مدیریت  نقش ',
            'list'    => 'لیست  نقش ',
            'add'     => 'افزودن  نقش ',
            'create'  => 'ایجاد  نقش ',
            'enter'   => 'ورود  نقش ',
            'edit'    => 'ویرایش  نقش ',
            'delete'  => 'حذف  نقش ',
        ];

        if ($request->ajax()) {
            $data = Role::all();

            return Datatables::of($data)
                ->addColumn('title_fa', function ($data) {
                    return ($data->title_fa);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
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
        return view('panel.useraccess')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'roles']));
    }

    public function store(Request $request)
    {
        try {

            $role = new Role();
            $role->title_fa     = $request->input('title_fa');
            $role->title        = $request->input('title');
            $role->status       = $request->input('status');

            $result1 = $role->save();

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

        $role = Role::findOrfail($request->input('id'));
        $role->title_fa     = $request->input('title_fa');
        $role->title        = $request->input('title');
        $role->status       = $request->input('status');
        $result = $role->update();
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
            $role = Role::findOrfail($request->input('id'));
            $result1 = $role->delete();

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
