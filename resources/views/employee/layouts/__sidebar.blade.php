{{--
    ┌──────────────────────────────────────────────────┐
    │  COMPONENT: __sidebar.blade.php                  │
    │  Sidebar navigasi utama HR App                   │
    │                                                  │
    │  Props / Variables yang dipakai dari controller: │
    │  - $activePage  → string, nama halaman aktif     │
    │    (presence | checkin | tasks | payrolls |      │
    │     leave-list)                                  │
    │  - $authUser    → object, data user login        │
    │    ($authUser->name, $authUser->role,            │
    │     $authUser->initials)                         │
    │  - $taskBadge   → int, jumlah task pending       │
    └──────────────────────────────────────────────────┘
--}}

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

        {{-- Check In / Out --}}
        {{-- <a href="{{ route('checkin.index') }}"
           class="nav-item {{ $activePage === 'checkin' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 16 16">
                <circle cx="8" cy="7" r="3.5"/>
                <path d="M8 10.5C5 10.5 2.5 12 2.5 14h11C13.5 12 11 10.5 8 10.5z"/>
                <circle cx="13" cy="4" r="2.5" fill="var(--green)" stroke="none"/>
                <path d="M12 4l.7.7 1.3-1.3" stroke="#fff" stroke-width="1.2" fill="none"/>
            </svg>
            Check In / Out
        </a> --}}

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
