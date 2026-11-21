<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\MemberDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminKycController extends Controller
{
    public function kycRequests()
    {
        $result['data'] = MemberDetail::where('kyc_status', 'Submitted')->get();

        return view('admin.kyc.new-kyc-requests')->with($result);
    }

    public function verifiedKyc()
    {
        $result['data'] = MemberDetail::where('kyc_status', 'Verified')->get();

        return view('admin.kyc.verified-kyc')->with($result);
    }

    public function kycView(Request $request, $id)
    {
        $ids = session('ADMIN_ID');
        $result['data'] = Admin::find($ids);
        $result['mdata'] = MemberDetail::find($id);

        return view('admin.kyc.view-kyc')->with($result);
    }

    public function updateKYC(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $var->aadhar_no = $request->post('aadhar_no');
        $var->save();
        session()->flash('successMsg', 'KYC Details have been updated successfully.');

        return redirect()->back();
    }

    public function updateAfront(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $file = $request->file('front_image');
        $ext = $file->getClientOriginalExtension();
        $filename = time().'.'.$ext;

        // Move file to the member project's uploads folder
        $file->move(base_path('../public/uploads'), $filename);

        $var->front_image = $filename;
        $var->save();

        $oldImage1 = $request->post('oldImage1');
        if ($oldImage1 != '') {
            @unlink(base_path('../public/uploads/').$oldImage1);
        }

        session()->flash('successMsg', 'KYC Details have been updated successfully.');

        return redirect()->back();
    }

    public function updateAback(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $file = $request->file('back_image');
        $ext = $file->getClientOriginalExtension();
        $filename = time().'.'.$ext;

        // Move file to the member project's uploads folder
        $file->move(base_path('../public/uploads'), $filename);

        $var->back_image = $filename;
        $var->save();

        $oldImage2 = $request->post('oldImage2');
        if ($oldImage2 != '') {
            @unlink(base_path('../public/uploads/').$oldImage2);
        }

        session()->flash('successMsg', 'KYC Details have been updated successfully.');

        return redirect()->back();
    }

    public function updateSelfie(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $file = $request->file('selfie_image');
        $ext = $file->getClientOriginalExtension();
        $filename = time().'.'.$ext;

        // Move file to the member project's uploads folder
        $file->move(base_path('../public/uploads'), $filename);

        $var->selfie_image = $filename;
        $var->save();

        $oldImage3 = $request->post('oldImage3');
        if ($oldImage3 != '') {
            @unlink(base_path('../public/uploads/').$oldImage3);
        }

        session()->flash('successMsg', 'KYC Details have been updated successfully.');

        return redirect()->back();
    }

    public function kycReject(Request $request, $id)
    {
        $var = MemberDetail::find($id);
        $var->kyc_status = 'Unverified';
        // $var->pan_no = NULL;
        $var->aadhar_no = null;
        // $var->pan_image = NULL;
        $var->front_image = null;
        $var->back_image = null;
        $var->selfie_image = null;
        $var->save();

        session()->flash('kycMesg', 'KYC request has been rejected successfully!');

        return redirect('admin/new-kyc-requests');
    }

    public function kycVerify(Request $request, $id)
    {
        $var = MemberDetail::find($id);
        $var->kyc_status = 'Verified';
        $var->save();

        $memberid = $var->memberid;
        $email = $var->email;
        $name = $var->name;
        $shortMsg = 'KYC Verified';
        $message = 'Your KYC details have been reviewed and successfully verified by our team. You are now eligible to withdraw funds from your account.';

        $mailData = [
            'email' => $email,
            'memberid' => $memberid,
            'kycmessage' => $message,
            'smsg' => $shortMsg,
            'name' => $name,
        ];

        $user['to'] = $email;
        Mail::send('admin.mails.kyc-mail', $mailData, function ($message) use ($user) {

            $message->to($user['to']);
            $message->subject('Iroxfx Global User KYC Verification');
        });

        session()->flash('kycMesg', 'KYC request has been verified successfully!');

        return redirect('admin/new-kyc-requests');
    }
}
