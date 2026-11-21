<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\MemberDetail;
use App\Models\SupportTicket;
use App\Models\TicketText;
use Illuminate\View\View;

class MemberSupportController extends Controller
{  public function createTick()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        return view('member.support.create-ticket')->with($result);
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'issue' => 'required'
        ]);

        $memberid = session('MEMBER_ID');
        if (! $memberid) {
            return redirect()->route('member.login');
        }

        $subject = $request->post('subject');
        $issue = $request->post('issue');
        $ticketid = $memberid . rand(10000, 99999);

        $sup = new SupportTicket();
        $sup->ticket_id = $ticketid;
        $sup->memberid = $memberid;
        $sup->subject = $subject;
        $sup->save();

        $text = new TicketText();
        $text->ticket_id = $ticketid;
        $text->written_by = 'Member';
        $text->text = $issue;
        $text->save();

        session()->flash('createMsg', 'Support Ticket has been created successfully. We will get back to you as soon as possible');
        return redirect()->back();
    }

    public function supportTickets()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['support'] = SupportTicket::where('memberid', $memberid)->orderBy('created_at', 'DESC')->get();
        return view('member.support.support-tickets')->with($result);
    }

    public function ViewsupportTickets($tid)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['support'] = SupportTicket::where('ticket_id', $tid)->first();
        $result['tdata'] = TicketText::where('ticket_id', $tid)->orderBy('created_at', 'ASC')->get();
        $updates = TicketText::where([['ticket_id', $tid], ['written_by', 'Admin']])->get();
        foreach ($updates as $key) {
            $var = TicketText::find($key['id']);
            $var->status = 'Read';
            $var->save();
        }
        return view('member.support.view-support-tickets')->with($result);
    }

    public function MbReplyTicket(Request $request)
    {
        $request->validate([
            'reply' => 'required'
        ]);
        $ticketid = $request->post('ticketid');
        $memberid = session('MEMBER_ID');
        $ticket = SupportTicket::where('ticket_id', $ticketid)
            ->where('memberid', $memberid)
            ->first();

        if (! $ticket) {
            return redirect()
                ->route('member.support.tickets')
                ->with('failedMsg', 'Support ticket not found.');
        }

        $text = new TicketText();
        $text->ticket_id = $ticketid;
        $text->written_by = 'Member';
        $text->text = $request->post('reply');
        $text->save();

        session()->flash('createMsg', 'Your reply has been added to Ticket successfully.');
        return redirect()->back();
    }
}
