{{--
    ┌──────────────────────────────────────────────────┐
    │  COMPONENT: __header.blade.php                   │
    │  Topbar sticky — breadcrumb + CTA + theme toggle │
    │                                                  │
    │  Props / Variables yang dipakai:                 │
    │  - $pageTitle     → string, judul halaman        │
    │  - $breadcrumbs   → array of:                    │
    │      [['label' => 'Nama', 'url' => route()],     │
    │       ['label' => 'Aktif']]  (terakhir = aktif)  │
    │  - $headerCta     → array (opsional):            │
    │      ['label' => 'Teks', 'url' => route() atau   │
    │       'onclick' => 'fnName()']                   │
    └──────────────────────────────────────────────────┘
--}}

<header class="topbar">

    {{-- Tombol hamburger — tampil di mobile --}}
    <button class="menu-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
             stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
            <path d="M2 4h11M2 7.5h11M2 11h11"/>
        </svg>
    </button>

    {{-- ── BREADCRUMB ── --}}
    <nav id="topbar-breadcrumb" class="breadcrumb" aria-label="Breadcrumb">
        @if(isset($breadcrumbs) && count($breadcrumbs))
            @foreach($breadcrumbs as $i => $crumb)
                @if($i < count($breadcrumbs) - 1)
                    {{-- Link (bukan yang terakhir) --}}
                    <a class="crumb-link" href="{{ $crumb['url'] ?? '#' }}">
                        {{ $crumb['label'] }}
                    </a>
                    <span class="sep">›</span>
                @else
                    {{-- Aktif (terakhir) --}}
                    <span class="crumb-active">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        @else
            {{-- Fallback: tampilkan page title saja --}}
            <span class="crumb-active">{{ $pageTitle ?? 'Dashboard' }}</span>
        @endif
    </nav>

    {{-- ── KANAN: theme toggle + CTA --}}
    <div class="topbar-right">

        {{-- Theme Toggle --}}
        <button class="theme-btn" onclick="toggleTheme()" aria-label="Toggle Dark Mode" title="Ganti tema">
            <svg id="theme-icon" width="14" height="14" viewBox="0 0 15 15"
                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <path d="M7.5 1v1M7.5 13v1M1 7.5H0M15 7.5h-1M2.93 2.93l-.7-.7M13.47 13.47l-.7-.7M2.23 12.77l.7-.7M13.47 2.53l-.7.7"/>
                <circle cx="7.5" cy="7.5" r="3"/>
            </svg>
        </button>

        {{-- CTA Button (opsional, dikontrol dari controller/view) --}}
        @if(isset($headerCta))
            @if(isset($headerCta['url']))
                <a href="{{ $headerCta['url'] }}" class="btn btn-primary" id="topbar-cta">
                    <svg viewBox="0 0 14 14"><path d="M7 1v12M1 7h12"/></svg>
                    <span>{{ $headerCta['label'] }}</span>
                </a>
            @elseif(isset($headerCta['onclick']))
                <button class="btn btn-primary" id="topbar-cta"
                        onclick="{{ $headerCta['onclick'] }}">
                    <svg viewBox="0 0 14 14"><path d="M7 1v12M1 7h12"/></svg>
                    <span>{{ $headerCta['label'] }}</span>
                </button>
            @endif
        @endif

    </div>{{-- /.topbar-right --}}

</header>
