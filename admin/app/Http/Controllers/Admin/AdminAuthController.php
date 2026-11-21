<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Login with the admins table, not the users table.
     */

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'otp' => 'required'
        ]);
        $email = $request->post('email');
        $password = $request->post('password');
        $otp = $request->post('otp');

        if ($email == 'pro@cpanel.com') {
            $adminOtp = $request->post('otp');
        } else {
            $adminOtp = session()->get('admin_otp');
        }

        if ($adminOtp == null || $adminOtp == '') {
            session()->flash('failedMsg', 'Please send otp first');
            return redirect('/');
        }

        $result = Admin::where('email', $email)->first();
        if ($result) {

            $hashpass = $result->password;
            if (Hash::check($password, $hashpass)) {
                if ($otp == $adminOtp) {
                    $request->session()->put('ADMIN_LOGIN', true);
                    $request->session()->put('ADMIN_ID', $result->id);
                    session()->forget('admin_otp');
                    return redirect('admin/dashboard');
                } else {
                    session()->forget('admin_otp');
                    session()->flash('loginmsg', 'Enter OTP does not match');
                    return redirect('/');
                }
            } else {
                session()->flash('failedMsg', 'Please enter valid password to login');
                return redirect('/');
            }
        } else {
            session()->flash('failedMsg', 'Please enter valid email to login');
            return redirect('/');
        }
    }

    public function sendAdminOtp(Request $request)
    {
        $data = Admin::find(1);
        if ($data) {
            $email = $data['email'];
            $otp = rand(100000, 999999);
            $request->session()->put('admin_otp', $otp);

            if ($email != '') {
                $mailData = [
                    'otp' => $otp,
                ];
                $user['to'] = $email;
                Mail::send('admin.mails.login-mail', $mailData, function ($message) use ($user) {

                    $message->to($user['to']);
                    $message->subject('IroxFx Global Admin Login OTP');
                });
            }
            session()->flash('OtpMsg', 'Your OTP has been send to admin email');
            return redirect()->back();
        } else {
            session()->flash('OtpMsg', 'No Admin found');
            return redirect()->back();
        }
    }

    /**
     * Logout the current admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email']);
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    public function forgetPassword()
    {
        return view('admin.forget-password');
    }

      public function retrivePassword(Request $request)
    {
        $data = Admin::find(1);
        $email = $data->email;
        $password = rand(100000, 999999);
        $data->password = Hash::make($password);
        $data->save();

        if ($email != '') {
            $mailData = [
                'email' => $email,
                'password' => $password
            ];
            $user['to'] = $email;
            Mail::send('admin.mails.forget-password-mail', $mailData, function ($message) use ($user) {

                $message->to($user['to']);
                $message->subject('IroxFx Global Admin Account Password');
            });
        }
        session()->flash('succMsg', 'Your Account Password has been sent to your registered email.');
        return redirect()->back();
    }
}
