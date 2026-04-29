<header class="navbar navbar-expand sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-icon btn-sm bg-secondary bg-opacity-10 text-white rounded-circle border-0 me-3">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    
                    <div class="d-none d-md-block">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item small text-muted">Home</li>
                                <li class="breadcrumb-item active small text-white" aria-current="page">@yield('title', 'Dashboard')</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                        <!-- Language -->
                        <div class="dropdown d-none d-sm-block">
                            <button class="btn btn-link text-white text-decoration-none dropdown-toggle small d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <img src="https://flagcdn.com/w20/gb.png" width="18" alt="EN">
                                <span class="d-none d-lg-inline">English</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary border-opacity-10">
                                <li><a class="dropdown-item small" href="#">Russian</a></li>
                                <li><a class="dropdown-item small" href="#">Chinese</a></li>
                            </ul>
                        </div>

                        <!-- Wallet -->
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-20 px-3 py-1 rounded-pill d-flex align-items-center gap-2">
                            <span class="text-success small fw-bold d-none d-sm-inline">Wallet:</span>
                            <span class="text-white fw-bold small">$100.00</span>
                        </div>

                        <!-- Action Icons -->
                        <div class="d-flex align-items-center gap-1 ps-2 ps-md-3 border-start border-secondary border-opacity-20">
                            <button class="btn btn-icon btn-sm bg-secondary bg-opacity-10 text-white rounded-circle border-0 position-relative" data-bs-toggle="offcanvas" data-bs-target="#notificationsOffcanvas">
                                <i class="bi bi-bell"></i>
                                <span id="notification_badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem; display: none;">0</span>
                            </button>
                            <button class="btn btn-icon btn-sm bg-secondary bg-opacity-10 text-white rounded-circle border-0" data-bs-toggle="offcanvas" data-bs-target="#preferencesOffcanvas">
                                <i class="bi bi-gear"></i>
                            </button>
                        </div>

                        <!-- Profile -->
                        <div class="dropdown ms-1 ms-md-2">
                            <button class="btn p-0 border-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D6EFD&color=fff" class="rounded-circle border border-white border-opacity-10" width="32">
                                <div class="text-start d-none d-xl-block">
                                    <div class="text-white fw-bold small lh-1">{{ auth()->user()->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->roles->first()->name ?? 'Member') }}</div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary border-opacity-10 shadow-lg mt-2">
                                <li><a class="dropdown-item small py-2" href="#"><i class="bi bi-person me-2"></i> Profile Settings</a></li>
                                <li><a class="dropdown-item small py-2" href="#"><i class="bi bi-shield-lock me-2"></i> Security</a></li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-10"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item small py-2 text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>