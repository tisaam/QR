<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->supportTickets()->latest()->paginate(10);
        return view('support.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'in:low,medium,high,urgent'
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'status' => 'open',
        ]);

        // Add initial message
        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->description,
            'is_admin' => false,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created.');
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id() && !Auth::user()->isSuperAdmin()) abort(403);
        
        $messages = $ticket->messages()->orderBy('created_at', 'asc')->get();
        return view('support.tickets.show', compact('ticket', 'messages'));
    }

    public function addMessage(Request $request, SupportTicket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => Auth::user()->isSuperAdmin(),
        ]);

        if ($ticket->status === 'waiting') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back();
    }

    public function close(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'closed', 'resolved_at' => now()]);
        return back()->with('success', 'Ticket closed.');
    }
}