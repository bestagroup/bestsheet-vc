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
use Illuminate\Support\Facades\Redirect;

class FullRegisterController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'title'          => 'required|string|max:255',
            'CEO'            => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'terms_accepted' => 'accepted'
        ]);

        if (User::where('phone', $request->phone)->exists()) {
            return redirect()->back()->with('error', 'با این شماره قبلا ثبت نام شده است ❌');
        }
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('error', 'با این ایمیل قبلا ثبت نام شده است ❌');
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name'              => $request->CEO,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'level'             => 'investor',
                'change_password'   => 1,
                'password'          => Hash::make($request->password),
            ]);

            $project = Project::create([
                'title'     => $request->title,
                'CEO'       => $request->CEO,
                'user_id'   => $user->id,
            ]);

            $companies = Company::create([
                'commercial_name'   => $request->title,
                'ceo_name'          => $request->CEO,
                'user_id'           => $user->id,
            ]);

            Auth::login($user);

            User_logs::create([
                'user_id' => auth()->id(),
                'action' => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => true,
                'description' => 'ثبت نام و ورود موفق',
            ]);

            DB::commit();

            return redirect()->route('profile')->with('success', 'ثبت ‌نام با موفقیت انجام شد');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطا در ذخیره اطلاعات. لطفاً دوباره تلاش کنید.']);
        }
    }

    public function logout(){
        Auth::logout();
        return Redirect::to('/login');
    }
}
