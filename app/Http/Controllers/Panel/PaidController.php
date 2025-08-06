<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class PaidController extends Controller
{
    public function index(Request $request)
    {

        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $finances       = Finance::all();
        $projects       = Project::all();
        $thispage       = [
            'title'   => 'مدیریت پرداخت ها',
            'list'    => 'لیست پرداخت ها',
            'add'     => 'افزودن پرداخت ها',
            'create'  => 'ایجاد پرداخت ها',
            'enter'   => 'ورود پرداخت ها',
            'edit'    => 'ویرایش پرداخت ها',
            'delete'  => 'حذف پرداخت ها',
        ];

        if ($request->ajax()) {
            $data = Finance::leftjoin('projects' , 'projects.id' , '=' , 'finances.project_id')
            ->select('finances.id','projects.title' , 'finances.amount', 'finances.date' , 'finances.serial' , 'finances.description')
            ->get();
            return Datatables::of($data)
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('amount', function ($data) {
                    return (number_format($data->amount));
                })
                ->addColumn('serial', function ($data) {
                    return ($data->serial);
                })
                ->addColumn('date', function ($data) {
                    return ($data->date);
                })
                ->addColumn('description', function ($data) {
                    return ($data->description);
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.paidmanage')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'finances' , 'projects']));
    }

    public function store(Request $request)
    {
        try {

            $finance = new Finance();
            $finance->project_id    = $request->input('project_id');
            $amount                 = str_replace(',', '', $request->input('amount'));
            $finance->amount        = $amount;
            $finance->date          = $request->input('date');
            $finance->description   = $request->input('description');
            $finance->serial        = $request->input('serial');

            $result = $finance->save();


            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
            }
            elseif($result != true) {
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

        //return Redirect::back();

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

//        return redirect(route('menudashboards.index'));

    }

    public function update(Request $request)
    {

        $finance = Finance::findOrfail($request->input('id'));
        $finance->project_id    = $request->input('project_id');
        $amount                 = str_replace(',', '', $request->input('amount'));
        $finance->amount        = $amount;
        $finance->date          = $request->input('date');
        $finance->description   = $request->input('description');
        $finance->serial        = $request->input('serial');

        $result = $finance->save();

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
            $finance = Finance::findOrfail($request->input('id'));
            $result = $finance->delete();

            if ($result == true ) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت پاک شد';
            }elseif($result != true) {
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
