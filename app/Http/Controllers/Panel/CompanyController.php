<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    public function index()
    {
        //
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
