
<style>
    /* Premium Aesthetics for Offcanvas */
    .offcanvas {
        background-color: #1a1d24 !important; /* Dark surface match */
        color: #e2e8f0;
        border-left: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .offcanvas-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1.25rem 1.5rem;
    }
    
    .offcanvas-title {
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .offcanvas-body {
        padding: 1.5rem;
    }

    /* List items in Preferences */
    .pref-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    
    .pref-item:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(13, 110, 253, 0.3);
        color: inherit;
    }

    .pref-item.active {
        border-color: #0d6efd;
        background: rgba(13, 110, 253, 0.05);
    }

    .pref-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        font-size: 1.2rem;
        color: #94a3b8;
    }
    
    .active .pref-icon {
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.1);
    }

    .pref-content {
        flex: 1;
        margin-left: 1rem;
    }

    .pref-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.1rem;
        display: block;
    }

    .pref-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        display: block;
    }

    /* Radio/Checkbox custom style */
    .pref-radio {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .active .pref-radio {
        border-color: #0d6efd;
    }

    .active .pref-radio::after {
        content: '';
        width: 10px;
        height: 10px;
        background: #0d6efd;
        border-radius: 50%;
    }

    .pref-check {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .checked .pref-check {
        border-color: #0d6efd;
        background: #0d6efd;
    }

    .checked .pref-check::after {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    /* Section Labels */
    .pref-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 0.75rem;
        margin-top: 1.5rem;
        letter-spacing: 0.05em;
    }
    
    .pref-label:first-child {
        margin-top: 0;
    }

    /* Notification specific */
    .notif-header-sub {
        font-size: 0.85rem;
        color: #94a3b8;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 2rem;
    }
    
    .mark-all {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
    }

    .notif-empty {
        text-align: center;
        margin-top: 4rem;
    }
    
    .notif-empty-icon {
        font-size: 3rem;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    /* Offcanvas width */
    .offcanvas-md {
        width: 420px !important;
    }
</style>

<!-- Preferences Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-md" tabindex="-1" id="preferencesOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Preferences</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="pref-label">Your account</div>
        
        <a href="{{ route('orders.index') }}" class="pref-item">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-shopping-cart"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Order history</span>
                    <span class="pref-subtitle">View your previous orders</span>
                </div>
                <i class="bi bi-chevron-right text-secondary opacity-50"></i>
            </div>
        </a>

        <a href="#" class="pref-item">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-gear"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Account settings</span>
                    <span class="pref-subtitle">View your account settings</span>
                </div>
                <i class="bi bi-chevron-right text-secondary opacity-50"></i>
            </div>
        </a>

        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="pref-item border-danger-subtle">
            <div class="d-flex align-items-center">
                <div class="pref-icon text-danger bg-danger bg-opacity-10"><i class="ph ph-sign-out"></i></div>
                <div class="pref-content">
                    <span class="pref-title text-danger">Logout</span>
                    <span class="pref-subtitle text-danger text-opacity-50">Log out of your account</span>
                </div>
                <i class="bi bi-chevron-right text-danger opacity-50"></i>
            </div>
        </a>

        <div class="pref-label">Color mode</div>

        <div class="pref-item theme-option" data-theme="light">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-sun"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Light theme</span>
                    <span class="pref-subtitle">Set light theme or reset to default</span>
                </div>
                <div class="pref-radio"></div>
            </div>
        </div>

        <div class="pref-item theme-option" data-theme="dark">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-moon"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Dark theme</span>
                    <span class="pref-subtitle">Switch to dark theme</span>
                </div>
                <div class="pref-radio"></div>
            </div>
        </div>

        <div class="pref-item theme-option" data-theme="auto">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-monitor"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Auto theme</span>
                    <span class="pref-subtitle">Set theme based on system mode</span>
                </div>
                <div class="pref-radio"></div>
            </div>
        </div>

        <div class="pref-label">Audio mode</div>

        <div class="pref-item pref-toggle" data-pref="audio">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-speaker-high"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Audio</span>
                    <span class="pref-subtitle">Toggle the audio on and off</span>
                </div>
                <div class="pref-check"></div>
            </div>
        </div>

        <div class="pref-label">Prompts</div>

        <div class="pref-item pref-toggle" data-pref="resend">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-arrows-counter-clockwise"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Resend</span>
                    <span class="pref-subtitle">Disable resend prompt</span>
                </div>
                <div class="pref-check"></div>
            </div>
        </div>

        <div class="pref-item pref-toggle" data-pref="refund">
            <div class="d-flex align-items-center">
                <div class="pref-icon"><i class="ph ph-arrows-counter-clockwise"></i></div>
                <div class="pref-content">
                    <span class="pref-title">Refund</span>
                    <span class="pref-subtitle">Disable refund prompt</span>
                </div>
                <div class="pref-check"></div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-md" tabindex="-1" id="notificationsOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Notifications</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="notif-header-sub d-flex justify-content-between align-items-center">
            <span>New notifications</span>
            <a href="#" class="mark-all" onclick="markAllAsRead(); return false;">Mark all as read</a>
        </div>
        
        <div id="active_notifications_list">
            <div class="notif-empty">
                <div class="notif-empty-icon"><i class="ph ph-bell-slash"></i></div>
                <div class="text-secondary small">You have no new notifications.</div>
            </div>
        </div>
    </div>
</div>

<script>
    // Theme Switcher Logic
    (function() {
        const applyTheme = (theme) => {
            const isAuto = theme === 'auto';
            const effectiveTheme = isAuto 
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : theme;
                
            document.documentElement.setAttribute('data-bs-theme', effectiveTheme);
            localStorage.setItem('theme', theme);
            
            // Update UI
            document.querySelectorAll('.theme-option').forEach(el => {
                el.classList.toggle('active', el.dataset.theme === theme);
            });
        };

        document.querySelectorAll('.theme-option').forEach(el => {
            el.addEventListener('click', () => applyTheme(el.dataset.theme));
        });

        // Initial apply
        applyTheme(localStorage.getItem('theme') || 'dark');
    })();

    // Preferences Checkbox Logic
    (function() {
        const updateUI = (prefId) => {
            const isChecked = localStorage.getItem('pref_' + prefId) === 'true';
            const el = document.querySelector(`[data-pref="${prefId}"]`);
            if (el) el.classList.toggle('checked', isChecked);
        };

        document.querySelectorAll('.pref-toggle').forEach(el => {
            const prefId = el.dataset.pref;
            updateUI(prefId);
            el.addEventListener('click', (e) => {
                e.preventDefault();
                const current = localStorage.getItem('pref_' + prefId) === 'true';
                localStorage.setItem('pref_' + prefId, !current);
                updateUI(prefId);
            });
        });
    })();
    
    // Notification Logic (Mock for now, matching UI)
    window.markAllAsRead = function() {
        $("#active_notifications_list").html(`
            <div class="notif-empty">
                <div class="notif-empty-icon"><i class="ph ph-bell-slash"></i></div>
                <div class="text-secondary small">You have no new notifications.</div>
            </div>
        `);
        showToast('success', 'All notifications marked as read');
    };
</script>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>