<style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Mazer colour tokens ─────────────────────────────────── */
        :root {
            --bg:           #f2f2f2;
            --surface:      #ffffff;
            --surface-2:    #f8f8f8;
            --border:       #e4e6ef;
            --text-primary: #25293c;
            --text-muted:   #6c757d;
            --text-subtle:  #a1a5b7;
            --accent:       #435ebe;
            --accent-hover: #3652ca;
            --accent-text:  #ffffff;
            --input-bg:     #f5f8fa;
            --input-border: #e4e6ef;
            --shadow:       0 4px 24px rgba(67,94,190,.10), 0 1px 4px rgba(0,0,0,.06);
            --shadow-btn:   0 4px 12px rgba(67,94,190,.35);
            --r-card:       16px;
            --r-input:      8px;
            --ease:         all 0.22s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --bg:           #1a1d2e;
            --surface:      #25293c;
            --surface-2:    #2f3349;
            --border:       #3d4166;
            --text-primary: #e8eaf2;
            --text-muted:   #9899ac;
            --text-subtle:  #5f6281;
            --accent:       #435ebe;
            --accent-hover: #5a73d1;
            --accent-text:  #ffffff;
            --input-bg:     #1e2130;
            --input-border: #3d4166;
            --shadow:       0 4px 32px rgba(0,0,0,.45), 0 1px 6px rgba(0,0,0,.3);
            --shadow-btn:   0 4px 16px rgba(67,94,190,.45);
        }

        /* ── Wrapper ─────────────────────────────────────────────── */
        .auth-wrap {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            transition: background .3s ease;
        }

        /* ── Card ────────────────────────────────────────────────── */
        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-card);
            padding: 40px 44px;
            width: 100%;
            max-width: 450px;
            box-shadow: var(--shadow);
            transition: var(--ease);
            position: relative;
            animation: slideUp .35s cubic-bezier(.4,0,.2,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Theme toggle ────────────────────────────────────────── */
        .theme-toggle {
            position: absolute;
            top: 18px; right: 20px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .toggle-lbl {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--text-subtle);
            user-select: none;
        }

        .sw { position: relative; width: 46px; height: 26px; cursor: pointer; }
        .sw input { display: none; }

        .sw-track {
            position: absolute; inset: 0;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 99px;
            transition: var(--ease);
        }

        .sw-thumb {
            position: absolute;
            top: 4px; left: 4px;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--text-subtle);
            font-size: 10px;
            display: flex; align-items: center; justify-content: center;
            transition: var(--ease);
        }

        .sw input:checked ~ .sw-track { background: var(--accent); border-color: var(--accent); }
        .sw input:checked ~ .sw-thumb { transform: translateX(20px); background: #fff; }

        /* ── Brand ───────────────────────────────────────────────── */
        .brand {
            display: flex; align-items: center; gap: 11px;
            margin-bottom: 30px;
        }

        .brand-ico {
            width: 44px; height: 44px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-ico svg { width: 24px; height: 24px; fill: #fff; }

        .brand-name {
            font-size: 20px; font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.01em;
        }

        /* ── Headings ────────────────────────────────────────────── */
        .auth-title {
            font-size: 26px; font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.02em;
            margin-bottom: 5px;
        }

        .auth-sub {
            font-size: 15px; font-weight: 400;
            color: var(--text-muted);
            margin-bottom: 28px;
        }

        .auth-divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 28px;
            transition: var(--ease);
        }

        /* ── Session status ──────────────────────────────────────── */
        .session-alert {
            background: rgba(67,94,190,.12);
            border: 1px solid rgba(67,94,190,.28);
            color: var(--accent);
            border-radius: 8px;
            padding: 11px 15px;
            font-size: 14px; font-weight: 600;
            margin-bottom: 20px;
        }

        /* ── Fields ──────────────────────────────────────────────── */
        .field-group { display: flex; flex-direction: column; gap: 22px; margin-bottom: 22px; }
        .field       { display: flex; flex-direction: column; gap: 8px; }

        .field-lbl {
            font-size: 14px; font-weight: 700;
            color: var(--text-primary);
        }

        .field-inp {
            font-family: 'Nunito', sans-serif;
            font-size: 15.5px; font-weight: 400;
            color: var(--text-primary);
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: var(--r-input);
            padding: 13px 16px;
            width: 100%;
            outline: none;
            transition: var(--ease);
            -webkit-appearance: none;
        }

        .field-inp::placeholder { color: var(--text-subtle); }

        .field-inp:hover  { border-color: var(--text-subtle); }

        .field-inp:focus {
            border-color: var(--accent);
            background: var(--surface);
            box-shadow: 0 0 0 3.5px rgba(67,94,190,.15);
        }

        .field-err {
            font-size: 12.5px; font-weight: 600;
            color: #ea5455;
        }

        /* ── Meta row ────────────────────────────────────────────── */
        .auth-meta {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .rem-lbl {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer;
        }

        .rem-check {
            width: 16px; height: 16px;
            border-radius: 4px;
            accent-color: var(--accent);
            cursor: pointer; flex-shrink: 0;
        }

        .rem-text {
            font-size: 14px; font-weight: 600;
            color: var(--text-muted);
            user-select: none;
        }

        .forgot-link {
            font-size: 14px; font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            transition: color .15s;
        }

        .forgot-link:hover { color: var(--accent-hover); text-decoration: underline; }

        /* ── Button ──────────────────────────────────────────────── */
        .btn-submit {
            width: 100%;
            font-family: 'Nunito', sans-serif;
            font-size: 15.5px; font-weight: 800;
            letter-spacing: .02em;
            color: var(--accent-text);
            background: var(--accent);
            border: none;
            border-radius: var(--r-input);
            padding: 15px 20px;
            cursor: pointer;
            transition: var(--ease);
            box-shadow: var(--shadow-btn);
        }

        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(67,94,190,.5);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ── Footer ──────────────────────────────────────────────── */
        .auth-footer {
            margin-top: 22px;
            display: flex; align-items: center;
            justify-content: center; gap: 7px;
            flex-wrap: wrap;
        }

        .ft-dot { width: 4px; height: 4px; background: var(--border); border-radius: 50%; flex-shrink: 0; }

        .ft-txt {
            font-size: 12px; font-weight: 600;
            color: var(--text-subtle);
        }

        /* ══════════════════════════════════════════════════════════
           RESPONSIVE BREAKPOINTS
           ══════════════════════════════════════════════════════════ */

        /* ── Tablet (641px – 1024px) ─────────────────────────────── */
        @media (max-width: 1024px) and (min-width: 641px) {
            .auth-wrap  { padding: 32px 24px; align-items: center; }
            .auth-card  { padding: 44px 40px; max-width: 520px; width: 100%; border-radius: 18px; }
            .auth-title { font-size: 26px; }
            .auth-sub   { font-size: 15px; }
            .brand      { margin-bottom: 28px; padding-right: 90px; }
            .field-inp  { font-size: 16px; padding: 15px 18px; }
            .btn-submit { font-size: 16px; padding: 17px 20px; }
        }

        /* ── Mobile (≤ 640px): full-screen ──────────────────────── */
        @media (max-width: 640px) {
            .auth-wrap {
                padding: 0;
                align-items: stretch;
                min-height: 100dvh;
            }
            .auth-card {
                width: 100%; max-width: 100%; min-height: 100dvh;
                border-radius: 0; border: none; box-shadow: none; animation: none;
                padding: 60px 32px 48px;
                display: flex; flex-direction: column; justify-content: center;
            }
            .theme-toggle { position: fixed; top: 20px; right: 20px; }
            .toggle-lbl   { display: none; }
            .sw           { width: 54px; height: 30px; }
            .sw-thumb     { width: 20px; height: 20px; top: 4px; left: 4px; }
            .sw input:checked ~ .sw-thumb { transform: translateX(24px); }
            .brand        { margin-bottom: 36px; padding-right: 0; }
            .brand-ico    { width: 56px; height: 56px; border-radius: 14px; }
            .brand-ico svg { width: 30px; height: 30px; }
            .brand-name   { font-size: 24px; }
            .auth-title   { font-size: 30px; margin-bottom: 8px; }
            .auth-sub     { font-size: 16px; margin-bottom: 32px; }
            .auth-divider { margin-bottom: 32px; }
            .field-lbl    { font-size: 15px; }
            .field-group  { gap: 26px; margin-bottom: 26px; }
            .field-inp    { font-size: 16px; padding: 18px 18px; border-radius: 12px; }
            .auth-meta    { flex-direction: row; flex-wrap: wrap; align-items: center; gap: 12px; margin-bottom: 32px; }
            .rem-check    { width: 20px; height: 20px; }
            .rem-text, .forgot-link { font-size: 15px; }
            .btn-submit   { font-size: 17px; padding: 20px 20px; border-radius: 12px; }
            .auth-footer  { margin-top: 32px; gap: 8px; }
            .ft-txt       { font-size: 13px; }
        }

        /* ── Very small (≤ 375px) ────────────────────────────────── */
        @media (max-width: 375px) {
            .auth-card  { padding: 52px 24px 40px; }
            .auth-title { font-size: 26px; }
            .brand-ico  { width: 48px; height: 48px; }
            .brand-name { font-size: 20px; }
        }

        /* ── Landscape mobile ────────────────────────────────────── */
        @media (max-width: 768px) and (orientation: landscape) and (max-height: 500px) {
            .auth-wrap  { padding: 16px; align-items: flex-start; }
            .auth-card  {
                min-height: unset; padding: 28px 32px;
                border-radius: 14px; border: 1px solid var(--border); box-shadow: var(--shadow);
            }
            .theme-toggle { position: absolute; }
            .brand        { margin-bottom: 18px; }
            .auth-title   { font-size: 22px; }
            .auth-sub     { font-size: 13px; margin-bottom: 18px; }
            .auth-divider { margin-bottom: 18px; }
            .field-group  { gap: 16px; margin-bottom: 16px; }
            .auth-meta    { margin-bottom: 18px; }
            .auth-footer  { margin-top: 16px; }
        }

        /* ── Large screens / desktops: min 1280px ────────────────── */
        @media (min-width: 1280px) {
            .auth-card {
                padding: 48px 52px;
                max-width: 480px;
            }

            .auth-title { font-size: 28px; }
        }
    </style>

    {{-- Wrapper: default dark, JS overrides from localStorage --}}
    <div class="auth-wrap" id="authWrap">
        <div class="auth-card">

            {{-- ── Theme toggle ── --}}
            <div class="theme-toggle">
                <span class="toggle-lbl" id="tglLabel">Dark</span>
                <label class="sw" title="Toggle dark / light">
                    <input type="checkbox" id="tglInput" />
                    <span class="sw-track"></span>
                    <span class="sw-thumb" id="tglThumb">🌙</span>
                </label>
            </div>

            {{-- Brand --}}
            <div class="brand">
                <div class="brand-ico">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 3a7 7 0 1 1 0 14A7 7 0 0 1 12 5zm0 2a5 5 0 1 0 0 10A5 5 0 0 0 12 7zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
                </div>
                <span class="brand-name">MY HRis</span>
            </div>

            <h1 class="auth-title">Sign in to account</h1>
            <p class="auth-sub">Please sign in to your account below</p>

            <div class="auth-divider"></div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="session-alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    {{-- Email --}}
                    <div class="field">
                        <label class="field-lbl" for="email">Email Address</label>
                        <input
                            id="email" class="field-inp"
                            type="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            required autofocus autocomplete="username"
                        />
                        @error('email')
                            <p class="field-err">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field">
                        <label class="field-lbl" for="password">Password</label>
                        <input
                            id="password" class="field-inp"
                            type="password" name="password"
                            placeholder="Enter your password"
                            required autocomplete="current-password"
                        />
                        @error('password')
                            <p class="field-err">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Remember + Forgot --}}
                <div class="auth-meta">
                    <label class="rem-lbl" for="remember_me">
                        <input id="remember_me" class="rem-check" type="checkbox" name="remember" />
                        <span class="rem-text">Remember me</span>
                    </label>

                    {{-- @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif --}}
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="auth-footer">
                <span class="ft-txt">Secured</span>
                <span class="ft-dot"></span>
                <span class="ft-txt">256-bit Encryption</span>
                <span class="ft-dot"></span>
                <span class="ft-txt">Protected</span>
            </div>

        </div>{{-- /card --}}
    </div>{{-- /wrap --}}

    <script>
        (function () {
            var wrap  = document.getElementById('authWrap');
            var inp   = document.getElementById('tglInput');
            var lbl   = document.getElementById('tglLabel');
            var thumb = document.getElementById('tglThumb');

            /* Default: dark (Mazer dark palette) */
            var saved = localStorage.getItem('mazer-theme') || 'dark';
            apply(saved);

            inp.addEventListener('change', function () {
                var next = this.checked ? 'light' : 'dark';
                apply(next);
                localStorage.setItem('mazer-theme', next);
            });

            function apply(theme) {
                if (theme === 'light') {
                    wrap.setAttribute('data-theme', 'light');
                    inp.checked     = true;
                    lbl.textContent = 'Light';
                    thumb.textContent = '☀️';
                } else {
                    wrap.setAttribute('data-theme', 'dark');
                    inp.checked     = false;
                    lbl.textContent = 'Dark';
                    thumb.textContent = '🌙';
                }
            }
        })();
    </script>