@extends('layouts.master')

@section('title', 'Ticket #' . $ticket->uid)

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-8">
        <div class="mb-4">
            <a href="{{ route('support.index') }}" class="btn btn-link text-secondary text-decoration-none p-0 mb-3">
                <i class="bi bi-arrow-left"></i> Back to Help Center
            </a>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold h3 mb-1">{{ $ticket->subject }}</h2>
                    <p class="text-muted small mb-0">Ticket UID: {{ $ticket->uid }} | Priority: <span class="text-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'info') }}">{{ ucfirst($ticket->priority) }}</span></p>
                </div>
                <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'answered' ? 'success' : 'secondary') }} bg-opacity-10 text-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'answered' ? 'success' : 'secondary') }} px-3 py-2 rounded-pill">
                    {{ strtoupper($ticket->status) }}
                </span>
            </div>
        </div>

        <!-- Chat History -->
        <div class="chat-container mb-4">
            @foreach($ticket->messages as $msg)
                <div class="d-flex mb-4 {{ $msg->is_admin ? 'justify-content-start' : 'justify-content-end' }}">
                    <div class="message-wrapper" style="max-width: 80%;">
                        <div class="d-flex align-items-center mb-1 {{ $msg->is_admin ? 'flex-row' : 'flex-row-reverse' }}">
                            <span class="small fw-bold text-{{ $msg->is_admin ? 'primary' : 'white' }} mx-2">{{ $msg->is_admin ? 'Support Agent' : 'You' }}</span>
                            <span class="text-muted" style="font-size: 0.65rem;">{{ $msg->created_at->format('M d, H:i') }}</span>
                        </div>
                        <div class="message-bubble p-3 rounded-4 {{ $msg->is_admin ? 'bg-primary bg-opacity-10 text-white' : 'bg-surface border border-secondary border-opacity-10 text-white' }}">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($ticket->status !== 'closed')
            <!-- Reply Form -->
            <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('support.reply', $ticket->uid) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Your Reply</label>
                            <textarea name="message" class="form-control bg-dark border-secondary border-opacity-20 text-white" rows="4" placeholder="Type your message here..." required></textarea>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">
                                Send Reply <i class="bi bi-send ms-2"></i>
                            </button>
                            <form action="{{ route('support.close', $ticket->uid) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Mark this ticket as resolved?')">
                                    Close Ticket
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-secondary border-0 bg-secondary bg-opacity-10 text-center rounded-4 p-4">
                <i class="bi bi-lock-fill fs-3 mb-2 d-block"></i>
                <h6 class="fw-bold text-white">This ticket has been closed.</h6>
                <p class="text-muted small mb-0">If you still need help, please create a new ticket.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .message-bubble { 
        position: relative;
        word-break: break-word;
    }
</style>
@endsection
