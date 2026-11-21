<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberDetail;
use App\Models\SupportTicket;
use App\Models\TicketText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminSupportController extends Controller
{
    public function newSupport(): View
    {

        $result['support'] = SupportTicket::with('member')->where('support_status', 'New')->get();
        return view('admin.support.new-support-ticket')->with($result);
    }

    public function openSupport(): View
    {

        $result['support'] = SupportTicket::where([['support_status', 'Replied'], ['status', 'Open']])->get();
        return view('admin.support.open-support-ticket')->with($result);
    }

    public function closeSupport(): View
    {
        $result['support'] = SupportTicket::with('member')->where('status', 'Closed')->get();
        return view('admin.support.close-support-ticket')->with($result);
    }

    public function viewSupport(int $id): View|RedirectResponse
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['ticket'] = SupportTicket::with('member')->find($id);

        if (! $result['ticket']) {
            return back()
                ->with('failedMsg', 'Support ticket not found')
                ->with('error', 'Support ticket not found');
        }

        $result['ticket_texts'] = TicketText::where('ticket_id', $result['ticket']->ticket_id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view('admin.support.view-support-ticket')->with($result);
    }

    public function replySupport(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $ticket = SupportTicket::find($id);

        if (! $ticket) {
            return back()
                ->with('failedMsg', 'Support ticket not found')
                ->with('error', 'Support ticket not found');
        }

        DB::transaction(function () use ($ticket, $data) {
            TicketText::create([
                'ticket_id' => $ticket->ticket_id,
                'written_by' => 'Admin',
                'text' => $data['message'],
                'status' => 'Unread',
            ]);

            $ticket->update(['support_status' => 'Replied']);
        });

        return back()->with('success', 'Reply sent successfully');
    }

    public function closeTicket(int $id): RedirectResponse
    {
        $ticket = SupportTicket::find($id);

        if (! $ticket) {
            return back()
                ->with('failedMsg', 'Support ticket not found')
                ->with('error', 'Support ticket not found');
        }

        $ticket->update(['status' => 'Closed']);

        return redirect()
            ->route('admin.support.open-tickets')
            ->with('success', 'Ticket closed successfully');
    }

    public function index(): View
    {
        $tickets = SupportTicket::with('member')->latest()->paginate(10);

        return view('admin.support.index', compact('tickets'));
    }

    public function open(): View
    {
        return $this->openSupport();
    }

    public function closed(): View
    {
        return $this->closeSupport();
    }

    public function show(SupportTicket $support): View
    {
        $support->load('member');

        return view('admin.support.show', compact('support'));
    }

    public function update(Request $request, SupportTicket $support): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:Open,In Progress,Closed'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'admin_reply' => ['nullable', 'string'],
        ]);

        $support->update($data);

        return back()->with('success', 'Updated successfully.');
    }

    public function destroy(SupportTicket $support): RedirectResponse
    {
        $support->delete();

        return redirect()->route('admin.support.index')->with('success', 'Deleted successfully.');
    }
}
