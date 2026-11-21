<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class MemberProfileController extends Controller
{
    public function summary()
    {
        $memberid = session('MEMBER_ID');
        $result['memberData'] = MemberDetail::where('memberid', $memberid)->first();
        return view('member.profile.summary')->with($result);
    }

    public function editProfile()
    {
        $memberid = session('MEMBER_ID');

        if (!$memberid) {
            return redirect()->route('member.login');
        }

        $memberData = MemberDetail::where('memberid', $memberid)->first();

        if (!$memberData) {
            return redirect()->route('member.login')->with('failedMsg', 'Member not found.');
        }

        try {
            $cdata = \App\Models\Country::all();
        } catch (\Exception $e) {
            $cdata = collect();
        }

        return view('member.profile.edit-profile', compact('memberData', 'cdata'));
    }



    public function update(Request $request): RedirectResponse
    {
        $member = $request->session()->get('member');
        if (!$member) {
            return redirect()->route('member.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'phone_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:80',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:80',
            'state' => 'nullable|string|max:80',
            'zip' => 'nullable|string|max:20',
        ]);

        $member = array_merge($member, $validated);
        $request->session()->put('member', $member);

        return redirect()
            ->route('member.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        return view('member.profile.change-password')->with($result);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|required_with:password_confirm|same:password_confirm|min:6',
            'password_confirm' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'pass_errors');
        }

        $memberid = session('MEMBER_ID');

        $var = MemberDetail::where('memberid', $memberid)->first();

        if (!$var) {
            return redirect()->back()->with('failedMsg', 'Member not found.');
        }

        $var->password = Hash::make($request->post('password'));
        $var->save();

        session()->flash('successMsg_pass', 'Password has been updated successfully');
        return redirect()->back();
    }
    public function txnpassChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|required_with:password_confirm|same:password_confirm|min:6',
            'password_confirm' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'txn_errors');
        }

        $id = $request->post('id');
        $var = MemberDetail::find($id);
        $var->txn_password = Hash::make($request->post('password'));
        $var->save();

        session()->flash('successMsg', 'Transaction Password has been updated successfully');
        return redirect()->back();
    }


     public function insertPdata(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $var = MemberDetail::where('memberid', $memberid)->first();
        $status = $var->status;
        $var->gender = $var->gender == null ? $request->post('gender') : $var->gender;
        $var->dob = $var->dob == null ? $request->post('dob') : $var->dob;
        $var->country = $var->country;
        if ($request->hasfile('profile_image')) {
            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                session()->flash('failedMsg', 'Allowed image type is jpg, jpeg, png. Please change image type');

                return redirect()->back();
            }
            $filename = time().'.'.$extension;
            $file->move(public_path('uploads'), $filename);
            $var->profile_image = $filename;
        }
        $var->save();

        session()->flash('successMsg', 'Profile Details have been updated');

        return redirect()->back();
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $member = $request->session()->get('member');
        if (!$member) {
            return redirect()->route('member.login');
        }

        $request->validate([
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Ensure folder exists and move file
            $destinationPath = public_path('uploads/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $path = 'uploads/avatars/' . $filename;

            $member['avatar'] = $path;
            $request->session()->put('member', $member);
        }

        return redirect()
            ->route('member.profile')
            ->with('success', 'Avatar updated successfully.');
    }

    public function walletAddress()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        return view('member.profile.wallet-address')->with($result);
    }

    public function insertWalletAddress(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required',
        ]);

        $id = $request->post('id');
        $var = MemberDetail::find($id);

        if (!empty($var->wallet_address)) {
            session()->flash('failedMsg', 'Wallet address can be updated only one time.');
            return redirect()->back();
        }

        $var->wallet_address = $request->post('wallet_address');
        $var->save();

        session()->flash('successMsg', 'Wallet Address have been inserted successfully!');
        return redirect()->back();
    }
    
    public function kyc()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['kycData'] = $result['data'];

        return view('member.profile.kyc')->with($result);
    }

    public function updateKyc(Request $request)
    {
        $request->validate([
            'aadhar_no' => 'required|min:12|max:12',
            'front_image' => 'required',
            'back_image' => 'required',
            'selfie_image' => 'required',
        ]);

        $id = $request->post('id');
        $aadhar_no = $request->post('aadhar_no');

        $aadhar_check = MemberDetail::where('aadhar_no', $aadhar_no)->first();
        if ($aadhar_check) {
            session()->flash('failedMsg', 'Aadhar No is already registered. Please change Aadhar No');

            return redirect()->back();
        }

        $memberid = session('MEMBER_ID');
        $var = MemberDetail::where('memberid', $memberid)->first();
        $var->aadhar_no = $request->post('aadhar_no');
        if ($request->hasfile('selfie_image')) {
            $file = $request->file('selfie_image');
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
                session()->flash('failedMsg', 'This file type is not allowed. Image must be jpg, jpeg or png!');

                return redirect()->back();
            }
            $filename = 'SELFIE'.time().'.'.$ext;
            $file->move(public_path('uploads'), $filename);
            $var->selfie_image = $filename;
        }
        if ($request->hasfile('front_image')) {
            $file = $request->file('front_image');
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
                session()->flash('failedMsg', 'This file type is not allowed. Image must be jpg, jpeg or png!');

                return redirect()->back();
            }
            $filename = 'FRONT'.time().'.'.$ext;
            $file->move(public_path('uploads'), $filename);
            $var->front_image = $filename;
        }
        if ($request->hasfile('back_image')) {
            $file = $request->file('back_image');
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
                session()->flash('failedMsg', 'This file type is not allowed. Image must be jpg, jpeg or png!');

                return redirect()->back();
            }
            $filename = 'BACK'.time().'.'.$ext;
            $file->move(public_path('uploads'), $filename);
            $var->back_image = $filename;
        }
        $var->kyc_date = date('Y-m-d H:i:s');
        $var->kyc_status = 'Submitted';
        $var->save();

        session()->flash('kycMsg', 'KYC Details have been submitted successfully!');

        return redirect()->back();
    }
}
