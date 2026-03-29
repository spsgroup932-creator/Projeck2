<div class="user-panel mx-3 mb-4 p-2 d-flex align-items-center rounded-3" style="background: var(--border-soft);">
    <div class="bg-primary text-white me-3 d-flex align-items-center justify-content-center fw-bold rounded-circle shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
        {{ substr(Auth::user()->name, 0, 1) }}
    </div>
    <div class="info text-truncate flex-grow-1">
        <div class="fw-bold small text-truncate" style="color: var(--text-main);" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
        <div class="text-muted text-uppercase" style="font-size: 0.6rem; font-weight: 700; letter-spacing: 0.5px;">{{ Auth::user()->role }}</div>
    </div>
    <a href="{{ route('profile') }}" class="btn btn-sm btn-link p-1 text-muted hover-primary transition">
        <i class="bi bi-gear-fill"></i>
    </a>
</div>

<div class="px-3 mb-2 small text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Main Navigation</div>

@foreach(config('menus', []) as $key => $menu)
    @if(auth()->user()->canAccessMenu($key))
        @if(isset($menu['is_dropdown']) && $menu['is_dropdown'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                    <div class="nav-icon-wrapper">
                        <i class="{{ $menu['icon'] }}"></i>
                    </div>
                    <span>{{ $menu['label'] }}</span>
                    <i class="bi bi-chevron-down ms-auto dropdown-chevron"></i>
                </a>
                <div class="collapse {{ collect($menu['sub'])->pluck('route')->contains(request()->route()->getName()) ? 'show' : '' }}" id="collapse{{ $key }}">
                    <ul class="nav flex-column ms-4 mt-1 gap-1">
                        @foreach($menu['sub'] as $subKey => $subMenu)
                            <li class="nav-item">
                                <a href="{{ route($subMenu['route'], $subMenu['params'] ?? []) }}" class="nav-link py-1 {{ request()->routeIs($subMenu['route'].'*') && (isset($subMenu['params']) ? request('method') == $subMenu['params']['method'] : true) ? 'active' : '' }}" style="font-size: 0.85rem;">
                                    <i class="{{ $subMenu['icon'] }} me-2"></i>
                                    <span>{{ $subMenu['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route($menu['route']) }}" class="nav-link {{ request()->routeIs($menu['route'].'*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <i class="{{ $menu['icon'] }}"></i>
                    </div>
                    <span>{{ $menu['label'] }}</span>
                </a>
            </li>
        @endif
    @endif
@endforeach

<style>
    .nav-icon-wrapper {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--border-soft);
        transition: var(--transition);
    }
    .nav-link.active .nav-icon-wrapper {
        background: rgba(255, 255, 255, 0.2);
    }
    .nav-link:hover .nav-icon-wrapper {
        background: var(--border-soft);
        opacity: 0.8;
    }
    [data-theme="light"] .nav-link.active .nav-icon-wrapper {
        background: rgba(0, 0, 0, 0.1);
    }
</style>

<hr class="mx-3 border-secondary opacity-10">

<li class="nav-item px-3 mb-4">
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i>
        <span>Overview Dashboard</span>
    </a>
</li>
