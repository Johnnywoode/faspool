<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #3d8bfd;
            --primary-glow: rgba(61, 139, 253, 0.2);
            --dark-bg: #050608;
            --surface-bg: #0f1115;
            --card-bg: #16191d;
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8f9fa;
            --text-secondary: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Navbar */
        .navbar {
            padding: 1.2rem 0;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(5, 6, 8, 0.85);
            backdrop-filter: blur(12px);
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        /* Hero Section */
        .hero-section {
            padding: 180px 0 120px;
            background: 
                radial-gradient(circle at 10% 10%, var(--primary-glow) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 40%);
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--primary-accent);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 4.8rem;
            font-weight: 800;
            line-height: 1.05;
            margin-bottom: 1.5rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin-bottom: 3rem;
        }

        /* Interactive Card Layout */
        .hero-visual-wrapper {
            position: relative;
            perspective: 1000px;
        }

        .visual-card-container {
            display: flex;
            gap: 20px;
            transform: rotateY(-15deg) rotateX(10deg);
            transition: 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .visual-card-container:hover {
            transform: rotateY(0) rotateX(0);
        }

        .hero-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
            flex: 1;
        }

        .card-left { min-width: 280px; }
        .card-right { min-width: 320px; background: #1c1f24; }

        .service-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 14px;
            margin-bottom: 12px;
            border: 1px solid transparent;
            transition: 0.3s;
        }

        .service-list-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--primary-accent);
        }

        .sms-notification {
            background: #252a31;
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 12px;
            border-left: 4px solid var(--primary-accent);
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from { transform: translateX(20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Trusted Brands */
        .brand-ticker {
            padding: 60px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.01);
        }

        .brand-item {
            opacity: 0.4;
            filter: grayscale(1) invert(1);
            transition: 0.3s;
            height: 35px;
        }

        .brand-item:hover {
            opacity: 1;
            filter: grayscale(0) invert(0);
        }

        /* Features */
        .modern-feature-card {
            background: var(--surface-bg);
            border: 1px solid var(--border-color);
            padding: 40px;
            border-radius: 32px;
            height: 100%;
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .modern-feature-card:hover {
            border-color: var(--primary-accent);
            transform: translateY(-8px);
        }

        .feature-glow {
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle at center, var(--primary-glow) 0%, transparent 60%);
            opacity: 0;
            transition: 0.4s;
            pointer-events: none;
        }

        .modern-feature-card:hover .feature-glow {
            opacity: 1;
        }

        .feature-icon-wrapper {
            width: 64px;
            height: 64px;
            background: rgba(61, 139, 253, 0.1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary-accent);
            margin-bottom: 24px;
        }

        /* Testimonials */
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .testimonial-card {
            background: var(--surface-bg);
            padding: 32px;
            border-radius: 24px;
            border: 1px solid var(--border-color);
        }

        /* Buttons */
        .btn-glow {
            background: var(--primary-accent);
            color: white;
            padding: 14px 32px;
            border-radius: 14px;
            font-weight: 600;
            border: none;
            box-shadow: 0 0 20px var(--primary-glow);
            transition: 0.3s;
        }

        .btn-glow:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px var(--primary-accent);
            color: white;
        }

        .btn-outline-custom {
            border: 1px solid var(--border-color);
            color: white;
            padding: 14px 32px;
            border-radius: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: rgba(255,255,255,0.05);
            border-color: white;
            color: white;
        }

        /* Footer */
        .footer-premium {
            padding: 100px 0 50px;
            background: #08090b;
        }

        .footer-title {
            color: white;
            font-weight: 700;
            margin-bottom: 24px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: 0.2s;
        }

        .footer-link:hover {
            color: var(--primary-accent);
            transform: translateX(5px);
        }

        @media (max-width: 1200px) {
            .hero-title { font-size: 4rem; }
            .visual-card-container { transform: none; }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 3.2rem; }
            .visual-card-container { flex-direction: column; }
            .hero-desc { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://www.smspool.net/assets/img/logo.png" alt="Logo" height="36" class="me-2">
                <span class="fw-bold fs-4 text-white font-heading">FASPOOL</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Our Solution</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Price List</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews">Testimonials</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('login') }}" class="text-white text-decoration-none fw-600">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-glow">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-7" data-aos="fade-right">
                    <div class="hero-badge">
                        <i class="bi bi-stars"></i> Ranked #1 for Non-VoIP Verification
                    </div>
                    <h1 class="hero-title">Cheapest and Fastest Online SMS Verification</h1>
                    <p class="hero-desc">
                        Protect your privacy with premium non-VoIP numbers. Instant OTP delivery for 1200+ services including WhatsApp, Telegram, and Google.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-glow btn-lg px-5">Register Now</a>
                        <a href="#pricing" class="btn btn-outline-custom btn-lg px-5">View Pricing</a>
                    </div>
                    <div class="mt-5 d-flex align-items-center gap-4 text-secondary small">
                        <span><i class="bi bi-check2-circle text-success"></i> 99.9% Uptime</span>
                        <span><i class="bi bi-check2-circle text-success"></i> Instant Delivery</span>
                        <span><i class="bi bi-check2-circle text-success"></i> Global Coverage</span>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-5 mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="hero-visual-wrapper">
                        <div class="visual-card-container">
                            <!-- Card 1: Selection -->
                            <div class="hero-card card-left">
                                <h6 class="fw-bold mb-3 text-secondary">Select Service</h6>
                                <div class="service-list-item">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" width="24" height="24">
                                    <span class="small fw-600">Telegram</span>
                                    <span class="ms-auto text-success small">$0.10</span>
                                </div>
                                <div class="service-list-item">
                                    <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" width="24" height="24">
                                    <span class="small fw-600">Google</span>
                                    <span class="ms-auto text-success small">$0.08</span>
                                </div>
                                <h6 class="fw-bold mb-3 mt-4 text-secondary">Select Country</h6>
                                <div class="service-list-item">
                                    <span class="fs-5 me-2">🇺🇸</span>
                                    <span class="small fw-600">United States</span>
                                </div>
                                <div class="service-list-item">
                                    <span class="fs-5 me-2">🇬🇧</span>
                                    <span class="small fw-600">United Kingdom</span>
                                </div>
                                <button class="btn btn-primary btn-sm w-100 mt-3 py-2 rounded-3">Receive SMS</button>
                            </div>
                            <!-- Card 2: Notifications -->
                            <div class="hero-card card-right">
                                <h6 class="fw-bold mb-4 text-secondary">Incoming SMS Notifications</h6>
                                <div class="sms-notification" style="animation-delay: 0.2s">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-chat-left-text text-primary"></i>
                                        <span class="small fw-bold">Discord</span>
                                    </div>
                                    <p class="small mb-0">Your Discord code is <strong>591821</strong></p>
                                </div>
                                <div class="sms-notification" style="animation-delay: 0.8s">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-google text-primary"></i>
                                        <span class="small fw-bold">Google</span>
                                    </div>
                                    <p class="small mb-0">Your Google code is <strong>849204</strong></p>
                                </div>
                                <div class="sms-notification" style="animation-delay: 1.4s">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-whatsapp text-success"></i>
                                        <span class="small fw-bold">WhatsApp</span>
                                    </div>
                                    <p class="small mb-0">Your WhatsApp code is <strong>122-344</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <div class="brand-ticker">
        <div class="container">
            <div class="row align-items-center justify-content-center g-5 text-center">
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/7/74/Twitch_logo_2019.svg" class="brand-item"></div>
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/0/07/Reddit_logo.svg" class="brand-item"></div>
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" class="brand-item"></div>
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" class="brand-item"></div>
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="brand-item"></div>
                <div class="col-lg-2 col-md-4 col-6"><img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" class="brand-item"></div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <section id="features" class="py-5 mt-5">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold fs-1 mb-3">Enterprise Grade Features</h2>
                <p class="text-secondary max-width-600 mx-auto">Everything you need for seamless and private account verification at scale.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-code-slash"></i></div>
                        <h4 class="fw-bold mb-3">Powerful API</h4>
                        <p class="text-secondary small">Integrate our SMS verification into your automated workflows with our blazing fast REST API.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-lightning-fill"></i></div>
                        <h4 class="fw-bold mb-3">Instant Delivery</h4>
                        <p class="text-secondary small">OTP codes are relayed to your dashboard in less than 10 seconds. No waiting, no frustration.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-shield-lock"></i></div>
                        <h4 class="fw-bold mb-3">Non-VoIP Numbers</h4>
                        <p class="text-secondary small">We use real SIM-card based numbers to ensure 100% acceptance on sensitive platforms like Google and Tinder.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-globe"></i></div>
                        <h4 class="fw-bold mb-3">Multi-Country</h4>
                        <p class="text-secondary small">Access numbers from 150+ countries. Your solution for global account creation and testing.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-speedometer2"></i></div>
                        <h4 class="fw-bold mb-3">99.9% Success Rate</h4>
                        <p class="text-secondary small">Our smart routing algorithms ensure your verification always goes through on the first try.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="modern-feature-card">
                        <div class="feature-glow"></div>
                        <div class="feature-icon-wrapper"><i class="bi bi-currency-dollar"></i></div>
                        <h4 class="fw-bold mb-3">Competitive Pricing</h4>
                        <p class="text-secondary small">Prices start at just $0.05. No hidden fees, no price fluctuations during peak demand.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Preview Section -->
    <section id="about" class="py-5 bg-dark bg-opacity-25 border-top border-bottom border-secondary border-opacity-10">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5" data-aos="fade-right">
                    <h2 class="fw-bold fs-1 mb-4">Use our service <br><span class="text-primary">Anytime, Anywhere</span></h2>
                    <p class="text-secondary mb-5">
                        Need an SMS verification on the go? We offer a seamless experience across desktop and mobile. Access your dashboard via our native apps or any browser.
                    </p>
                    <div class="d-flex gap-3 mb-4">
                        <a href="#"><img src="https://www.smspool.net/assets/index/img/googleplay.svg" height="50"></a>
                        <a href="#"><img src="https://www.smspool.net/assets/index/img/appstore.svg" height="50"></a>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-primary fw-bold">
                        <i class="bi bi-arrow-right"></i> <span>Or register on our website directly</span>
                    </div>
                </div>
                <div class="col-lg-7 text-center mt-5 mt-lg-0" data-aos="zoom-in">
                    <img src="https://www.smspool.net/assets/index/img/laptop.svg" class="img-fluid" alt="Laptop Preview">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="reviews" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold fs-1 mb-3">What Our Users Say</h2>
                <div class="text-warning fs-3 mb-2">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p class="text-secondary">Rated 5/5 by over 50,000 satisfied customers.</p>
            </div>
            <div class="testimonial-grid">
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                    <p class="small text-secondary mb-4 italic">"Faspool is probably the best one out there, fast support, high uptime and extremely lowkey. I've used a lot of providers but this is the winner."</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary" style="width: 40px; height: 40px"></div>
                        <div class="small"><div class="fw-bold text-white">Duck</div><div class="text-muted">Verified User</div></div>
                    </div>
                </div>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                    <p class="small text-secondary mb-4 italic">"High quality and one things for sure Faspool got stock! Smooth process and owner solved my issue in 30 seconds. 10/10 site."</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px"></div>
                        <div class="small"><div class="fw-bold text-white">Shababan</div><div class="text-muted">Business Owner</div></div>
                    </div>
                </div>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                    <p class="small text-secondary mb-4 italic">"User-friendly service has truly been a game-changer for my business. Navigating the platform is a breeze both on desktop and mobile."</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success" style="width: 40px; height: 40px"></div>
                        <div class="small"><div class="fw-bold text-white">Pablo Romero</div><div class="text-muted">Digital Nomad</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Anonymous Payment -->
    <section class="py-5 mb-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-10">
                    <div class="p-5 rounded-5 bg-primary bg-opacity-10 border border-primary border-opacity-20 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col-md-7 position-relative" style="z-index: 2">
                                <h2 class="fw-bold fs-1 mb-4">Pay <span class="text-primary">Anonymously</span> with Cryptocurrency</h2>
                                <p class="text-secondary mb-5">We value your privacy. Deposit funds using Bitcoin, Ethereum, LTC, or USDT. Instant wallet crediting with zero tracking.</p>
                                <a href="{{ route('register') }}" class="btn btn-glow btn-lg">Deposit Now</a>
                            </div>
                            <div class="col-md-5 mt-5 mt-md-0 text-center">
                                <img src="https://www.smspool.net/assets/index/img/bitcoin.svg" alt="Bitcoin" class="img-fluid" style="max-height: 250px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-premium">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center mb-4" href="#">
                        <img src="https://www.smspool.net/assets/img/logo.png" alt="Logo" height="32" class="me-2">
                        <span class="fw-bold fs-4 text-white font-heading">FASPOOL</span>
                    </a>
                    <p class="text-secondary small">Empowering privacy through secure and instant virtual SMS verifications. Providing real non-VoIP numbers for all your online verification needs since 2024.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-discord"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="footer-title">Navigation</h6>
                    <a href="#" class="footer-link">Home</a>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#pricing" class="footer-link">Prices</a>
                    <a href="#reviews" class="footer-link">Reviews</a>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="footer-title">Resources</h6>
                    <a href="#" class="footer-link">API Documentation</a>
                    <a href="#" class="footer-link">Long-term Rentals</a>
                    <a href="#" class="footer-link">Free Verification</a>
                    <a href="#" class="footer-link">Carrier Lookup</a>
                </div>
                <div class="col-lg-3">
                    <h6 class="footer-title">Contact Us</h6>
                    <a href="mailto:support@faspool.com" class="footer-link">support@faspool.com</a>
                    <a href="#" class="footer-link">Support Tickets</a>
                    <a href="#" class="footer-link">Telegram Bot</a>
                </div>
            </div>
            <hr class="border-secondary border-opacity-10 mt-5 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-secondary small">
                <p class="mb-0">&copy; {{ date('Y') }} Faspool.net. All rights reserved.</p>
                <div class="d-flex gap-4">
                    <a href="#" class="text-secondary text-decoration-none hover-white">Privacy Policy</a>
                    <a href="#" class="text-secondary text-decoration-none hover-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) $('.navbar').addClass('scrolled');
            else $('.navbar').removeClass('scrolled');
        });
    </script>
</body>
</html>
