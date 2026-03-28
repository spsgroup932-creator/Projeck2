<nav class="navbar navbar-expand-lg main-header px-4">
    <div class="container-fluid p-0">
        <!-- Sidebar Toggle -->
        <button type="button" id="sidebarCollapse" class="btn btn-light bg-white shadow-sm border text-secondary me-3 d-flex align-items-center justify-content-center p-2 rounded">
            <i class="bi bi-list fs-5 mb-0" style="line-height:1;"></i>
        </button>
        
        <!-- Optional Current Date or Context Header -->
        <div class="me-auto d-none d-md-flex align-items-center">
             <span class="text-muted fw-medium" style="font-size: 0.9rem;">
                 <i class="bi bi-calendar3 me-1"></i> {{ date('d F Y') }}
             </span>
        </div>

        <!-- Top Right Navbar -->
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                    <div class="fw-semibold d-none d-sm-block text-end" style="line-height:1.2;">
                        <div>{{ Auth::user()->name }}</div>
                        <small class="text-primary" style="font-size: 0.70rem; font-weight: bold; text-transform: uppercase;">{{ Auth::user()->role }}</small>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm ms-2 bg-gradient" style="width: 38px; height: 38px; font-size: 1rem;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded" aria-labelledby="navbarDropdown" style="min-width: 200px;">
                    <li class="px-3 py-2 border-bottom d-sm-none">
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                        <div class="text-muted small text-uppercase fw-bold text-primary">{{ Auth::user()->role }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 mt-1" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2 text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2 mb-1 fw-medium">
                                <i class="bi bi-box-arrow-right me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
