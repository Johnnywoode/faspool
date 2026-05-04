<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /*
         * Theme layer — override Bootstrap's own CSS custom properties.
         * Because Bootstrap components consume these vars internally, every
         * button, card, badge, input, etc. inherits the theme automatically.
         * Nothing below this block should hard-code a color value.
         */
        [data-bs-theme="dark"] {
            /* Core palette */
            --bs-body-bg:            #12151a;
            --bs-body-color:         #e2e8f0;
            --bs-body-font-family:   'Inter', system-ui, sans-serif;

            /* Surfaces — used by cards, modals, offcanvas, dropdowns */
            --bs-secondary-bg:       #0f1115;
            --bs-tertiary-bg:        #0a0b0e;

            /* Borders */
            --bs-border-color:        rgba(255, 255, 255, 0.08);
            --bs-border-color-translucent: rgba(255, 255, 255, 0.06);

            /* Muted text */
            --bs-secondary-color:    #94a3b8;
            --bs-tertiary-color:     #64748b;

            /* Navbar / topbar background (used via .bg-body) */
            --bs-navbar-bg:          rgba(5, 6, 8, 0.85);

            /* Sidebar dimensions (layout only, not colors) */
            --sidebar-width:         280px;
            --topbar-height:         70px;
        }

        /*
         * Layout skeleton — wiring, not colors.
         * All colors come from Bootstrap vars above.
         */
        body {
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--bs-tertiary-bg);
            border-right: 1px solid var(--bs-border-color);
            position: fixed;
            height: 100vh;
            z-index: 1050;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        /* Sidebar menu improvements */
        #sidebar .nav-item {
            width: 100%;
        }

        #sidebar .nav-link {
            white-space: nowrap;
            width: 100%;
        }

        #sidebar .nav-link span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Ensure collapse icons don't wrap */
        #sidebar .nav-link i:last-child {
            flex-shrink: 0;
        }

        /* Better nested menu indentation */
        #sidebar .collapse .nav {
            padding-left: 0.5rem;
        }

        /* Second level nested menus */
        #sidebar .collapse .collapse .nav {
            padding-left: 1rem;
        }

        /* Make submenu items slightly smaller */
        #sidebar .collapse .nav-link {
            font-size: 0.8125rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        /* Active submenu styling */
        #sidebar .collapse .nav-link.active {
            background-color: rgba(13, 110, 253, 0.1) !important;
            color: #0d6efd !important;
            font-weight: 600;
        }

        /* Consistent icon sizing */
        #sidebar .nav-link i {
            width: 1.25rem;
            text-align: center;
        }

        #content {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s, width 0.3s;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #sidebar.active + #content {
            margin-left: 0;
            width: 100%;
        }

        .navbar {
            /* height: var(--topbar-height); */
            background-color: var(--bs-navbar-bg) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bs-border-color);
        }

        main {
            flex-grow: 1;
            padding: 2.5rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar         { width: 5px; }
        ::-webkit-scrollbar-track   { background: transparent; }
        ::-webkit-scrollbar-thumb   { background: var(--bs-border-color); border-radius: 10px; }

        /* Breadcrumb separator */
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--bs-secondary-color);
            content: "/";
        }

        @media (max-width: 992px) {
            #sidebar          { margin-left: calc(-1 * var(--sidebar-width)); }
            #sidebar.active   { margin-left: 0; }
            #content          { margin-left: 0 !important; width: 100% !important; }
        }
    </style>
    @stack('css')
</head>
<body>
    <div id="wrapper">
        <nav id="sidebar">
            @include('partials.sidebar')
        </nav>

        <div id="content">
            @include('partials.navbar')
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.offcanvas')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Select2 Dark Theme Overrides */
        .select2-container--default .select2-selection--single {
            background-color: var(--bs-secondary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            height: 45px !important;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--bs-body-color) !important;
            padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px !important;
        }
        .select2-dropdown {
            background-color: var(--bs-secondary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            color: var(--bs-body-color) !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--bs-primary) !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: var(--bs-tertiary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            color: var(--bs-body-color) !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Global Select2 Initializer
            $('select').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });
            
            AOS.init({ duration: 800, once: true });

        function showToast(type, message, title) {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: 'var(--bs-secondary-bg)',
                color: 'var(--bs-body-color)',
                didOpen: toast => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            }).fire({ icon: type, title: title || message, text: title ? message : '' });
        }

        function getTooltip(el) {
            const id = el.data('tippy-id');
            const instance = tippy(el[0], {
                content: '<i class="bi bi-arrow-repeat bi-spin"></i> Loading...',
                trigger: 'manual',
                interactive: true,
                allowHTML: true,
                animation: 'fade',
                theme: 'light-border',
            });
            instance.show();

            fetch(`{{ url('tooltip') }}/${id}`)
                .then(r => r.json())
                .then(data => {
                    const content = data.status === 'success' ? data.data.content : data.message;
                    instance.setContent(data.status === 'success' && data.data.link
                        ? `${content}${data.data.link}`
                        : content
                    );
                })
                .catch(() => instance.setContent('Error loading content'));
        }

        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

            $('.tippy').on('click', function () {
                getTooltip($(this));
            });
        });
    </script>

    @if (Session::has('message'))
    <script>
        const _labels = { info: 'Information!', warning: 'Warning!', success: 'Success!', error: 'Oops!' };
        const _type    = "{{ Session::get('status', 'success') }}";
        const _msg     = "{!! Session::get('message') !!}";
        showToast(_type, _msg, _labels[_type]);
    </script>
    @endif

    @if (Session::has('success'))
    <script>showToast('success', "{!! Session::get('success') !!}", 'Success!');</script>
    @endif

    @if (Session::has('error'))
    <script>showToast('error', "{!! Session::get('error') !!}", 'Error!');</script>
    @endif

    @stack('scripts')
</body>
</html>