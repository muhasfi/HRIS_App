{{--
    ┌──────────────────────────────────────────────────┐
    │  COMPONENT: __footer.blade.php                   │
    │  Footer minimal di bawah content area            │
    │                                                  │
    │  Props / Variables yang dipakai:                 │
    │  - $appVersion → string, versi aplikasi          │
    │    default: 'v2.0.0'                             │
    └──────────────────────────────────────────────────┘
--}}

<footer class="app-footer">
    <span class="footer-copy">
        &copy; {{ date('Y') }} Karyawan HR &mdash; All rights reserved.
    </span>
    <span class="footer-ver">{{ $appVersion ?? 'v2.0.0' }}</span>
</footer>
