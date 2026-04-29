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
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Subject</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-20 text-white" placeholder="Brief description of your issue">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted text-uppercase">Related Order (Optional)</label>
                            <select class="form-select bg-dark border-secondary border-opacity-20 text-white">
                                <option selected disabled>Select an order...</option>
                                <option>Order #12345 - WhatsApp Verification</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted text-uppercase">Message</label>
                            <textarea class="form-control bg-dark border-secondary border-opacity-20 text-white" rows="5" placeholder="Describe your issue in detail..."></textarea>
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
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill small">0 Open</span>
            </div>
            <div class="card-body p-0">
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">No support tickets yet.</p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="card bg-surface border-secondary border-opacity-10 rounded-4 shadow-sm mt-4">
            <div class="card-header bg-transparent border-secondary border-opacity-10 py-3 px-4">
                <h6 class="mb-0 fw-bold"><i class="bi bi-question-circle me-2 text-primary"></i> Frequently Asked Questions</h6>
            </div>
            <div class="card-body p-4">
                <div class="accordion accordion-dark" id="faqAccordion">
                    <div class="accordion-item bg-transparent border-secondary border-opacity-10">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How long does it take to receive SMS?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Most SMS messages arrive within 1-3 minutes. If you don't receive SMS within 15 minutes, you can cancel the order and get a refund.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-secondary border-opacity-10">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Can I get a refund if SMS is not received?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Yes! If your order expires without receiving SMS, the amount will be refunded to your wallet automatically.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-secondary border-opacity-10">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                What payment methods are accepted?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                We currently accept Paystack payments (cards, bank transfers). More payment methods will be added soon.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-surface { background-color: var(--surface-dark); }
    .accordion-dark .accordion-button { box-shadow: none; }
    .accordion-dark .accordion-button:not(.collapsed) { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
</style>
@endsection
