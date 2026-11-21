<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use App\Models\MemberDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MemberAuthController extends Controller
{
    public function login(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('MEMBER_ID')) {
            return redirect()->route('member.dashboard');
        }

        return view('member.auth.login');
    }

    public function loginSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'memberid' => 'required',
            'password' => 'required',
        ]);
        $memberid = $request->post('memberid');
        $password = $request->post('password');

        $result = MemberDetail::where('memberid', $memberid)->first();
        if ($result) {
            if ($result->status == 'Blocked') {
                session()->flash('failedMsg', 'Your account is blocked.');

                return redirect('login')->withInput();
            }
            $hashed = $result->password;
            if (Hash::check($password, $hashed)) {
                session()->put('dashImgModel1', true);
                $request->session()->put('MEMBER_LOGIN', true);
                $request->session()->put('MEMBER_ID', $result->memberid);

                return redirect('member/dashboard');
            } else {
                session()->flash('failedMsg', 'Please enter valid Password to login');

                return redirect('login')->withInput();
            }
        } else {
            session()->flash('failedMsg', 'Please enter valid Memberid to login');

            return redirect('login')->withInput();
        }
    }

    public function signup(Request $request, $id = null)
    {
        $sponsorid['sdata'] = $id ?? $request->query('sponsor') ?? $request->query('sdata') ?? '';
        try {
            $sponsorid['cdata'] = Country::all();
        } catch (\Exception $e) {
            $sponsorid['cdata'] = collect();
        }

        return view('member.auth.signup')->with($sponsorid);
    }

    public function signupSubmit(Request $request)
    {
        $request->merge([
            'sponsor' => strtoupper(trim((string) $request->input('sponsor'))),
        ]);

        $request->validate([
            'sponsor' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:member_details,email',
            'mobile' => 'required|min:10|max:10',
            'country' => 'required',
            'password' => 'required|min:5|max:50',
            'password_confirmation' => 'required|same:password',
            'txn_password' => 'required|min:5|max:50',
        ], [
            'password_confirmation.required' => 'The confirm password field is required.',
            'password_confirmation.same' => 'The confirm password must match the password.',
        ]);
        $sponsorid = strtoupper(trim((string) $request->post('sponsor')));
        $spon = MemberDetail::whereRaw('TRIM(UPPER(memberid)) = ?', [$sponsorid])->first();

        if (! $spon) {
            return redirect()->back()
                ->withErrors(['sponsor' => 'The selected sponsor is invalid.'])
                ->withInput();
        }

        $sponName = $spon->name;
        $email = $request->post('email');
        $mobile = $request->post('mobile');
        $country = $request->post('country');

        $status = trim((string) ($spon->status ?? ''));

        if ($status !== '' && strtolower($status) !== 'active') {
            session()->flash('failedMsg', 'Sponsor account is not activated.');

            return redirect()->back()->withInput();
        }

        $check = MemberDetail::where('mobile', $mobile)->first();
        if ($check) {
            session()->flash('failedMsg', 'This mobile is already registered');

            return redirect()->back();
        }
        $var = new MemberDetail;
        $var->memberid = userId();
        $var->random_address = generateRandomAddress();
        $var->sponsorid = $sponsorid;
        $var->sponsor_name = $sponName;
        $var->name = $request->post('name');
        $var->email = $email;
        $var->country = $country;
        $var->mobile = $request->post('mobile');
        $var->country = $request->post('country');
        $password = $request->post('password');
        $var->password = Hash::make($password);
        $txnpass = $request->post('txn_password');
        $var->txn_password = Hash::make($txnpass);
        $var->country = $request->post('country');
        $var->save();
        $data = MemberDetail::find($var->id);
        $userid = $data->memberid;
        $email = $data->email;
        $name = $data->name;
        $mobile = $data->mobile;
        if ($email != '') {
            $mailData = [
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'memberid' => $userid,
                'password' => $password,
                'txn_password' => $txnpass,
            ];

            $user['to'] = $email;
            Mail::send('member.mails.mail', $mailData, function ($message) use ($user) {

                $message->to($user['to']);
                $message->subject('IroxFx Global Member Login Credentials');
            });
        }
        $request->session()->put('name', $request->post('name'));
        $request->session()->put('userid', $userid);
        $request->session()->put('email', $email);
        $request->session()->put('mobile', $mobile);
        $request->session()->put('country', $country);
        $request->session()->put('password', $request->post('password'));
        $request->session()->put('txn_password', $txnpass);

        session()->flash('successMsg', 'Account created successfully.');

        return redirect('/member/account-created');
    }

    public function checkSponsor(Request $request): JsonResponse
    {
        $sponsorid = $request->post('sponsor');
        if ($sponsorid != '') {
            $data = MemberDetail::where('memberid', $sponsorid)->first();
            if ($data) {

                // if ($data['status'] == 'Temp') {
                //     return response()->json([
                //         'code' => 0,
                //         'data' => '<span class="text-danger">This Sponsor Id is not active. Please change sponsor id</span>'
                //     ]);
                // } elseif ($data['status'] == 'Deactive') {
                //     return response()->json([
                //         'code' => 0,
                //         'data' => '<span class="text-danger">This Sponsor Id is Deactive. Please change sponsor id</span>'
                //     ]);
                // } elseif ($data['status'] == 'Active') {
                return response()->json([
                    'code' => 1,
                    'data' => '<span class="text-success">Sponsor Name :'.$data['name'].'</span>',
                ]);
                // }
            } else {
                return response()->json([
                    'code' => 1,
                    'data' => '<span class="text-danger">No Data Found with this Id</span>',
                ]);
            }
        } else {
            return response()->json([
                'code' => 0,
                'data' => '',
            ]);
        }
    }

    public function accountCreated()
    {
        $memberId = session('userid');
        $member = null;

        if ($memberId) {
            $member = MemberDetail::where('memberid', $memberId)->first();
        }

        return view('member.auth.account-created', compact('member'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('MEMBER_ID');

        return redirect()
            ->route('member.login')
            ->with('success', 'You have been logged out.');
    }

    public function forgotPassword()
    {
        return view('member.auth.forgot-password');
    }

    public function sendForgotPassword(Request $request)
    {
        $memberid = $request->post('memberid');

        $data = MemberDetail::where('memberid', $memberid)->first();
        if ($data) {

            $name = $data['name'];
            $email = $data['email'];
            $memberid = $data['memberid'];
            $password = rand(100000, 999999);
            $data->password = Hash::make($password);
            $data->save();

            if ($email != '') {
                $mailData = [
                    'name' => $name,
                    'email' => $email,
                    'memberid' => $memberid,
                    'password' => $password,
                ];
                $user['to'] = $email;
                Mail::send('member.mails.forget-password-mail', $mailData, function ($message) use ($user) {

                    $message->to($user['to']);
                    $message->subject('IroxFx Global Member Account Password');
                });
            }
            session()->flash('successMsg', 'Your Account Password has been sent to your registered email.');

            return redirect()->back();
        } else {
            session()->flash('failedMsg', 'Entered MemberId is not registered with us!');

            return redirect()->back();
        }
    }

    public function verifyAdmin($memberid, $code)
    {
        if ($memberid != '' && $code != '') {
            $check = Admin::where('login_code', $code)->first();

            if ($check && $check->login_code != null) {
                // Parse the secure code: memberid_timestamp_randomString
                $parts = explode('_', $check->login_code);

                if (count($parts) === 3) {
                    $targetMemberId = $parts[0];
                    $timestamp = (int) $parts[1];

                    // Security checks:
                    // 1. The member ID in the URL matches the member ID in the code.
                    // 2. The code was generated in the last 60 seconds (prevents replay/brute-force).
                    if ($targetMemberId === $memberid && (time() - $timestamp) <= 60) {
                        // Clear the code immediately (one-time use)
                        $check->login_code = null;
                        $check->save();

                        session()->put('MEMBER_LOGIN', true);
                        session()->put('MEMBER_ID', $memberid);

                        return redirect()->route('member.dashboard');
                    }
                }
            }
        }
    }

    public function getMember(Request $request)
    {
        $memberid = $request->post('memberid');
        if ($memberid != '') {
            $data = MemberDetail::where('memberid', $memberid)->first();
            if ($data) {
                return response()->json([
                    'code' => 1,
                    'data' => '<span class="text-success" style="color:green;">Member Name :'.$data['name'].'</span>',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'data' => '<span class="text-danger" style="color:red;">No Data Found with this Id</span>',
                ]);
            }
        } else {
            return response()->json([
                'code' => 0,
                'data' => '',
            ]);
        }
    }
}
