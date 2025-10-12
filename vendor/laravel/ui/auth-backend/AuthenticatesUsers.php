<?php

namespace Illuminate\Foundation\Auth;

use App\Models\ActiveCode;
use App\Notifications\ActiveCode as ActiveCodeNotification;
use App\Models\User_logs;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        $user = User::where('email', $request->input('email'))->first();

        // ثبت لاگ ورود ناموفق
        User_logs::create([
            'user_id' => optional($user)->id,
            'action' => 'failed_login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => false,
            'description' => 'تلاش ناموفق برای ورود (احتمالاً رمز عبور اشتباه)',
        ]);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        User_logs::create([
            'user_id' => auth()->id(),
            'action' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => true,
            'description' => 'ورود موفق',
        ]);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->level === 'admin') {
            return redirect()->route('dashboard');
        }
        if ($user->level === 'investor') {
            return redirect()->route('profile');
        }
        return redirect()->route('profile');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username()
    {
        return 'email';
    }

    public function logout(Request $request)
    {
        User_logs::create([
            'user_id' => auth()->id(),
            'action' => 'logout',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => true,
            'description' => 'خروج از حساب',
        ]);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        //
    }

    protected function guard()
    {
        return Auth::guard();
    }
    public function redirectToProvider($provider)
    {
//        return Socialite::driver($provider)->redirect();
        return Socialite::driver($provider)
            ->scopes(['https://www.googleapis.com/auth/calendar'])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        $google_id            = $user->getId();
        $google_token         = $user->token;
        $google_refresh_token = $user->refreshToken;
        $google_expires_in    = $user->expiresIn;
        try {
            $user = User::find(Auth::id());
            $user->email_verify     = 1;
            $user->google_id        = $google_id;
            $user->google_token     = $google_token;
            $user->google_expires_in= $google_expires_in;
            if ($google_refresh_token) {
                $user->google_refresh_token = $google_refresh_token;
            }

            $user->save();

        }catch (Exception){

        }
        alert()->success($user->name.' به داشبورد مدیریتی ' , 'خوش آمدید' );
        return redirect()->intended('/');
    }

    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::whereEmail($user->email)->first();
        if ($authUser) {
            return $authUser;
        }
        return  User::create([
            'name'                  => $googleUser->getName(),
            'email'                 => $user->email,
            'password'              => Hash::make('123456789'),
            'level'                 => 'applicant',
            'status'                => 4,
            'role_id'               => 5,
            'change_password'       => 1,
            'google_id'             => $google_id,
            'google_token'          => $google_token,
            'google_refresh_token'  => $google_refresh_token,
            'google_expires_in'     => $google_expires_in,
        ]);
    }

    public function otplogin()
    {
        return view('auth.otplogin');
    }

    public function sendtoken()
    {
        return view('auth.token');
    }

    public function checktoken(Request $request)
    {
        $request->validate([
            'code' => ['required','numeric','min:6','exists:active_codes,code']
        ]);

        $token = $this->convertPersianToEnglishNumbers($request->input('code'));
        $times = ActiveCode::select('expired_at')->whereCode($token)->first();

        if (jdate($times->expired_at)->getTimestamp() - jdate()->now()->getTimestamp() <= 0) {
            alert()->error('عملیات ناموفق', 'کد وارد شده منقضی گردیده است، لطفا مجدد تلاش کنید ');
            return Redirect::back();
        }

        $user   = User::findOrFail(session('auth.user_id'));
        $status = ActiveCode::verifyCode($token , $user);

        if(auth()->loginUsingId($user->id) && $request->session()->get('auth.reg') == 1) {
            $user->activeCode()->delete();
            $user->phone_verify = 1;
            $user->update();
            if ($user->level == 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->level == 'applicant') {
                return redirect()->route('profile');
            }
        } elseif(auth()->loginUsingId($user->id))
        {
            $user->activeCode()->delete();
            $user->phone_verify = 1;
            $user->update();
            if ($user->level == 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->level == 'applicant') {
                return redirect()->route('profile');
            }
        }
        return redirect(route('login'));
    }

    public function gettoken(Request $request){

        $phone = $this->convertPersianToEnglishNumbers($request->input('phone'));

        $validData = Validator::make(
            ['phone' => $phone],
            ['phone' => ['required', 'exists:users,phone']]
        )->validate();

        $user = User::where('phone', $phone)->first();

        session(['auth' => ['user_id' => $user->id]]);

        $code = ActiveCode::generateCode($user);

        $user->notify(new ActiveCodeNotification($code , $user->phone));

        return redirect(route('sendtoken'))->with(['phone' => $phone]);
    }

    public function remember(Request $request){

        $validData = $request->validate([
            'phone'      => ['required', 'exists:users,phone'],
        ]);

        $phone      = $this->convertPersianToEnglishNumbers($validData['phone']);
        $user       = User::wherePhone($phone)->first();
        $user       = User::find($user->id);
        $request->session()->flash('auth', [
            'user_id' => $user->id
        ]);

        $code = ActiveCode::generateCode($user);

        $user->notify(new ActiveCodeNotification($code , $user->phone));

        return redirect(route('sendtoken'))->with(['phone' => $phone]);
    }

    protected function convertPersianToEnglishNumbers($string) {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persianNumbers, $englishNumbers, $string);
    }

}
