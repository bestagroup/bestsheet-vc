<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MenuPanel;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MenusiteController extends Controller
{
    public function index(Request $request)
    {

        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $menus          = Menu::all();
        $thispage       = [
            'title'   => 'مدیریت منو سایت',
            'list'    => 'لیست منو سایت',
            'add'     => 'افزودن منو سایت',
            'create'  => 'ایجاد منو سایت',
            'enter'   => 'ورود منو سایت',
            'edit'    => 'ویرایش منو سایت',
            'delete'  => 'حذف منو سایت',
        ];

        if ($request->ajax()) {
            $data = Menu::select('id' , 'priority', 'title', 'slug', 'status' , 'class' , 'controller')->get();

            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->priority);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
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
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'" id="#deletesubmit_'.$data->id.'" data-id="#deletesubmit_'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.menusite')->with(compact(['thispage' , 'menupanels' , 'submenupanels' , 'menus']));
    }

    public function store(Request $request)
    {
        try {

            $menus = new Menu();
            $menus->title        = $request->input('title');
            $menus->tab_title    = $request->input('tab_title');
            $menus->page_title   = $request->input('page_title');
            $menus->submenu      = $request->input('submenu');
            $menus->class        = $request->input('class');
            $menus->controller   = $request->input('controller');
            $menus->status       = $request->input('status');
            $menus->home_show    = $request->input('home_show');
            $menus->keyword      = $request->input('keyword');
            $menus->description  = $request->input('description');
            $menus->user_id      = Auth::user()->id;


            $result1 = $menus->save();

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

        $menu = Menu::findOrfail($request->input('id'));
        $menu->title        = $request->input('title');
        $menu->tab_title    = $request->input('tab_title');
        $menu->page_title   = $request->input('page_title');
        $menu->submenu      = $request->input('submenu');
        $menu->class        = $request->input('class');
        $menu->controller   = $request->input('controller');
        $menu->status       = $request->input('status');
        $menu->home_show    = $request->input('home_show');
        $menu->keyword      = $request->input('keyword');
        $menu->description  = $request->input('description');
        $menu->user_id      = Auth::user()->id;
//        if ($request->input('userlevel')){
//            $menu->userlevel        = json_encode(explode("،", $request->input('userlevel')));
//        }
//        $menu->priority         = $request->input('priority');

        $result = $menu->update();
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
            $menu = Menu::findOrfail($request->input('id'));
            $result1 = $menu->delete();

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
