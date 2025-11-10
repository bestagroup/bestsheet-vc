<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{

    public function index(Request $request)
    {

        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $companies      = Company::all();
        $finances       = Finance::all();
        $users          = User::whereLevel('applicant')->select('id','name')->get();

        $thispage       = [
            'title'   => 'مدیریت شرکت ها',
            'list'    => 'لیست شرکت ها',
            'add'     => 'افزودن شرکت ',
            'create'  => 'ایجاد شرکت ',
            'enter'   => 'ورود شرکت ',
            'edit'    => 'ویرایش شرکت ',
            'delete'  => 'حذف شرکت ',
        ];

        if ($request->ajax()) {

            $data = project::all();

            return Datatables::of($data)

                ->addColumn('logo', function ($data) {
                    if ($data->logo) {
                        $fileUrl = asset('storage/' . $data->logo);
                        return '<img src="' . $fileUrl . '" alt="' . $data->title . '" style="width: 80px; height: auto;">';
                    }else{
                        return '';
                    }
                })
                ->addColumn('commercial_name', function ($data) {
                    return ($data->title);
                })
                ->addColumn('company_name', function ($data) {
                    return ($data->company_name);
                })
                ->addColumn('registration_number', function ($data) {
                    return ($data->registration_number);
                })
                ->addColumn('ceo_name', function ($data) {
                    return ($data->ceo_name);
                })
                ->addColumn('registration_date', function ($data) {
                    return ($data->registration_date);
                })
                ->addColumn('national_id', function ($data) {
                    return ($data->national_id);
                })
                ->addColumn('economic_code', function ($data) {
                    return ($data->economic_code);
                })
                ->addColumn('legal_type', function ($data) {
                    return ($data->legal_type);
                })
                ->addColumn('website', function ($data) {
                    return ($data->website);
                })

                ->editColumn('action', function ($data) {
                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['company', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary mx-1"><i class="mdi mdi-pencil-outline"></i></button>';
                    }
                    if (auth()->user()->can('can-access', ['company', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action' , 'logo'])
                ->make(true);
        }
        return view('panel.company')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'companies' , 'finances' , 'users']));
    }

    public function store(Request $request)
    {
        try {
            $company = new Project();
            $company->title                 = $request->input('title');
            $company->company_name          = $request->input('company_name');
            $company->registration_number   = $request->input('registration_number');
            $company->national_id           = $request->input('national_id');
            $company->registration_date     = $request->input('registration_date');
            $company->economic_code         = $request->input('economic_code');
            $company->legal_type            = $request->input('legal_type');
            $company->tel                   = $request->input('tel');
            $company->email                 = $request->input('email');
            $company->website               = $request->input('website');
            $company->postal_code           = $request->input('postal_code');
            $company->state                 = $request->input('state');
            $company->city                  = $request->input('city');
            $company->CEO                   = $request->input('CEO');
            $company->ceo_national_code     = $request->input('ceo_national_code');
            $company->address               = $request->input('address');

            $result = $company->save();

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

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->level == 'admin'){
            $companies = Project::findOrfail($id);
        }elseif(Auth::user()->level == 'applicant'){
            $companyId = Auth::user()->project->id;
            $companies = Project::findOrfail($companyId);
        }
        try{
            $companies->title                 = $request->input('title');
            $companies->company_name          = $request->input('company_name');
            $companies->registration_number   = $request->input('registration_number');
            $companies->national_id           = $request->input('national_id');
            $companies->registration_date     = $request->input('registration_date');
            $companies->economic_code         = $request->input('economic_code');
            $companies->legal_type            = $request->input('legal_type');
            $companies->tel                   = $request->input('tel');
            $companies->email                 = $request->input('email');
            $companies->website               = $request->input('website');
            $companies->postal_code           = $request->input('postal_code');
            $companies->state                 = $request->input('state');
            $companies->city                  = $request->input('city');
            $companies->CEO                   = $request->input('CEO');
            $companies->ceo_national_code     = $request->input('ceo_national_code');
            $companies->address               = $request->input('address');
        if ($request->input('user_id')) {
            $companies->user_id = $request->input('user_id');
        }
        $result = $companies->update();

            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت ثبت شد';
                $data    =[
                    'registration_number' => $companies->registration_number,
                    'national_id'         => $companies->national_id,
                    'tel'                 => $companies->tel,
                    'email'               => $companies->email,
                    'address'             => $companies->address,
                ];
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

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message ,'data' => $data]);
    }

    public function destroy(Request $request)
    {
        try {
            $company = Project::findOrfail($request->input('id'));
            $result = $company->delete();

            if ($result == true) {
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
