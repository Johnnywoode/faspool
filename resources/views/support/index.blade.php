@extends('layouts.master')

@section('title', 'Support Center')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold h3 mb-1">Support <span class="text-primary">Center</span></h2>
            <p class="text-muted">Get help with your orders or report issues.</p>
        </div>

        <!-- Create Ticket -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Create Ticket</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('support.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small text-muted text-uppercase fw-bold">Subject</label>
                            <input type="text" name="subject" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Brief description of your issue" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted text-uppercase fw-bold">Priority</label>
                            <select name="priority" class="form-select bg-dark border-secondary border-opacity-20 text-white">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted text-uppercase fw-bold">Message</label>
                            <textarea name="message" class="form-control bg-dark border-secondary border-opacity-20 text-white" rows="5" placeholder="Describe your issue in detail..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold">
                                <i class="bi bi-send me-2"></i> Submit Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Tickets -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-ticket-perforated me-2 text-primary"></i> My Tickets</h6>
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill small">{{ $tickets->total() }} Total</span>
            </div>
            <div class="card-body p-0">
                @if($tickets->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">No support tickets yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-secondary bg-opacity-5">
                                <tr>
                                    <th class="px-4 py-3 small text-uppercase">Ticket ID</th>
                                    <th class="py-3 small text-uppercase">Subject</th>
                                    <th class="py-3 small text-uppercase">Status</th>
                                    <th class="py-3 small text-uppercase">Last Update</th>
                                    <th class="px-4 py-3 text-end small text-uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-white small">{{ $ticket->uid }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-white small">{{ $ticket->subject }}</div>
                                            <div class="text-muted small" style="font-size: 0.7rem;">Priority: {{ ucfirst($ticket->priority) }}</div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'answered' ? 'success' : 'secondary') }} bg-opacity-10 text-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'answered' ? 'success' : 'secondary') }} px-2 py-1 rounded small">
                                                {{ strtoupper($ticket->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-muted small">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <a href="{{ route('support.show', $ticket->uid) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                View Thread
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top border-secondary border-opacity-10">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
</style>
@endsection
