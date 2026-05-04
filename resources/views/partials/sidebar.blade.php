@inject('menuService', 'App\Services\MenuService')

@php
    $menuItems = $menuService->getMenuItems();
@endphp

<div class="sidebar-header border-bottom border-secondary border-opacity-10 py-4 px-4">
    <div class="d-flex align-items-center">
        <img src="https://www.smspool.net/assets/img/logo.png" alt="Logo" height="30" class="me-2">
        <span class="fw-bold fs-5 text-white">{{  config('app.name') }}</span>
    </div>
</div>

<div class="sidebar-content py-3">
    <ul class="nav flex-column gap-1 px-3">
        @if(isset($isImpersonating) && $isImpersonating)
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" href="{{ route('impersonate.stop') }}">
                    <i class="ph ph-sign-out fs-5"></i>
                    <span>Stop Impersonation</span>
                </a>
            </li>
        @endif

        @foreach($menuItems as $item)
            @if(isset($item['is_label']) && $item['is_label'])
                <li class="nav-label mt-3 mb-1 small text-uppercase fw-bold text-secondary px-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                    {{ $item['name'] }}
                </li>
            @elseif(isset($item['submenu']))
                <li class="nav-item">
                    @php
                        $isActive = request()->routeIs($item['slug'] . '*') || (isset($item['submenu']) && collect($item['submenu'])->pluck('slug')->contains(function($slug) { return request()->routeIs($slug); }));
                    @endphp
                    <a class="nav-link d-flex align-items-center gap-3 py-2 px-2 rounded-3 text-secondary {{ $isActive ? 'active bg-primary text-white' : '' }}" 
                       data-bs-toggle="collapse" href="#menu-{{ Str::slug($item['name']) }}" role="button" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                        <i class="ph ph-{{ $item['icon'] ?? 'app-window' }} fs-5"></i>
                        <span>{{ $item['name'] }}</span>
                        <i class="bi bi-chevron-down ms-auto small transition-all {{ $isActive ? '' : 'rotate-n90' }}"></i>
                    </a>
                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="menu-{{ Str::slug($item['name']) }}">
                        <ul class="nav flex-column ms-4 mt-1 gap-1">
                            @foreach($item['submenu'] as $sub)
                                <li class="nav-item">
                                    <a class="nav-link py-2 px-2 rounded-3 small text-secondary {{ request()->routeIs($sub['slug'] ?? 'none') ? 'active text-primary fw-bold' : '' }}" href="{{ $sub['url'] }}">
                                        {{ $sub['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 py-2 px-2 rounded-3 text-secondary {{ request()->routeIs($item['slug'] ?? 'none') ? 'active bg-primary text-white' : '' }}" 
                       href="{{ $item['url'] }}">
                        <i class="ph ph-{{ $item['icon'] ?? 'app-window' }} fs-5"></i>
                        <span>{{ $item['name'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<style>
    #sidebar .nav-link {
        transition: 0.2s;
        font-weight: 500;
    }
    #sidebar .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.05);
        color: white !important;
    }
    #sidebar .nav-link.active {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .nav-label {
        user-select: none;
    }
    .rotate-n90 {
        transform: rotate(-90deg);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
