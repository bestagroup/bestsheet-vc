<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\State;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FinancialController extends Controller
{
    public function index(Request $request){
        $thispage       = [
            'title'   => 'مدیریت پرداخت ها',
            'list'    => 'لیست پرداخت ها',
            'add'     => 'افزودن پرداخت ها',
            'create'  => 'ایجاد پرداخت ها',
            'enter'   => 'ورود پرداخت ها',
            'edit'    => 'ویرایش پرداخت ها',
            'delete'  => 'حذف پرداخت ها',
        ];
        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $finances       = Finance::all();
        $projects       = Project::where('invest_step' , '<>', 0)->get();

        if ($request->ajax()) {
            $data = DB::table('finances as f')
                ->leftJoin('projects as p', 'p.id', '=', 'f.project_id')
                ->select('f.serial','f.id as id', 'f.amount','f.installment', 'f.date', 'p.id as project_id', 'p.company_name','p.title' , 'p.amount_request_accept' , 'p.start_date')
                ->orderBy('f.serial', 'asc')
                ->where('f.amount' ,'>' ,0)
                ->get();

            return Datatables::of($data)

                ->addColumn('company_name', function ($data) {
                    return ($data->company_name);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('contract_amount', function ($data) {
                    return (number_format($data->amount_request_accept));
                })
                ->addColumn('contract_date', function ($data) {
                    return ($data->start_date);
                })
                ->addColumn('serial', function ($data) {
                    return ($data->serial);
                })
                ->addColumn('installment', function ($data) {
                    return ($data->installment);
                })
                ->addColumn('amount', function ($data) {
                    return (number_format($data->amount));
                })
                ->addColumn('date', function ($data) {
                    return ($data->date);
                })
                ->addColumn('action', function ($data) {
                    $actionBtn ='<button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="'.$data->id.'" data-url="'.route('finance.edit', $data->id).'"><i class="mdi mdi-pencil-outline"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.finance')->with(compact(['menupanels' , 'submenupanels' , 'finances','thispage' , 'projects']));
    }

    public function store(Request $request)
    {
        {
            try {
                $finance = new Finance();
                $finance->project_id    = $request->input('project_id');
                $finance->serial        = $request->input('serial');
                $finance->docserial     = $request->input('docserial');
                $finance->finance_type  = 'vc-investment';
                $finance->date          = $request->input('date');
                $finance->description   = $request->input('description');
                $finance->amount        = $request->filled('amount') ? str_replace(',', '', $request->input('amount'))  : null;

                $result = $finance->save();

                if ($result == true) {
                    $success = true;
                    $flag = 'success';
                    $subject = 'عملیات موفق';
                    $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
                } elseif ($result != true) {
                    $success = false;
                    $flag = 'error';
                    $subject = 'عملیات نا موفق';
                    $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
                }

            } catch (Exception $e) {

                $success = false;
                $flag = 'error';
                $subject = 'خطا در ارتباط با سرور';
                //$message = strchr($e);
                $message = 'اطلاعات زیرمنو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
            }

            return response()->json(['success' => $success, 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

//        return redirect(route('menudashboards.index'));

        }
    }

    public function edit($id)
    {
        $finance       = Finance::findOrFail($id);
        $projects       = Project::where('invest_step' , '>=', 6)->get();

        return view('panel.partials.edit-form-finance', compact('finance', 'projects'));
    }

    public function update(Request $request , $id)
    {

        $finances = Finance::findOrfail($id);
        $finances->project_id    = $request->input('project_id');
        $finances->serial        = $request->input('serial');
        $finances->docserial     = $request->input('docserial');
        $finances->finance_type  = 'vc-investment';
        $finances->date          = $request->input('date');
        $finances->description   = $request->input('description');
        $finances->amount        = $request->input('amount');
        $finances->amount        = $request->filled('amount') ? str_replace(',', '', $request->input('amount'))  : null;

        $result = $finances->update();

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
