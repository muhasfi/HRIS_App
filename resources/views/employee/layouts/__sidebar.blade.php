<nav class="sidebar" id="sidebar">

    {{-- ── LOGO ── --}}
    <div class="logo-wrap">
        <div class="logo-mark">Hr</div>
        <span class="logo-name">Karyawan</span>
        <span class="logo-ver">v2</span>
    </div>

    {{-- ── NAVIGASI UTAMA ── --}}
    <div class="nav-group">
        <div class="nav-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <rect x="1.5" y="2" width="13" height="12" rx="2"/>
                <path d="M5 2V5M11 2V5M1.5 7h13"/>
                <path d="M5 10h.5M8 10h.5M11 10h.5"/>
            </svg>
            Dashboard
        </a>

        {{-- Presence --}}
        <a href="{{ route('presences.index') }}"
           class="nav-item {{ request()->routeIs('presences.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <rect x="1.5" y="2" width="13" height="12" rx="2"/>
                <path d="M5 2V5M11 2V5M1.5 7h13"/>
                <path d="M5 10h.5M8 10h.5M11 10h.5"/>
            </svg>
            Presence
        </a>

        {{-- Tasks --}}
        <a href="{{ route('tasks.index') }}"
           class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <rect x="2" y="3" width="12" height="1.4" rx=".7"/>
                <rect x="2" y="7.3" width="12" height="1.4" rx=".7"/>
                <rect x="2" y="11.6" width="7" height="1.4" rx=".7"/>
                <circle cx="12.5" cy="12.3" r="2.2" stroke="currentColor" fill="none"/>
                <path d="M11.6 12.3l.6.6 1.3-1.2"/>
            </svg>
            Tasks
            @if(isset($taskBadge) && $taskBadge > 0)
                <span class="nav-badge">{{ $taskBadge }}</span>
            @endif
        </a>

        {{-- Payrolls --}}
        <a href="{{ route('payrolls.index') }}"
           class="nav-item {{ request()->routeIs('payrolls.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <rect x="1.5" y="3.5" width="13" height="9" rx="1.5"/>
                <path d="M5.5 3.5v1M10.5 3.5v1M4 8h2M10 8h2M6 10.5h4"/>
            </svg>
            Payrolls
        </a>

        {{-- Leave Request --}}
        <a href="{{ route('leave-requests.index') }}"
           class="nav-item {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <path d="M8 1.5A5.5 5.5 0 018 12.5M8 1.5A5.5 5.5 0 018 12.5M1.5 8h13M2.5 4.5C4 5.5 6 6 8 6s4-.5 5.5-1.5M2.5 11.5C4 10.5 6 10 8 10s4 .5 5.5 1.5"/>
            </svg>
            Leave Request
        </a>

        {{-- Logout --}}
        <a href="#"
        class="nav-item"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

            <svg class="nav-icon" viewBox="0 0 16 16">
                <path d="M6 2.5h-2A1.5 1.5 0 002.5 4v8A1.5 1.5 0 004 13.5h2"/>
                <path d="M10 11l3-3-3-3"/>
                <path d="M13 8H6"/>
            </svg>

            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>{{-- /.nav-group --}}

    {{-- ── USER PROFILE (FOOTER SIDEBAR) ── --}}
    <div class="sidebar-foot">
        <div class="user-row">
            <div class="av av-sm" style="background: var(--accent); color: #ede9e3">
                {{ $authUser->initials ?? 'U' }}
            </div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ $authUser->role ?? '-' }}</div>
            </div>
        </div>
    </div>

</nav>
