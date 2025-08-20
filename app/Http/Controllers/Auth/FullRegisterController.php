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
                'level'           => 'investor',
                'change_password' => 1,
                'password'        => Hash::make($request->password),
            ]);

            $project = Project::create([
                'title'   => $request->title,
                'CEO'     => $request->CEO,
                'user_id' => $user->id,
            ]);

            $companies = Company::create([
                'commercial_name' => $request->title,
                'ceo_name'        => $request->CEO,
                'user_id'         => $user->id,
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

    public function logout(){
        Auth::logout();
        return redirect()->to('/login');
    }
}
