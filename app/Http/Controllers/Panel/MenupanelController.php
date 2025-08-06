<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MenuPanel;
use App\Models\Permission;
use App\Models\SubmenuPanel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class MenupanelController extends Controller
{

    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'submenu' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $thispage       = [
            'title'   => 'مدیریت منو داشبورد',
            'list'    => 'لیست منو داشبورد',
            'add'     => 'افزودن منو داشبورد',
            'create'  => 'ایجاد منو داشبورد',
            'enter'   => 'ورود منو داشبورد',
            'edit'    => 'ویرایش منو داشبورد',
            'delete'  => 'حذف منو داشبورد',
        ];

        if ($request->ajax()) {
            $data = Menupanel::select('id' ,'priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->orderBy('priority')->get();

            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->priority);
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

                    if (Gate::allows('can-access', ['menupanel', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary">
                        <i class="mdi mdi-pencil-outline"></i>
                      </button> ';
                    }

                    if (Gate::allows('can-access', ['menupanel', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $data->id . '" id="#deletesubmit_' . $data->id . '" data-id="#deletesubmit_' . $data->id . '">
                        <i class="mdi mdi-delete-outline"></i>
                       </button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.menupanel')->with(compact(['thispage' , 'menupanels' , 'submenupanels']));
    }

    public function store(Request $request)
    {
        try {
            $priority = MenuPanel::max('priority');
            $menu_panel = new Menupanel();
            $menu_panel->title        = $request->input('title');
            $menu_panel->label        = $request->input('label');
            //$menu_panel->icon         = $request->input('icon');
            $menu_panel->submenu      = $request->input('submenu');
            $menu_panel->class        = $request->input('class');
            $menu_panel->controller   = $request->input('controller');
            $menu_panel->user_id      = 1;
            $menu_panel->status       = $request->input('status');
            $menu_panel->priority     = $priority + 1;

            $result1 = $menu_panel->save();

            $permission = new Permission();
            $permission->title          = $request->input('title');
            $permission->label          = $request->input('label');
            $permission->menu_panel_id  = $menu_panel->id;
            $permission->user_id        = 1;

            $result2 = $permission->save();

            if ($result1 == true  && $result2 == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات منو با موفقیت ثبت شد';
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
            $menu = MenuPanel::findOrfail($request->input('id'));
            $result1 = $menu->delete();

            $permission = Permission::whereMenu_panel_id($request->input('id'))->first();
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
