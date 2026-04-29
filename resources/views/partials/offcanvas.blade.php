

<style>
    /* Only dynamic state styles that can't be represented by static Bootstrap classes */
    .transition {
        transition: all 0.2s ease;
    }
    
    .hover-bg-light:hover {
        background-color: rgba(var(--bs-secondary-rgb), 0.05);
    }
    
    .hover-bg-danger-transparent:hover {
        background-color: rgba(var(--bs-danger-rgb), 0.05);
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    /* Theme radio button active state */
    .theme-radio.active {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        box-shadow: inset 0 0 0 2px var(--bs-body-bg);
    }
    
    /* Checkbox checked state */
    .pref-checkbox.checked {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        position: relative;
    }
    
    .pref-checkbox.checked::after {
        content: '✓';
        font-size: 12px;
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    /* Custom scrollbar (optional enhancement) */
    .offcanvas-body {
        scrollbar-width: thin;
        scrollbar-color: rgba(var(--bs-secondary-rgb), 0.2) transparent;
    }
    
    .offcanvas-body::-webkit-scrollbar {
        width: 4px;
    }
    
    .offcanvas-body::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .offcanvas-body::-webkit-scrollbar-thumb {
        background-color: rgba(var(--bs-secondary-rgb), 0.2);
        border-radius: 10px;
    }
    
    .offcanvas-body::-webkit-scrollbar-thumb:hover {
        background-color: rgba(var(--bs-secondary-rgb), 0.3);
    }
    
    /* Notification items */
    .notification-item {
        padding: 1rem;
        border-bottom: 1px solid rgba(var(--bs-secondary-rgb), 0.08);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .notification-item:hover {
        background-color: rgba(var(--bs-secondary-rgb), 0.03);
    }
    
    /* Offcanvas width classes */
    .offcanvas-sm { width: 300px !important; }
    .offcanvas-md { width: 500px !important; }
    .offcanvas-lg { width: 800px !important; }
    
    @media (max-width: 576px) {
        .offcanvas-sm, .offcanvas-md, .offcanvas-lg { width: 100% !important; }
    }
</style>


<!-- Preferences Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-md border-start-0" tabindex="-1" id="preferencesOffcanvas" aria-labelledby="preferencesOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-semibold" id="preferencesOffcanvasLabel">
            Preferences
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <!-- Your account section -->
        <div class="px-3 pt-3">
            <div class="text-secondary text-uppercase small fw-semibold mb-2">Your account</div>
            
            <a href="#" class="text-decoration-none d-block hover-bg-light rounded-3 transition">
                <div class="d-flex align-items-center justify-content-between py-3 px-2">
                    <div>
                        <div class="fw-medium text-body">Order history</div>
                        <div class="small text-secondary">View your previous orders</div>
                    </div>
                    <i class="bi bi-chevron-right text-secondary opacity-50"></i>
                </div>
            </a>
            
            <a href="#" class="text-decoration-none d-block hover-bg-light rounded-3 transition">
                <div class="d-flex align-items-center justify-content-between py-3 px-2">
                    <div>
                        <div class="fw-medium text-body">Account settings</div>
                        <div class="small text-secondary">View your account settings</div>
                    </div>
                    <i class="bi bi-chevron-right text-secondary opacity-50"></i>
                </div>
            </a>
            
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-decoration-none d-block hover-bg-danger-transparent rounded-3 transition">
                <div class="d-flex align-items-center justify-content-between py-3 px-2">
                    <div>
                        <div class="fw-medium text-danger">Logout</div>
                        <div class="small text-danger opacity-75">Log out of your account</div>
                    </div>
                    <i class="bi bi-chevron-right text-danger opacity-50"></i>
                </div>
            </a>
        </div>

        <hr class="my-2 opacity-25">

        <!-- Color mode section -->
        <div class="px-3">
            <div class="text-secondary text-uppercase small fw-semibold mb-2 mt-2">Color mode</div>
            
            <div class="theme-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-theme="light">
                <div>
                    <div class="fw-medium text-body">Light theme</div>
                    <div class="small text-secondary">Set light theme or reset to default</div>
                </div>
                <div class="rounded-circle border border-secondary border-opacity-25 theme-radio" style="width: 18px; height: 18px;"></div>
            </div>
            
            <div class="theme-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-theme="dark">
                <div>
                    <div class="fw-medium text-body">Dark theme</div>
                    <div class="small text-secondary">Switch to dark theme</div>
                </div>
                <div class="rounded-circle border border-secondary border-opacity-25 theme-radio" style="width: 18px; height: 18px;"></div>
            </div>
            
            <div class="theme-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-theme="auto">
                <div>
                    <div class="fw-medium text-body">Auto theme</div>
                    <div class="small text-secondary">Set theme based on system mode</div>
                </div>
                <div class="rounded-circle border border-secondary border-opacity-25 theme-radio" style="width: 18px; height: 18px;"></div>
            </div>
        </div>

        <hr class="my-2 opacity-25">

        <!-- Audio mode section -->
        <div class="px-3">
            <div class="text-secondary text-uppercase small fw-semibold mb-2 mt-2">Audio mode</div>
            
            <div class="pref-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-pref="audio">
                <div>
                    <div class="fw-medium text-body">Audio</div>
                    <div class="small text-secondary">Toggle the audio on and off</div>
                </div>
                <div class="rounded border border-secondary border-opacity-25 pref-checkbox" style="width: 20px; height: 20px;"></div>
            </div>
        </div>

        <hr class="my-2 opacity-25">

        <!-- Prompts section -->
        <div class="px-3 pb-3">
            <div class="text-secondary text-uppercase small fw-semibold mb-2 mt-2">Prompts</div>
            
            <div class="pref-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-pref="resend">
                <div>
                    <div class="fw-medium text-body">Resend</div>
                    <div class="small text-secondary">Disable resend prompt</div>
                </div>
                <div class="rounded border border-secondary border-opacity-25 pref-checkbox" style="width: 20px; height: 20px;"></div>
            </div>
            
            <div class="pref-option d-flex align-items-center justify-content-between py-3 px-2 rounded-3 transition cursor-pointer hover-bg-light" data-pref="refund">
                <div>
                    <div class="fw-medium text-body">Refund</div>
                    <div class="small text-secondary">Disable refund prompt</div>
                </div>
                <div class="rounded border border-secondary border-opacity-25 pref-checkbox" style="width: 20px; height: 20px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-sm border-start-0" tabindex="-1" id="notificationsOffcanvas" aria-labelledby="notificationsLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-semibold" id="notificationsLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-medium small">New notifications</span>
            <a href="#" class="text-primary text-decoration-none small" onclick="markAllAsRead(); return false;">Mark all as read</a>
        </div>
        
        <div id="active_notifications_list">
            <div class="text-center py-5">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary opacity-50"></i>
                <div class="text-secondary small">You have no new notifications.</div>
            </div>
        </div>
    </div>
</div>

<script>
// Theme Switcher Logic
    (function() {
        // Get saved theme or default
        const savedTheme = localStorage.getItem('theme') || 'dark';
        
        // Apply theme function
        function applyTheme(theme) {
            if (theme === 'auto') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute('data-bs-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
            localStorage.setItem('theme', theme);
            updateThemeUI(theme);
        }
        
        // Update UI radios
        function updateThemeUI(theme) {
            document.querySelectorAll('.theme-radio').forEach(radio => {
                radio.classList.remove('active');
            });
            
            const themeMap = {
                'light': 0,
                'dark': 1,
                'auto': 2
            };
            
            const index = themeMap[theme];
            if (index !== undefined) {
                const radios = document.querySelectorAll('.theme-radio');
                if (radios[index]) radios[index].classList.add('active');
            }
        }
        
        // Theme option click handlers
        document.querySelectorAll('.theme-option').forEach((item, index) => {
            item.addEventListener('click', function() {
                const theme = this.getAttribute('data-theme');
                applyTheme(theme);
            });
        });
        
        // Initial theme application
        applyTheme(savedTheme);
        
        // Listen for system theme changes when in auto mode
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (localStorage.getItem('theme') === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', e.matches ? 'dark' : 'light');
            }
        });
    })();

    // Preferences Checkbox Logic
    (function() {
        // Load saved preferences
        function updateCheckboxUI(prefId, isChecked) {
            const item = document.querySelector(`[data-pref="${prefId}"]`);
            if (item) {
                const checkbox = item.querySelector('.pref-checkbox');
                if (checkbox) {
                    if (isChecked) {
                        checkbox.classList.add('checked');
                    } else {
                        checkbox.classList.remove('checked');
                    }
                }
        }
    }
    
    function togglePreference(prefId) {
        const currentValue = localStorage.getItem('pref_' + prefId) === 'true';
        const newValue = !currentValue;
        localStorage.setItem('pref_' + prefId, newValue);
        updateCheckboxUI(prefId, newValue);
        
        // Show toast or feedback (optional)
        const prefNames = {
            audio: 'Audio',
            resend: 'Resend prompt',
            refund: 'Refund prompt'
        };
        console.log(`${prefNames[prefId]} ${newValue ? 'disabled' : 'enabled'}`);
    }
    
    // Initialize checkboxes
    ['audio', 'resend', 'refund'].forEach(prefId => {
        const savedValue = localStorage.getItem('pref_' + prefId) === 'true';
        updateCheckboxUI(prefId, savedValue);
        
        const option = document.querySelector(`[data-pref="${prefId}"]`);
        if (option) {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                togglePreference(prefId);
            });
        }
    });
})();

// Notification Logic
$(document).ready(function() {
    const apikey = "tTWeml7xofI7o6kdJUBVnFeKsvVbWvYd"; // Should come from config
    const endpoint_url = "https://api.smspool.net";
    
    let notificationsList = [];

    function formatTimestamp(timestamp) {
        if (!timestamp) return 'Just now';
        try {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            
            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)} min ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
            return date.toLocaleDateString();
        } catch(e) {
            return 'Just now';
        }
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function renderNotifications() {
        const $container = $("#active_notifications_list");
        $container.empty();
        
        if (notificationsList.length > 0) {
            const unreadCount = notificationsList.filter(n => !n.read).length;
            if (unreadCount > 0) {
                const badge = $('#notification_badge');
                if (badge.length) {
                    badge.text(unreadCount).show();
                }
            }
            
            notificationsList.forEach(notification => {
                const notificationHtml = `
                    <div class="notification-item" id="notif-${notification.id}">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="fw-semibold small">${escapeHtml(notification.title)}</div>
                            <div class="text-secondary small" style="font-size: 10px;">${formatTimestamp(notification.timestamp)}</div>
                        </div>
                        <div class="small text-secondary mb-2">${escapeHtml(notification.text)}</div>
                        ${!notification.read ? `<a href="#" class="text-primary text-decoration-none small" onclick="markAsRead('${notification.id}'); return false;">Mark as read</a>` : ''}
                    </div>
                `;
                $container.append(notificationHtml);
            });
        } else {
            const badge = $('#notification_badge');
            if (badge.length) badge.hide();
            
            $container.html(`
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <div class="text-secondary small">You have no new notifications.</div>
                </div>
            `);
        }
    }
    
    function checkNotifications() {
        if (!apikey || apikey === "tTWeml7xofI7o6kdJUBVnFeKsvVbWvYd") return;
        
        $.ajax({
            url: endpoint_url + "/notifications/retrieve",
            type: "POST",
            data: { key: apikey },
            success: function(data) {
                if (data && Array.isArray(data)) {
                    notificationsList = data.map(notif => ({
                        id: notif.notification_id,
                        title: notif.title || 'Notification',
                        text: notif.text || '',
                        timestamp: notif.timestamp,
                        read: false
                    }));
                    renderNotifications();
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch notifications:', error);
            }
        });
    }
    
    // Expose functions globally
    window.markAsRead = function(id) {
        const notification = notificationsList.find(n => n.id == id);
        if (notification) {
            notification.read = true;
            renderNotifications();
            
            // API call to mark as read would go here
            if (apikey && apikey !== "tTWeml7xofI7o6kdJUBVnFeKsvVbWvYd") {
                $.ajax({
                    url: endpoint_url + "/notifications/mark-read",
                    type: "POST",
                    data: { key: apikey, notification_id: id },
                    error: function() {
                        console.error('Failed to mark notification as read');
                    }
                });
            }
        }
    };
    
    window.markAllAsRead = function() {
        notificationsList.forEach(n => n.read = true);
        renderNotifications();
        
        // API call to mark all as read would go here
        if (apikey && apikey !== "tTWeml7xofI7o6kdJUBVnFeKsvVbWvYd" && notificationsList.length > 0) {
            $.ajax({
                url: endpoint_url + "/notifications/mark-all-read",
                type: "POST",
                data: { key: apikey },
                error: function() {
                    console.error('Failed to mark all notifications as read');
                }
            });
        }
    };
    
    // Initial load
    checkNotifications();
    if (apikey && apikey !== "tTWeml7xofI7o6kdJUBVnFeKsvVbWvYd") {
        setInterval(checkNotifications, 30000);
    }
});
</script>

<!-- Logout Form (if not already present elsewhere) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>