@extends('layouts.master')

@section('title', 'Order #' . substr($order->id, 0, 8))

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-xl-8">
        <div class="mb-4">
            <a href="{{ route('orders.index') }}" class="btn btn-link text-secondary text-decoration-none p-0 mb-3">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
            <h2 class="fw-bold h3 mb-1">Waiting for <span class="text-primary">SMS</span></h2>
            <p class="text-muted small">Order ID: {{ $order->id }}</p>
        </div>

        <div class="card bg-surface border-secondary border-opacity-10 rounded-5 shadow-lg overflow-hidden">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                        <div class="small text-secondary text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">Your Number</div>
                        <h1 class="display-4 fw-bold text-white mb-3" id="phoneNumber">{{ $order->number }}</h1>
                        <div class="d-flex justify-content-center justify-content-lg-start gap-2">
                            <button class="btn btn-secondary btn-sm rounded-3 px-3" onclick="copyToClipboard('{{ $order->number }}')">
                                <i class="bi bi-copy me-2"></i> Copy Number
                            </button>
                            <div class="badge bg-primary bg-opacity-10 text-primary p-2 px-3 rounded-3 d-flex align-items-center">
                                <i class="bi bi-clock me-2"></i> <span id="timer">15:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div id="statusContainer" class="p-4 rounded-5 bg-dark bg-opacity-50 border border-secondary border-opacity-10 position-relative overflow-hidden">
                            <!-- Waiting State -->
                            <div id="waitingState">
                                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem; border-width: 0.25rem;"></div>
                                <h5 class="fw-bold text-white">Listening for SMS...</h5>
                                <p class="text-muted small mb-0">Codes are usually delivered within 60 seconds. Do not refresh this page.</p>
                            </div>

                            <!-- Success State -->
                            <div id="successState" style="display: none;">
                                <div class="icon-box bg-success bg-opacity-20 text-success rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check-lg fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-white">Code Received!</h5>
                                <div class="bg-success bg-opacity-10 border border-success border-opacity-20 p-3 rounded-4 mt-3">
                                    <h2 class="display-5 fw-bold text-success mb-0" id="otpCode">------</h2>
                                </div>
                                <button class="btn btn-success w-100 mt-4 py-3 rounded-4 fw-bold" onclick="copyOtp()">
                                    Copy Code <i class="bi bi-clipboard-check ms-2"></i>
                                </button>
                            </div>

                            <!-- Expired State -->
                            <div id="expiredState" style="display: none;">
                                <i class="bi bi-exclamation-octagon text-danger display-4 mb-3"></i>
                                <h5 class="fw-bold text-white">Order Expired</h5>
                                <p class="text-muted small">You will be automatically refunded if no SMS was received.</p>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 mt-3">Try Again</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-secondary bg-opacity-5 border-top border-secondary border-opacity-10 p-4 px-5">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="small text-muted">Service</div>
                        <div class="fw-bold text-white">{{ $order->service->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Country</div>
                        <div class="fw-bold text-white">{{ $order->country->name }} {{ $order->country->flag }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Cost</div>
                        <div class="fw-bold text-success">${{ number_format($order->price, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        // Toast notification could be added here
    }

    function copyOtp() {
        const otp = document.getElementById('otpCode').innerText;
        copyToClipboard(otp);
    }

    // Polling Logic
    const orderId = "{{ $order->id }}";
    let pollInterval;

    function pollSms() {
        $.ajax({
            url: `/orders/${orderId}/check`,
            method: 'GET',
            success: function(data) {
                if (data.status === 'received') {
                    clearInterval(pollInterval);
                    document.getElementById('waitingState').style.display = 'none';
                    document.getElementById('successState').style.display = 'block';
                    document.getElementById('otpCode').innerText = data.sms;
                    
                    // Optional: Update title/favicon to alert user
                    document.title = "Code Received! - {{ config('app.name') }}";
                } else if (data.status === 'expired') {
                    clearInterval(pollInterval);
                    document.getElementById('waitingState').style.display = 'none';
                    document.getElementById('expiredState').style.display = 'block';
                }
            }
        });
    }

    // Start polling every 5 seconds
    pollInterval = setInterval(pollSms, 5000);

    // Timer Logic
    let timeLeft = 15 * 60;
    const timerElement = document.getElementById('timer');

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            return;
        }
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.innerText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
</script>
@endsection
