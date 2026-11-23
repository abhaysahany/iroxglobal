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
     * Generate dynamic captcha image and store code in session.
     */
    public function captcha(Request $request)
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $request->session()->put('admin_captcha', $code);

        $width = 140;
        $height = 46;
        $image = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($image, 243, 244, 246);
        imagefill($image, 0, 0, $bg);

        // Add background noise lines
        for ($i = 0; $i < 4; $i++) {
            $lineColor = imagecolorallocate($image, random_int(190, 220), random_int(190, 220), random_int(190, 220));
            imageline($image, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lineColor);
        }

        // Add background noise dots
        for ($i = 0; $i < 50; $i++) {
            $dotColor = imagecolorallocate($image, random_int(180, 215), random_int(180, 215), random_int(180, 215));
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $dotColor);
        }

        $textColor = imagecolorallocate($image, 15, 23, 42);

        for ($i = 0; $i < strlen($code); $i++) {
            $x = 18 + ($i * 23);
            $y = random_int(12, 16);
            imagestring($image, 5, $x, $y, $code[$i], $textColor);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Login with the admins table, not the users table.
     */

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'captcha' => 'required',
        ]);

        $captcha = strtoupper(trim((string) $request->post('captcha')));
        $sessionCaptcha = strtoupper(trim((string) $request->session()->get('admin_captcha')));

        if (empty($sessionCaptcha) || $captcha !== $sessionCaptcha) {
            $request->session()->forget('admin_captcha');
            $request->session()->flash('failedMsg', 'Invalid captcha code entered.');
            return redirect()->back()->withInput($request->only('email'));
        }

        $request->session()->forget('admin_captcha');

        $email = $request->post('email');
        $password = $request->post('password');

        $result = Admin::where('email', $email)->first();
        if ($result) {
            $hashpass = $result->password;
            if (Hash::check($password, $hashpass)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $result->id);
                $request->session()->put('admin_id', $result->id);
                return redirect('admin/dashboard');
            } else {
                $request->session()->flash('failedMsg', 'Please enter valid password to login');
                return redirect('/');
            }
        } else {
            $request->session()->flash('failedMsg', 'Please enter valid email to login');
            return redirect('/');
        }
    }

    /**
     * Logout the current admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email', 'ADMIN_ID', 'ADMIN_LOGIN']);
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
