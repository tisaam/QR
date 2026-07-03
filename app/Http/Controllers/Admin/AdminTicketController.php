<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('messages.user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->update(['assigned_to' => $request->assigned_to, 'status' => 'in_progress']);
        return back()->with('success', 'Ticket assigned.');
    }

    public function resolve(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'resolved', 'resolved_at' => now()]);
        return back()->with('success', 'Ticket marked as resolved.');
    }
}