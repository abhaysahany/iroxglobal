<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\MemberDetail;
use App\Models\UplineMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberReferralController extends Controller
{
    public function directMembers()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        if ($result['data'] && (int) ($result['data']->level_downline ?? 0) >= 2) {
            $unassignedDirects = MemberDetail::where([
                ['sponsorid', $result['data']['memberid']],
                ['status', 'Active'],
            ])->where(function ($q) {
                $q->whereNull('direct_type')
                  ->orWhere('direct_type', '')
                  ->orWhere('direct_status', 0);
            })->get();

            foreach ($unassignedDirects as $u) {
                updateDownline($result['data']['memberid']);
                DB::table('member_details')->where('id', $u->id)->update([
                    'direct_status' => 1,
                    'direct_type'   => 'Pool',
                ]);
            }
        }

        $result['directData'] = MemberDetail::where('sponsorid', $result['data']['memberid'])->orderBy('created_at', 'DESC')->get();
        return view('member.levels.direct-members')->with($result);
    }

    public function assignDirectMember(Request $request): RedirectResponse
    {
        $request->validate([
            'memberid' => 'required',
            'type'     => 'required|in:Pool,Level',
        ]);

        $sponsorId = session('MEMBER_ID');
        $memberId  = $request->memberid;
        $type      = $request->type;

        $direct = MemberDetail::where([
            ['memberid', $memberId],
            ['sponsorid', $sponsorId],
        ])->first();

        if (! $direct) {
            session()->flash('failedMsg', 'Direct member not found.');
            return redirect()->back();
        }

        if (strtolower($direct->status) !== 'active') {
            session()->flash('failedMsg', 'Member is not active yet.');
            return redirect()->back();
        }

        if ((int) ($direct->direct_status ?? 0) === 1 || ! empty($direct->direct_type)) {
            session()->flash('failedMsg', 'Member is already assigned.');
            return redirect()->back();
        }

        if ($type === 'Pool') {
            updateDownline($sponsorId);

            DB::table('member_details')->where('id', $direct->id)->update([
                'direct_status' => 1,
                'direct_type'   => 'Pool',
            ]);

            $direct->direct_status = 1;
            $direct->direct_type   = 'Pool';
            $direct->save();

            session()->flash('successMsg', 'Member ' . $memberId . ' successfully assigned as Pool Member.');
        } elseif ($type === 'Level') {
            $sponsor = MemberDetail::where('memberid', $sponsorId)->first();
            if ($sponsor && (int) ($sponsor->level_downline ?? 0) >= 2) {
                session()->flash('failedMsg', 'You already have 2 Level Members. Additional members can only be assigned as Pool Members.');
                return redirect()->back();
            }

            if ($sponsor) {
                $sponsor->level_downline = ($sponsor->level_downline ?? 0) + 1;
                $sponsor->save();

                DB::table('member_details')->where('id', $sponsor->id)->update([
                    'level_downline' => $sponsor->level_downline,
                ]);
            }

            DB::table('member_details')->where('id', $direct->id)->update([
                'direct_status' => 1,
                'direct_type'   => 'Level',
            ]);

            $direct->direct_status = 1;
            $direct->direct_type   = 'Level';
            $direct->save();

            session()->flash('successMsg', 'Member ' . $memberId . ' successfully assigned as Level Member.');
        }

        return redirect()->back();
    }

    public function geneology(Request $request)
    {
        $memberid = trim((string) ($request->query('memberid') ?? $request->query('searchid') ?? session('MEMBER_ID')));

        $result['data'] = null;

        if ($memberid !== '') {
            $result['data'] = MemberDetail::where('memberid', $memberid)
                ->orWhere('name', 'like', '%' . $memberid . '%')
                ->orderByRaw('memberid = ? desc', [$memberid])
                ->first();
        }

        if ($result['data']) {
            $result['tree'] = $this->buildGeneologyTree($result['data']->memberid, 0, 120);
        } else {
            $result['tree'] = [];
        }

        return view('member.levels.geneology')->with($result);
    }

    public function ViewGeneology($did)
    {
        $member = MemberDetail::find($did);

        if (!$member) {
            return redirect()->route('member.referral.genealogy');
        }

        return redirect()->route('member.referral.genealogy', ['memberid' => $member->memberid]);
    }

    public function searchGenelogy(Request $request)
    {
        $request->validate([
            'searchid' => 'required'
        ]);
        $searchid = $request->post('searchid');
        return redirect()->route('member.referral.genealogy', ['memberid' => $searchid]);
    }

    private function buildGeneologyTree($memberid, $depth = 0, $maxDepth = 120)
    {
        if ($depth >= $maxDepth) {
            return [];
        }

        $children = MemberDetail::where('sponsorid', $memberid)
            ->orderBy('id', 'asc')
            ->get();

        $tree = [];

        foreach ($children as $child) {
            $tree[] = [
                'member' => $child,
                'children' => $this->buildGeneologyTree($child->memberid, $depth + 1, $maxDepth)
            ];
        }

        return $tree;
    }

    public function lelMemTable()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        return view('member.levels.level-members-table')->with($result);
    }

    public function lelMemDetails($level)
    {
        $uplines = 'upline_' . $level;
        $result['data'] = MemberDetail::find(session('MEMBER_ID'));
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $ids = UplineMember::where($uplines, $memberid)->pluck('memberid');
        $result['users'] = MemberDetail::whereIn('memberid', $ids)->orderBy('created_at', 'DESC')->get();
        $result['levelNo'] = $level;
        return view('member.levels.level-members-details')->with($result);
    }

    public function createFakeChain($root_memberid = null)
    {
        if (!$root_memberid) {
            $root_memberid = session('MEMBER_ID');
        }

        if (!$root_memberid) {
            $firstMember = MemberDetail::orderBy('id', 'asc')->first();

            if ($firstMember) {
                $root_memberid = $firstMember->memberid;
            }
        }

        if (!$root_memberid) {
            return response()->json([
                'status' => false,
                'message' => 'Root member not found.'
            ]);
        }

        $root = MemberDetail::where('memberid', $root_memberid)->first();

        if (!$root) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid root member ID.'
            ]);
        }

        $previousSponsorId = $root_memberid;
        $created = 0;
        $skipped = 0;
        $firstFakeId = null;
        $lastFakeId = null;

        for ($i = 1; $i <= 120; $i++) {
            $fakeId = 'FAKE' . str_pad($i, 3, '0', STR_PAD_LEFT);

            $exists = MemberDetail::where('memberid', $fakeId)->first();

            if ($exists) {
                $previousSponsorId = $fakeId;
                $skipped++;
                continue;
            }

            $sponsorName = MemberDetail::where('memberid', $previousSponsorId)->value('name') ?? 'Root';

            $member = new MemberDetail();

            $member->memberid = $fakeId;
            $member->sponsorid = $previousSponsorId;
            $member->sponsor_name = $sponsorName;
            $member->name = 'Fake Member ' . $i;
            $member->email = strtolower($fakeId) . '@demo.local';
            $member->mobile = '9000000' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $member->password = Hash::make('123456');
            $member->txn_password = Hash::make('123456');
            $member->status = 'Temp';
            $member->package = 0;
            $member->p2p_wallet = 0;
            $member->wallet = 0;
            $member->downline = 0;
            $member->investment = 0;
            $member->team = 1;
            $member->self_biz = 0;
            $member->team_biz = 0;
            $member->rank = 'Basic';
            $member->kyc_status = 'Unverified';
            $member->comment = 'Fake chain member for testing only';

            $member->save();

            if (!$firstFakeId) {
                $firstFakeId = $fakeId;
            }

            $lastFakeId = $fakeId;
            $previousSponsorId = $fakeId;
            $created++;
        }

        return response()->json([
            'status' => true,
            'message' => 'Fake member chain created successfully.',
            'root_memberid' => $root_memberid,
            'created' => $created,
            'skipped_existing' => $skipped,
            'first_fake_id' => $firstFakeId,
            'last_fake_id' => $lastFakeId
        ]);
    }
}
