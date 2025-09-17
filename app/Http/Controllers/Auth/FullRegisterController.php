<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use App\Models\User_logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FullRegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1) همه‌ی قوانین داخل validate تا تمام خطاها وارد $errors شوند
        $request->validate([
            'title'             => 'required|string|max:255',
            'CEO'               => 'required|string|max:255',
            'phone'             => 'required|string|max:20|unique:users,phone',   // یکتا
            'email'             => 'required|email|max:255|unique:users,email',  // یکتا
            'password'          => 'required|string|min:8|confirmed',
            'terms_accepted'    => 'accepted',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'            => $request->CEO,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'level'           => 'applicant',
                'status'          => 4,
                'role_id'         => 5,
                'change_password' => 1,
                'password'        => Hash::make($request->password),
            ]);

            $companies = Company::create([
                'company_name'    => $request->title,
                'ceo_name'        => $request->CEO,
                'user_id'         => $user->id,
            ]);

            $project = Project::create([
                'company_id'   => $companies->id,
                'title'        => $request->title,
                'CEO'          => $request->CEO,
                'user_id'      => $user->id,
            ]);

            Auth::login($user);

            User_logs::create([
                'user_id'    => auth()->id(),
                'action'     => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status'     => true,
                'description'=> 'ثبت نام و ورود موفق',
            ]);

            DB::commit();

            return redirect()->route('profile')->with('success', 'ثبت‌نام با موفقیت انجام شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            // این خطا هم داخل $errors->any() می‌افتد
            return back()->withErrors(['system' => 'خطا در ذخیره اطلاعات. لطفاً دوباره تلاش کنید.'])->withInput();
        }
    }
    public function update(Request $request, $id)
    {
        if (Auth::user()->level == 'admin'){
            $users = User::findOrfail($id);
        }elseif(Auth::user()->level == 'applicant'){
            $users = Auth::user();
        }

        $users->name        = $request->input('name');
        $users->national_id = $request->input('national_id');
        $users->father_name = $request->input('father_name');
        $users->national_id = $request->input('national_id');
        $users->email       = $request->input('email');
        $users->phone       = $request->input('phone');
        $users->gender      = $request->input('gender');
        $users->postalcode  = $request->input('postalcode');
        $users->address     = $request->input('address');

        $result = $users->update();

        try{
            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت ثبت شد';
                $data    =[
                    'user_national_id'         => $users->national_id,
                    'user_phone'               => $users->phone,
                    'user_email'               => $users->email,
                    'user_address'             => $users->address,
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
    public function logout(){
        Auth::logout();
        return redirect()->to('/login');
    }
}
