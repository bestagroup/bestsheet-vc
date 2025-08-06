<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuPanel;
use App\Models\Permission;
use App\Models\Submenu;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SubmenusiteController extends Controller
{
    public function index(Request $request)
    {

        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $menus          = Menu::all();
        $submenus       = Submenu::join('menus' , 'menus.id' , '=' , 'submenus.menu_id')
            ->select('menus.title as menu','submenus.menu_id','submenus.id','submenus.title','submenus.tab_title','submenus.page_title','submenus.slug','submenus.status','submenus.class','submenus.controller')
            ->get();
        $thispage       = [
            'title'   => 'مدیریت زیر صفحه سایت',
            'list'    => 'لیست زیر صفحه سایت',
            'add'     => 'افزودن زیر صفحه سایت',
            'create'  => 'ایجاد زیر صفحه سایت',
            'enter'   => 'ورود زیر صفحه سایت',
            'edit'    => 'ویرایش زیر صفحه سایت',
            'delete'  => 'حذف زیر صفحه سایت',
        ];

        if ($request->ajax()) {
            $data = Submenu::join('menus' , 'menus.id' , '=' , 'submenus.menu_id')
                ->select('menus.title as menu','submenus.id','submenus.title','submenus.slug','submenus.status','submenus.class','submenus.controller')
                ->get();
            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->id);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
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
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.submenusite')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'submenus' , 'menus']));
    }

    public function store(Request $request)
    {
        try {

            $submenus = new Submenu();
            $submenus->title        = $request->input('title');
            $submenus->tab_title    = $request->input('tab_title');
            $submenus->page_title   = $request->input('page_title');
            $submenus->menu_id      = $request->input('menu_id');
            $submenus->class        = $request->input('class');
            $submenus->controller   = $request->input('controller');
            $submenus->user_id      = Auth::user()->id;
            $submenus->status       = $request->input('status');

            $result1 = $submenus->save();

            if ($result1 == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
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

        $submenu = Submenu::findOrfail($request->input('id'));
        $submenu->title        = $request->input('title');
        $submenu->tab_title    = $request->input('tab_title');
        $submenu->page_title   = $request->input('page_title');
        $submenu->menu_id      = $request->input('menu_id');
        $submenu->class        = $request->input('class');
        $submenu->controller   = $request->input('controller');
        $submenu->user_id      = 1;
        $submenu->status       = $request->input('status');
//        if ($request->input('userlevel')){
//            $menu->userlevel        = json_encode(explode("،", $request->input('userlevel')));
//        }
//        $menu->priority         = $request->input('priority');

        $result = $submenu->update();
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
            $submenu = Submenu::findOrfail($request->input('id'));
            $result1 = $submenu->delete();


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
