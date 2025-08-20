<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
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

        $thispage       = [
            'title'   => 'مدیریت پروژه ها',
            'list'    => 'لیست پروژه ها',
            'add'     => 'افزودن پروژه ها',
            'create'  => 'ایجاد پروژه ها',
            'enter'   => 'ورود پروژه ها',
            'edit'    => 'ویرایش پروژه ها',
            'delete'  => 'حذف پروژه ها',
        ];

        if ($request->ajax()) {
            $data = DB::table('projects as p')
                ->leftJoin('finances as f', 'p.id', '=', 'f.project_id')
                ->select(
                    'p.id', 'p.title','p.company_name','p.CEO','p.portfo_status','p.flow_level'
                    ,'p.progress_percentage','p.activity_status','p.start_date','p.amount_request_accept'
                    ,'p.company_name', 'p.amount_commitment_first_stage',
                    DB::raw('MAX(CASE WHEN f.serial = 1 THEN f.amount END) as first_stage_payment'),
                    'p.amount_commitment_second_stage',
                    DB::raw('MAX(CASE WHEN f.serial = 2 THEN f.amount END) as second_stage_payment'),
                    'p.amount_commitment_third_stage',
                    DB::raw('MAX(CASE WHEN f.serial = 3 THEN f.amount END) as third_stage_payment'),
                    'p.amount_commitment_fourth_stage',
                    DB::raw('MAX(CASE WHEN f.serial = 4 THEN f.amount END) as fourth_stage_payment'),
                    'p.amount_commitment_fifth_stage',
                    DB::raw('MAX(CASE WHEN f.serial = 5 THEN f.amount END) as fifth_stage_payment')
                )
                ->groupBy(
                    'p.id', 'p.title', 'p.company_name', 'p.CEO', 'p.portfo_status', 'p.flow_level',
                    'p.progress_percentage', 'p.activity_status', 'p.start_date', 'p.amount_request_accept',
                    'p.amount_commitment_first_stage', 'p.amount_commitment_second_stage',
                    'p.amount_commitment_third_stage', 'p.amount_commitment_fourth_stage',
                    'p.amount_commitment_fifth_stage'
                )
                ->orderBy('p.id', 'desc')
                ->get();
            return Datatables::of($data)
                ->addColumn('id', function ($data) {
                    return ($data->id);
                })
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('company_name', function ($data) {
                    return ($data->company_name);
                })
                ->addColumn('CEO', function ($data) {
                    return ($data->CEO);
                })
                ->addColumn('portfo_status', function ($data) {
                    return ($data->portfo_status);
                })
                ->addColumn('flow_level', function ($data) {
                    return ($data->flow_level);
                })
                ->addColumn('progress_percentage', function ($data) {
                    return ($data->progress_percentage . '%');
                })
                ->addColumn('activity_status', function ($data) {
                    return ($data->activity_status);
                })
                ->addColumn('start_date', function ($data) {
                    return ($data->start_date);
                })
                ->addColumn('amount_request_accept', function ($data) {
                    return (number_format($data->amount_request_accept));
                })
                ->addColumn('amount_deposited', function ($data) {
                    return (number_format($data->first_stage_payment + $data->second_stage_payment + $data->third_stage_payment + $data->fourth_stage_payment + $data->fifth_stage_payment));
                })
                ->addColumn('amount_commitment_first_stage', function ($data) {
                    return (number_format($data->amount_commitment_first_stage));
                })
                ->addColumn('first_stage_payment', function ($data) {
                    return (number_format($data->first_stage_payment));
                })
                ->addColumn('amount_commitment_second_stage', function ($data) {
                    return (number_format($data->amount_commitment_second_stage));
                })
                ->addColumn('second_stage_payment', function ($data) {
                    return (number_format($data->second_stage_payment));
                })
                ->addColumn('amount_commitment_third_stage', function ($data) {
                    return (number_format($data->amount_commitment_third_stage));
                })
                ->addColumn('third_stage_payment', function ($data) {
                    return (number_format($data->third_stage_payment));
                })
                ->addColumn('amount_commitment_fourth_stage', function ($data) {
                    return (number_format($data->amount_commitment_fourth_stage));
                })
                ->addColumn('fourth_stage_payment', function ($data) {
                    return (number_format($data->fourth_stage_payment));
                })
                ->addColumn('amount_commitment_fifth_stage', function ($data) {
                    return (number_format($data->amount_commitment_fifth_stage));
                })
                ->addColumn('fifth_stage_payment', function ($data) {
                    return (number_format($data->fifth_stage_payment));
                })
                ->addColumn('commitment_balance', function ($data) {
                    return (number_format($data->first_stage_payment + $data->second_stage_payment + $data->third_stage_payment + $data->fourth_stage_payment + $data->fifth_stage_payment - $data->amount_request_accept));
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['project', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary mx-1"><i class="mdi mdi-pencil-outline"></i></button>';
                    }
                    if (auth()->user()->can('can-access', ['project', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    $actionBtn .= '<button class="btn btn-sm btn-icon btn-eye mx-1" data-bs-toggle="modal" data-bs-target="#showModal'.$data->id.'"><i class="mdi mdi-eye"></i></button>';

                    $actionBtn .= '<button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="'.$data->id.'"><i class="mdi mdi-file-document-multiple-outline"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.project')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'projects' , 'finances']));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->level == 'admin'){
            $companies = Company::findOrfail($id);
        }elseif(Auth::user()->level == 'applicant'){
            $companyId = Auth::user()->company->id;
            $companies = Company::findOrfail($companyId);
        }

        $companies->company_name        = $request->input('company_name');
        $companies->commercial_name     = $request->input('commercial_name');
        $companies->registration_number = $request->input('registration_number');
        $companies->national_id         = $request->input('national_id');
        $companies->economic_code       = $request->input('economic_code');
        $companies->legal_type          = $request->input('legal_type');
        $companies->phone               = $request->input('phone');
        $companies->email               = $request->input('email');
        $companies->website             = $request->input('website');
        $companies->province            = $request->input('province');
        $companies->city                = $request->input('city');
        $companies->postal_code         = $request->input('postal_code');
        $companies->ceo_name            = $request->input('ceo_name');
        $companies->ceo_national_code   = $request->input('ceo_national_code');
        $companies->address             = $request->input('address');

        $result = $companies->update();

        try{
            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت ثبت شد';
                $data    =[
                    'registration_number' => $companies->registration_number,
                    'national_id'         => $companies->national_id,
                    'phone'               => $companies->phone,
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


    public function destroy(string $id)
    {
        //
    }
}
