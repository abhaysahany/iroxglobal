<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\MemberDetail;
use App\Models\Country;
use App\Models\Deduction;
use App\Models\PoolUplineMember;
use App\Models\DashImage;
use App\Models\WeeklyCount;
use App\Models\DashMessage;
use App\Models\FakeDatas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminMemberController extends Controller
{
    public function index(Request $request): View
    {
        $stateMap = [
            'active' => 'Active',
            'temp' => 'Temp',
            'deactive' => 'Deactive',
            'blocked' => 'Blocked',
        ];
        $requestState = $request->input('state', 'all');
        $selectedState = array_key_exists($requestState, $stateMap) ? $requestState : 'all';

        $members = MemberDetail::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('memberid', 'like', "%{$search}%");
                });
            })
            ->when($selectedState !== 'all', function ($query) use ($stateMap, $selectedState) {
                $query->where('status', $stateMap[$selectedState]);
            })
            ->latest()
            ->get();

        return view('admin.members.index', compact('members', 'selectedState'));
    }

     public function show(MemberDetail $member): View
    {
        $cdata = Country::all();
        return view('admin.members.show', compact('member', 'cdata'));
    }
    
     public function update(Request $request)
    {
        $id = $request->post('id');
        $request->validate([
            'email' => [
                'required',
            ]
        ]);

        $var = MemberDetail::find($id);
        $var->name = $request->post('name');
        $var->father_name = $request->post('father');
        $var->dob = $request->post('dob');
        $var->email = $request->post('email');
        $var->mobile = $request->post('mobile');
        $var->address = $request->post('address');
        $var->city = $request->post('city');
        $var->state = $request->post('state');
        $var->country = $request->post('country');
        $var->gender = $request->post('gender');
        if ($request->hasfile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                session()->flash('failedMsg', 'Allowed image type is jpg, jpeg, png. Please change image type');
                return redirect()->back();
            }
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads'), $filename);
            $var->profile_image = $filename;
        }
        $var->save();

        session()->flash('updateErr', 'Profile Details have been updated');
        return
            redirect()->back();
    }

    public function updateStatus(Request $request, $id)
    {
        $var = MemberDetail::find($id);
        $status = $var->status;
        if ($status == 'Active') {
            $var->status = 'Blocked';
            $var->save();
            session()->flash('error', 'Status has been Blocked successfully.');
            return redirect()->back();
        } elseif ($status == 'Blocked') {
            $var->status = 'Active';
            $var->save();
            session()->flash('success', 'Status has been Activated successfully.');
            return redirect()->back();
        }
    }
    
     public function memberLogin(Request $request, $id)
    {
        $result = MemberDetail::find($id);
        $code = $result->memberid.'_'.time().'_'.Str::random(32);
        $upd = Admin::find(session('admin_id'));
        $upd->login_code = $code;
        $upd->save();

        if ($result) {
            $memberUrl = config('detailsApp.app_url');

            return redirect()->to($memberUrl.'/verify-admin/'.$result->memberid.'/'.$upd->login_code);
        }
    }
    
     public function security()
    {
        $result['data'] = MemberDetail::orderby('created_at', 'desc')->get();
        return view('admin.member-security')->with($result);
    }

    public function securityUpdate(Request $request)
    {
        $password = $request->post('password');
        $txnpassword = $request->post('txnpassword');
        $var = MemberDetail::find($request->post('id'));
        $var->password = Hash::make($password);
        $var->txn_password = Hash::make($txnpassword);
        $var->save();

        session()->flash('message', 'Security Details have been updated successfully!');
        return redirect()->back();
    }
    
    public function getMember(Request $request)
    {
        $memberid = $request->post('memberid');
        if ($memberid != '') {
            $data = MemberDetail::where('memberid', $memberid)->first();
            if ($data) {
                return response()->json([
                    'code' => 1,
                    'name' => $data->name,
                    'data' => '<span class="text-success" style="color:green;">Member Name :'.$data['name'].'</span>',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'name' => null,
                    'data' => '<span class="text-danger" style="color:red;">No Data Found with this Id</span>',
                ]);
            }
        } else {
            return response()->json([
                'code' => 0,
                'name' => null,
                'data' => '',
            ]);
        }
    }
    
    
    public function dashMsgDelete($id)
    {
        $var = DashMessage::where('id', $id)->delete();
        session()->flash('delMsg', 'Message has been deleted successfully');

        return redirect()->back();
    }

    public function dashImages()
    {
        $result['data'] = DashImage::all();

        return view('admin.dash-images')->with($result);
    }

    public function uploadDashImg(Request $request)
    {
        $var = DashImage::find(1);
        $var->title = $request->post('title');
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                session()->flash('failedMsg', 'Allowed image type is jpg, jpeg, png. Please change image type');

                return redirect()->back();
            }
            $filename = time().'.'.$extension;
            $file->move(public_path('admin_assets/images/dash-img'), $filename);
            $var->image = $filename;
        }
        $var->save();

        session()->flash('uploadMsg', 'Images has been uploaded successfully');

        return redirect()->back();
    }

    public function dashImgStatusChange(Request $request, $id)
    {
        $var = DashImage::find($id);
        $status = $var->status;
        if ($status == 'Live') {
            $var->status = 'Non Live';
        } else {
            $var->status = 'Live';
        }
        $var->save();

        session()->flash('uploadMsg', 'Image Status has been changed successfully');

        return redirect()->back();
    }
    
     public function deduction()
    {
        $result['data'] = Deduction::find(1);

        return view('admin.deduction')->with($result);
    }

    public function dedtion(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|gt:0',
            'walletToP2P' => 'required|numeric|gt:0',
        ]);
        $var = Deduction::find(1);
        $var->rate = $request->post('rate');
        $var->walletToP2P = $request->post('walletToP2P');
        $var->save();

        session()->flash('successMsg', 'Deduction rates have been updated successfully');

        return redirect()->back();
    }
    
    public function walletAddress()
    {
        $result['data'] = MemberDetail::orderBy('created_at', 'desc')->get();

        return view('admin.wallet-address')->with($result);
    }

    public function updateWallet(Request $request)
    {
        $var = MemberDetail::find($request->post('id'));
        $var->wallet_address = $request->post('address');
        $var->save();
        session()->flash('update', 'Wallet Address has been updated successfully.');

        return redirect()->back();
    }
    
    public function poolMember()
    {
        $result['data'] = PoolUplineMember::orderBy('created_at', 'desc')->get();
        return view('admin.poolmember')->with($result);
    }
    
    public function weeklyCount()
    {
        $result['data'] = WeeklyCount::orderBy('created_at', 'desc')->get();
        return view('admin.weekly-counts')->with($result);
    }

    public function AddonData()
    {
        $result['fakeData'] = FakeDatas::all();
        return view('admin.add-on-data')->with($result);
    }


     public function AddonDataInsert(Request $request)
    {
        $validated = $request->validate([
            'memberid' => 'required',
            'my_direct' => 'required|integer|min:0',
            'total_team' => 'required|integer|min:0',
            'global_members' => 'required|integer|min:0',
            'total_withdrawal' => 'required|numeric|min:0',
            'p2p_wallet' => 'required|numeric|min:0',
            'rank_income' => 'required|numeric|min:0',
            'daily_income' => 'required|numeric|min:0',
            'referral_income' => 'required|numeric|min:0',
            'global_rec_income' => 'required|numeric|min:0',
            'global_rec_star_income' => 'required|numeric|min:0',
        ]);
        $memberid = $validated['memberid'];
        $var = MemberDetail::where('memberid', $memberid)->first();
        if (!$var) {
            session()->flash('failedMsg', 'Entered Memberid is not Available. Please change memberid');
            return redirect()->back();
        }
        if ($var->status != 'Active') {
            session()->flash('failedMsg', 'Entered Memberid is not Active. Please change memberid');
            return redirect()->back();
        }

        if (FakeDatas::where('memberid', $memberid)->exists()) {
            session()->flash('failedMsg', 'Entered Memberid is already available in Addon Data. Please change memberid');
            return redirect()->back();
        }

        FakeDatas::create($validated);

        session()->flash('successMsg', 'Data Inserted Successfully!');
        return redirect()->back();
    }

    public function AddonDataEdit(Request $request, $id)
    {
        $result['data'] = FakeDatas::findOrFail($id);
        return view('admin.addon-data-edit')->with($result);
    }

    public function AddonDataUpdate(Request $request)
    {
        $validated = $request->validate([
            'memberid' => 'required',
            'my_direct' => 'required|integer|min:0',
            'total_team' => 'required|integer|min:0',
            'global_members' => 'required|integer|min:0',
            'total_withdrawal' => 'required|numeric|min:0',
            'p2p_wallet' => 'required|numeric|min:0',
            'rank_income' => 'required|numeric|min:0',
            'daily_income' => 'required|numeric|min:0',
            'referral_income' => 'required|numeric|min:0',
            'global_rec_income' => 'required|numeric|min:0',
            'global_rec_star_income' => 'required|numeric|min:0',
        ]);
        $id = $request->post('id');
        $memberid = $validated['memberid'];
        $var = MemberDetail::where('memberid', $memberid)->first();
        if (!$var) {
            session()->flash('failedMsg', 'Entered Memberid is not Available. Please change memberid');
            return redirect()->back();
        }
        if ($var->status != 'Active') {
            session()->flash('failedMsg', 'Entered Memberid is not Active. Please change memberid');
            return redirect()->back();
        }

        FakeDatas::findOrFail($id)->update($validated);

        session()->flash('successMsg', 'Data updated Successfully!');
        return redirect()->back();
    }
    
}
