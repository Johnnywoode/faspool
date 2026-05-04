<ul class="nav flex-column gap-1 ps-3 mt-1">
    @if(isset($submenuItems))
        @foreach($submenuItems as $subItem)
            <li class="nav-item w-100">
                @if(isset($subItem['submenu']) && !empty($subItem['submenu']))
                    {{-- Nested submenu item with its own children --}}
                    @php
                        $isSubActive = request()->routeIs($subItem['slug'] ?? 'none') || 
                                      (isset($subItem['submenu']) && collect($subItem['submenu'])->pluck('slug')->contains(function($slug) { 
                                          return request()->routeIs($slug); 
                                      }));
                    @endphp
                    
                    <a class="nav-link d-flex align-items-center gap-2 py-2 px-2 rounded-3 small text-secondary {{ $isSubActive ? 'active text-primary fw-bold bg-primary bg-opacity-10' : '' }}" 
                       data-bs-toggle="collapse" 
                       href="#submenu-{{ Str::slug($subItem['name']) }}" 
                       role="button" 
                       aria-expanded="{{ $isSubActive ? 'true' : 'false' }}">
                        @if(isset($subItem['icon']))
                            <i class="ph ph-{{ $subItem['icon'] }} fs-6"></i>
                        @endif
                        <span>{{ $subItem['name'] }}</span>
                        <i class="bi bi-chevron-down ms-auto small transition-all {{ $isSubActive ? '' : 'rotate-n90' }}"></i>
                    </a>
                    
                    <div class="collapse {{ $isSubActive ? 'show' : '' }}" id="submenu-{{ Str::slug($subItem['name']) }}">
                        @include('partials.submenu', ['submenuItems' => $subItem['submenu']])
                    </div>
                @else
                    {{-- Regular submenu item --}}
                    <a class="nav-link py-2 px-2 rounded-3 small text-secondary {{ request()->routeIs($subItem['slug'] ?? 'none') ? 'active text-primary fw-bold bg-primary bg-opacity-10' : '' }}" 
                       href="{{ $subItem['url'] ?? 'javascript:void(0)' }}">
                        @if(isset($subItem['icon']))
                            <i class="ph ph-{{ $subItem['icon'] }} fs-6 me-2"></i>
                        @endif
                        <span>{{ $subItem['name'] }}</span>
                    </a>
                @endif
            </li>
        @endforeach
    @endif
</ul>