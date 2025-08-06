<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MenuPanel;
use App\Models\Permission;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class SubmenupanelController extends Controller
{
    public function index(Request $request)
    {

        $submenupanels = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $thispage       = [
            'title'   => 'مدیریت زیرمنو داشبورد',
            'list'    => 'لیست زیرمنو داشبورد',
            'add'     => 'افزودن زیرمنو داشبورد',
            'create'  => 'ایجاد زیرمنو داشبورد',
            'enter'   => 'ورود زیرمنو داشبورد',
            'edit'    => 'ویرایش زیرمنو داشبورد',
            'delete'  => 'حذف زیرمنو داشبورد',
        ];

        if ($request->ajax()) {
            $data = SubmenuPanel::join('menu_panels' , 'menu_panels.id' , '=' , 'submenu_panels.menu_id')
                ->select('menu_panels.label as menu','submenu_panels.id','submenu_panels.title','submenu_panels.label','submenu_panels.slug','submenu_panels.status','submenu_panels.class','submenu_panels.controller')
                ->get();
            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->id);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('label', function ($data) {
                    return ($data->label);
                })
                ->addColumn('slug', function ($data) {
                    return ($data->slug);
                })
                ->addColumn('menu', function ($data) {
                    return ($data->menu);
                })
                ->addColumn('class', function ($data) {
                    return ($data->class);
                })
                ->addColumn('controller', function ($data) {
                    return ($data->controller);
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == "0") {
                        return "عدم نمایش";
                    } elseif ($data->status == "4") {
                        return "در حال نمایش";
                    }
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '';

                    if (Gate::allows('can-access', ['submenupanel', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary">
                        <i class="mdi mdi-pencil-outline"></i>
                      </button> ';
                    }
                    if (Gate::allows('can-access', ['submenupanel', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $data->id . '" id="#deletesubmit_' . $data->id . '" data-id="#deletesubmit_' . $data->id . '">
                        <i class="mdi mdi-delete-outline"></i>
                       </button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.submenupanel')->with(compact(['thispage' , 'submenupanels' , 'menupanels']));
    }

    public function store(Request $request)
    {
        try {
            $priority      = SubmenuPanel::select('id')->orderBy('id' , 'DESC')->first();
            $submenu_panel = new SubmenuPanel();
            $submenu_panel->title        = $request->input('title');
            $submenu_panel->label        = $request->input('label');
            $submenu_panel->menu_id      = $request->input('menupanel_id');
            $submenu_panel->class        = $request->input('class');
            $submenu_panel->controller   = $request->input('controller');
            $submenu_panel->user_id      = Auth::user()->id;
            $submenu_panel->priority     = $priority->id + 1;
            $submenu_panel->status       = $request->input('status');
            $result1 = $submenu_panel->save();

            $permission = new Permission();
            $permission->title          = $request->input('title');
            $permission->label          = $request->input('label');
            $permission->submenu_panel_id  = $submenu_panel->id;
            $permission->menu_panel_id  = $request->input('menupanel_id');
            $permission->user_id        = Auth::user()->id;

            $result2 = $permission->save();

            if ($result1 == true  && $result2 == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
            }
            elseif($result1 == true  && $result2 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات دسترسی ثبت نشد، لطفا مجددا تلاش نمایید';
            }
            elseif($result1 != true  && $result2 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات زیرمنو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }


        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

//        return redirect(route('menudashboards.index'));

    }

    public function update(Request $request)
    {

        $submenu_panel = SubmenuPanel::findOrfail($request->input('id'));
        $submenu_panel->title        = $request->input('title');
        $submenu_panel->label        = $request->input('label');
        $submenu_panel->menu_id      = $request->input('menupanel_id');
        $submenu_panel->class        = $request->input('class');
        $submenu_panel->controller   = $request->input('controller');
        $submenu_panel->user_id      = Auth::user()->id;
        $submenu_panel->status       = $request->input('status');
//        if ($request->input('userlevel')){
//            $menu->userlevel        = json_encode(explode("،", $request->input('userlevel')));
//        }
//        $menu->priority         = $request->input('priority');

        $result = $submenu_panel->update();
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
            $submenu = SubmenuPanel::findOrfail($request->input('id'));
            $result1 = $submenu->delete();

            $permission = Permission::whereSubmenu_panel_id($request->input('id'))->first();
            $result2 = $permission->delete();


            if ($result1 == true  && $result2 == true) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت پاک شد';
            }elseif($result1 == true  && $result2 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات دسترسی ثبت نشد، لطفا مجددا تلاش نمایید';
            }
            elseif($result1 != true  && $result2 != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
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
