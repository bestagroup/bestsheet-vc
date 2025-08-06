<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MenuPanel;
use App\Models\Owner;
use App\Models\Permission;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OwnerController extends Controller
{
    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $owners         = Owner::all();
        $thispage       = [
            'title'   => 'مدیریت اطلاعات کارفرما',
            'list'    => 'لیست اطلاعات کارفرما',
            'add'     => 'افزودن اطلاعات کارفرما',
            'create'  => 'ایجاد اطلاعات کارفرما',
            'enter'   => 'ورود اطلاعات کارفرما',
            'edit'    => 'ویرایش اطلاعات کارفرما',
            'delete'  => 'حذف اطلاعات کارفرما',
        ];

        if ($request->ajax()) {
            $data = Owner::all();

            return Datatables::of($data)
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('tel', function ($data) {
                    return ($data->tel);
                })
                ->addColumn('mobile', function ($data) {
                    return ($data->mobile);
                })
                ->addColumn('ceo', function ($data) {
                    return ($data->ceo);
                })
                ->addColumn('email', function ($data) {
                    return ($data->email);
                })
                ->addColumn('meli_code', function ($data) {
                    return ($data->meli_code);
                })
                ->addColumn('eghtesadi_code', function ($data) {
                    return ($data->eghtesadi_code);
                })
                ->addColumn('date_sabt', function ($data) {
                    return ($data->date_sabt);
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'" id="#deletesubmit_'.$data->id.'" data-id="#deletesubmit_'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.owner')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'owners']));
    }

    public function store(Request $request)
    {
        try {

            $owner = new Owner();
            $owner->title          = $request->input('title');
            $owner->tel            = $request->input('tel');
            $owner->mobile         = $request->input('mobile');
            $owner->ceo            = $request->input('ceo');
            $owner->email          = $request->input('email');
            $owner->meli_code      = $request->input('meli_code');
            $owner->eghtesadi_code = $request->input('eghtesadi_code');
            $owner->date_sabt      = $request->input('date_sabt');
            $owner->summery        = $request->input('summery');
            $owner->user_id        = Auth::user()->id;
            if($request->input('social')) {
                $owner->social = json_encode(explode(",", $request->input('social')));
            }
            $result1 = $owner->save();

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

//        return redirect(route('menudashboards.index'));

    }

    public function update(Request $request)
    {

        $menu_panel = MenuPanel::findOrfail($request->input('id'));
        $menu_panel->title        = $request->input('title');
        $menu_panel->label        = $request->input('label');
        //$menu_panel->icon         = $request->input('icon');
        $menu_panel->submenu      = $request->input('submenu');
        $menu_panel->class        = $request->input('class');
        $menu_panel->controller   = $request->input('controller');
        $menu_panel->user_id      = 1;
        $menu_panel->status       = $request->input('status');
//        if ($request->input('userlevel')){
//            $menu->userlevel        = json_encode(explode("،", $request->input('userlevel')));
//        }
//        $menu->priority         = $request->input('priority');

        $result = $menu_panel->update();
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
            $owner = Owner::findOrfail($request->input('id'));
            $result1 = $owner->delete();


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
