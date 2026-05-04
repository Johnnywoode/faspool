<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('support.index', compact('tickets'));
    }

    /**
     * Store a newly created support ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $user = Auth::user();

        $ticket = SupportTicket::create([
            'uid' => 'TKT-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Support ticket created successfully.');
    }

    /**
     * Display the specified support ticket.
     */
    public function show(SupportTicket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $ticket->load('messages.user');
        
        return view('support.show', compact('ticket'));
    }

    /**
     * Add a message to an existing ticket.
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $this->authorize('view', $ticket);

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => Auth::user()->hasRole('admin'),
        ]);

        if (!Auth::user()->hasRole('admin')) {
            $ticket->update(['status' => 'open']);
        } else {
            $ticket->update(['status' => 'answered']);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Close the ticket.
     */
    public function close(SupportTicket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $ticket->update(['status' => 'closed']);
        
        return back()->with('success', 'Ticket closed successfully.');
    }
}
