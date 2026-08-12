<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];

        // Jika username bukan format email, asumsikan itu adalah kode paramedic dan tambahkan @rs.local
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) && !str_contains($username, '@')) {
            $credentials[$this->username()] = strtolower(trim($username)) . '@rs.local';
        }

        // Attempt to log in only active users
        return array_merge($credentials, ['is_active' => true]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $username = $request->{$this->username()};
        
        // Sesuaikan username dengan logika di credentials
        if (!filter_var($username, FILTER_VALIDATE_EMAIL) && !str_contains($username, '@')) {
            $username = strtolower(trim($username)) . '@rs.local';
        }

        // Check if the user exists and password is correct, but the account is inactive.
        $user = User::where($this->username(), $username)->first();

        if ($user && Hash::check($request->password, $user->password) && !$user->is_active) {
            $alert = [
                'title' => 'Aktivasi Diperlukan',
                'text' => 'Akun Anda belum aktif. Silakan hubungi Admin untuk aktivasi.',
                'icon' => 'warning',
            ];

            // Arahkan kembali dengan pesan error khusus untuk akun tidak aktif
            return redirect()->route('login')
                ->withInput($request->only($this->username(), 'remember'))
                ->with('sweet_alert', $alert);
        }

        // Default failed login response
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}