{{--
    ┌──────────────────────────────────────────────────┐
    │  PAGE: employee/presence/create.blade.php        │
    │  Form Check In karyawan (GPS + Kamera Selfie)    │
    │                                                  │
    │  Variables dari PresenceController@create:       │
    │  - $employees → collection (tidak dipakai di     │
    │    sisi employee, tapi dikirim dari controller)  │
    │  - $authUser  → object (name, role, initials,    │
    │                 division, avatar_color)          │
    └──────────────────────────────────────────────────┘
--}}

@extends('employee.layouts.master')

@section('title', 'Presensi — Karyawan HR')

@php
    $pageTitle   = 'Presensi';
    $activePage  = 'presence';
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Presensi'],
    ];
    $headerCta = null;
@endphp

@section('content')

<div class="checkin-wrap">

    {{-- ── ALERT MESSAGES ── --}}
    @if(session('success'))
        <div class="alert alert-success" style="grid-column: 1 / -1">
            <svg viewBox="0 0 14 14" width="14" height="14">
                <path d="M2 7l4 4 6-7" stroke="currentColor" stroke-width="1.5"
                      fill="none" stroke-linecap="round"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="grid-column: 1 / -1">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="grid-column: 1 / -1">
            <ul style="margin:0;padding-left:16px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── PETA LOKASI ── --}}
    <div class="map-card">
        <div class="map-header">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                 stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                <path d="M8 1.5C5.5 1.5 3.5 3.5 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6c0-2.5-2-4.5-4.5-4.5z"/>
                <circle cx="8" cy="6" r="1.5"/>
            </svg>
            <span class="panel-title">Lokasi Saat Ini</span>
            <button type="button" class="btn btn-sm" style="margin-left: auto" onclick="getLocation()">
                <svg viewBox="0 0 14 14" width="12" height="12">
                    <path d="M7 1v2M7 11v2M1 7h2M11 7h2" stroke="currentColor"
                          stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    <circle cx="7" cy="7" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
                </svg>
                Perbarui
            </button>
        </div>

        {{-- Leaflet Map --}}
        <div id="checkin-map"></div>

        <div class="map-footer">
            <div class="loc-row">
                <div class="loc-icon">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                         stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                        <path d="M8 1.5C5.5 1.5 3.5 3.5 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6c0-2.5-2-4.5-4.5-4.5z"/>
                        <circle cx="8" cy="6" r="1.5"/>
                    </svg>
                </div>
                <div>
                    <div class="loc-addr" id="loc-addr">Memuat lokasi...</div>
                    <div class="loc-coord" id="loc-coord">—</div>
                </div>
            </div>
            <div class="loc-status">
                <div class="status-dot" id="loc-status-dot" style="background: var(--amber)"></div>
                <span id="loc-status-txt" style="font-size: 12px; color: var(--text-muted)">
                    Mendapatkan lokasi GPS...
                </span>
            </div>
        </div>
    </div>

    {{-- ── KAMERA SELFIE ── --}}
    <div class="camera-card">
        <div class="camera-header">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                 stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                <path d="M1.5 5.5A1.5 1.5 0 013 4h.5l1-1.5h5L10.5 4H13a1.5 1.5 0 011.5 1.5v7A1.5 1.5 0 0113 14H3a1.5 1.5 0 01-1.5-1.5v-7z"/>
                <circle cx="8" cy="9" r="2.5"/>
            </svg>
            <span class="panel-title">Foto Selfie</span>
            <span class="badge badge-purple" style="margin-left: auto" id="cam-badge">
                Kamera Aktif
            </span>
        </div>

        <div class="camera-body">
            <video id="camera-video" autoplay playsinline muted></video>
            <canvas id="camera-canvas"></canvas>
            <img id="selfie-preview" alt="Selfie"/>
            <div class="camera-overlay" id="cam-overlay">
                <div class="face-guide"></div>
                <div class="camera-label">Posisikan wajah di dalam bingkai</div>
            </div>
            <div class="camera-placeholder" id="cam-placeholder" style="display: none">
                <svg viewBox="0 0 40 40">
                    <path d="M5 15A2 2 0 017 13h2l2-3h10l2 3h2a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V15z"
                          stroke="currentColor" stroke-width="1.5" fill="none"/>
                    <circle cx="19" cy="20" r="5" stroke="currentColor" stroke-width="1.5" fill="none"/>
                </svg>
                <p>Kamera tidak aktif</p>
            </div>
        </div>

        <div class="camera-footer">
            {{-- Tombol ambil foto --}}
            <div id="shutter-wrap" style="display:flex;align-items:center;gap:10px;flex:1">
                <button type="button" class="shutter" id="shutter-btn" onclick="capturePhoto()">
                    <div class="shutter-inner"></div>
                </button>
                <div>
                    <div style="font-size:12.5px;font-weight:500;color:var(--text-primary)">
                        Ambil Foto
                    </div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        Pastikan wajah terlihat jelas
                    </div>
                </div>
            </div>
            {{-- Setelah foto diambil --}}
            <div id="retake-wrap" style="display:none;align-items:center;gap:8px;flex:1">
                <div style="flex:1">
                    <div style="font-size:12.5px;font-weight:500;color:var(--green-text)">
                        ✓ Foto berhasil diambil
                    </div>
                    <div style="font-size:11px;color:var(--text-muted)">Siap untuk presensi</div>
                </div>
                <button type="button" class="btn btn-sm" onclick="retakePhoto()">Ulangi</button>
            </div>
        </div>
    </div>

    {{-- ── FORM PRESENSI ── --}}
    <div class="checkin-summary card">

        <form action="{{ route('presences.store') }}" method="POST" id="form-presences">
            @csrf

            {{-- Hidden fields: foto & koordinat --}}
            <input type="hidden" name="photo_check_in" id="photo-input">
            <input type="hidden" name="check_in_lat"   id="check_in_lat">
            <input type="hidden" name="check_in_long"  id="check_in_long">

            <div class="panel-head">
                <span class="panel-title">Detail Presensi</span>
                <span id="checkin-time-badge" class="badge badge-blue"
                      style="font-family:'Sora',sans-serif">—</span>
            </div>

            <div class="checkin-form-grid">

                {{-- Kolom 1: Info Karyawan --}}
                <div>
                    <div style="font-size:11.5px;font-weight:500;color:var(--text-muted);
                                text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px">
                        Informasi Karyawan
                    </div>
                    <div class="checkin-meta-row">
                        <div class="checkin-meta-icon" style="background:var(--accent-soft)">
                            <div class="av av-xs" style="background:var(--accent);color:#ede9e3">
                                {{ $authUser->initials ?? substr(Auth::user()->name, 0, 2) }}
                            </div>
                        </div>
                        <span class="checkin-meta-label">Nama</span>
                        <span class="checkin-meta-val">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="checkin-meta-row">
                        <div class="checkin-meta-icon" style="background:var(--green-soft)">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
                                 stroke="var(--green-text)" stroke-width="1.5" stroke-linecap="round">
                                <rect x="2" y="3" width="12" height="10" rx="1.5"/>
                                <path d="M2 7h12M5.5 10h5"/>
                            </svg>
                        </div>
                        <span class="checkin-meta-label">Divisi</span>
                        <span class="checkin-meta-val">{{ $authUser->division ?? session('role') ?? '-' }}</span>
                    </div>
                </div>

                {{-- Kolom 2: Waktu & Lokasi --}}
                <div>
                    <div style="font-size:11.5px;font-weight:500;color:var(--text-muted);
                                text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px">
                        Waktu & Lokasi
                    </div>
                    <div class="checkin-meta-row">
                        <div class="checkin-meta-icon" style="background:var(--amber-soft)">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
                                 stroke="var(--amber-text)" stroke-width="1.5" stroke-linecap="round">
                                <circle cx="8" cy="8" r="6"/>
                                <path d="M8 5v3.5l2 1.5"/>
                            </svg>
                        </div>
                        <span class="checkin-meta-label">Tanggal</span>
                        <span class="checkin-meta-val" id="ci-date">—</span>
                    </div>
                    <div class="checkin-meta-row">
                        <div class="checkin-meta-icon" style="background:var(--amber-soft)">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
                                 stroke="var(--amber-text)" stroke-width="1.5" stroke-linecap="round">
                                <circle cx="8" cy="8" r="6"/>
                                <path d="M8 4v4l3 1"/>
                            </svg>
                        </div>
                        <span class="checkin-meta-label">Jam</span>
                        <span class="checkin-meta-val" id="ci-time"
                              style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700">—</span>
                    </div>
                    <div class="checkin-meta-row">
                        <div class="checkin-meta-icon" style="background:var(--accent-soft)">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none"
                                 stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                                <path d="M8 1.5C5.5 1.5 3.5 3.5 3.5 6c0 3.5 4.5 8.5 4.5 8.5S12.5 9.5 12.5 6c0-2.5-2-4.5-4.5-4.5z"/>
                                <circle cx="8" cy="6" r="1.5"/>
                            </svg>
                        </div>
                        <span class="checkin-meta-label">Lokasi</span>
                        <span class="checkin-meta-val" id="ci-loc" style="font-size:12px">Memuat...</span>
                    </div>
                </div>

                {{-- Kolom 3: Keterangan --}}
                <div>
                    <div style="font-size:11.5px;font-weight:500;color:var(--text-muted);
                                text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px">
                        Keterangan
                    </div>
                    <div class="form-group" style="margin-bottom:10px">
                        <label class="form-label">Tipe Kehadiran</label>
                        <select class="form-control" id="ci-type" name="attendance_type">
                            <option value="wfo">Work From Office (WFO)</option>
                            <option value="wfh">Work From Home (WFH)</option>
                            <option value="tugas_luar">Tugas Luar</option>
                            <option value="client_visit">Client Visit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="ci-notes" name="notes"
                                  style="min-height:60px"
                                  placeholder="Tambahkan catatan jika ada..."></textarea>
                    </div>
                </div>

            </div>{{-- /.checkin-form-grid --}}

            {{-- Footer Aksi --}}
            <div style="display:flex;gap:8px;justify-content:flex-end;align-items:center;
                        margin-top:6px;padding-top:14px;border-top:1px solid var(--border)">
                <span id="checkin-selfie-status" class="badge badge-amber">
                    <span class="badge-dot" style="background:var(--amber)"></span>
                    Belum ada foto
                </span>
                <span id="checkin-loc-status" class="badge badge-amber">
                    <span class="badge-dot" style="background:var(--amber)"></span>
                    Lokasi belum tersedia
                </span>
                <button type="button" class="btn btn-primary" id="submit-checkin"
                        onclick="submitCheckIn()" disabled>
                    <svg viewBox="0 0 14 14" width="14" height="14">
                        <path d="M2 7l4 4 6-7" stroke="currentColor" stroke-width="1.5"
                              fill="none" stroke-linecap="round"/>
                    </svg>
                    Submit Presensi
                </button>
            </div>

        </form>

    </div>{{-- /.checkin-summary --}}

</div>{{-- /.checkin-wrap --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Jam & Tanggal realtime ──────────────────────────────
    function updateClock() {
        const now  = new Date();
        const date = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        const time = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });

        document.getElementById('ci-date').textContent            = date;
        document.getElementById('ci-time').textContent            = time;
        document.getElementById('checkin-time-badge').textContent = time;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Status flags ────────────────────────────────────────
    let locationValid = false;
    let photoTaken    = false;

    function checkReady() {
        const btn = document.getElementById('submit-checkin');
        if (locationValid && photoTaken) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', true);
        }
    }

    // ── Leaflet Map ─────────────────────────────────────────
    const officeLat = -7.570312354872347;
    const officeLon = 110.80334127983431;
    const threshold = 0.01;

    let map    = null;
    let marker = null;

    window.getLocation = function () {
        if (!navigator.geolocation) {
            showToast('Geolocation tidak didukung browser ini.', 'error');
            return;
        }

        document.getElementById('loc-status-txt').textContent     = 'Mendapatkan lokasi GPS...';
        document.getElementById('loc-status-dot').style.background = 'var(--amber)';

        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;

            // Isi hidden input
            document.getElementById('check_in_lat').value  = lat;
            document.getElementById('check_in_long').value = lon;

            // Update koordinat label
            document.getElementById('loc-coord').textContent =
                lat.toFixed(6) + ', ' + lon.toFixed(6);
            document.getElementById('ci-loc').textContent =
                lat.toFixed(5) + ', ' + lon.toFixed(5);

            // Init / update peta
            if (!map) {
                map = L.map('checkin-map').setView([lat, lon], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
            } else {
                map.setView([lat, lon], 16);
            }

            if (marker) { map.removeLayer(marker); }
            marker = L.marker([lat, lon]).addTo(map)
                       .bindPopup('Posisi Anda').openPopup();

            // Cek radius kantor
            const dist = Math.sqrt(
                Math.pow(lat - officeLat, 2) +
                Math.pow(lon - officeLon, 2)
            );

            if (dist <= threshold) {
                locationValid = true;
                document.getElementById('loc-status-dot').style.background = 'var(--green)';
                document.getElementById('loc-status-txt').textContent       = 'Anda berada di area kantor ✓';
                document.getElementById('loc-addr').textContent             = 'Area Kantor';

                // Update badge lokasi
                const locBadge = document.getElementById('checkin-loc-status');
                locBadge.className = 'badge badge-green';
                locBadge.innerHTML = '<span class="badge-dot" style="background:var(--green)"></span> Lokasi valid';
            } else {
                locationValid = false;
                document.getElementById('loc-status-dot').style.background = 'var(--red, #e55)';
                document.getElementById('loc-status-txt').textContent       = 'Di luar area kantor';
                document.getElementById('loc-addr').textContent             = 'Lokasi tidak valid';
                showToast('Kamu tidak berada di area kantor.', 'error');
            }

            checkReady();

        }, function (err) {
            showToast('Gagal mendapatkan lokasi: ' + err.message, 'error');
        });
    };

    // Panggil otomatis saat load
    getLocation();

    // ── Kamera ─────────────────────────────────────────────
    const video       = document.getElementById('camera-video');
    const canvas      = document.getElementById('camera-canvas');
    const preview     = document.getElementById('selfie-preview');
    const photoInput  = document.getElementById('photo-input');
    const shutterWrap = document.getElementById('shutter-wrap');
    const retakeWrap  = document.getElementById('retake-wrap');
    const camBadge    = document.getElementById('cam-badge');
    const placeholder = document.getElementById('cam-placeholder');
    const overlay     = document.getElementById('cam-overlay');

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(function (stream) {
                video.srcObject = stream;
                video.style.display = 'block';
                placeholder.style.display = 'none';
            })
            .catch(function (err) {
                video.style.display      = 'none';
                placeholder.style.display = 'flex';
                camBadge.className        = 'badge badge-amber';
                camBadge.textContent      = 'Kamera tidak aktif';
                showToast('Tidak bisa mengakses kamera: ' + err.message, 'error');
            });
    }

    window.capturePhoto = function () {
        canvas.width  = video.videoWidth  || 640;
        canvas.height = video.videoHeight || 480;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const dataURL = canvas.toDataURL('image/jpeg', 0.8);

        photoInput.value   = dataURL;
        preview.src        = dataURL;
        preview.style.display = 'block';
        video.style.display   = 'none';
        overlay.style.display = 'none';

        shutterWrap.style.display = 'none';
        retakeWrap.style.display  = 'flex';

        camBadge.className   = 'badge badge-green';
        camBadge.textContent = 'Foto Diambil ✓';

        // Update selfie badge
        const selfieBadge = document.getElementById('checkin-selfie-status');
        selfieBadge.className = 'badge badge-green';
        selfieBadge.innerHTML = '<span class="badge-dot" style="background:var(--green)"></span> Foto siap';

        photoTaken = true;
        checkReady();
    };

    window.retakePhoto = function () {
        photoInput.value      = '';
        preview.src           = '';
        preview.style.display = 'none';
        video.style.display   = 'block';
        overlay.style.display = 'flex';

        shutterWrap.style.display = 'flex';
        retakeWrap.style.display  = 'none';

        camBadge.className   = 'badge badge-purple';
        camBadge.textContent = 'Kamera Aktif';

        const selfieBadge = document.getElementById('checkin-selfie-status');
        selfieBadge.className = 'badge badge-amber';
        selfieBadge.innerHTML = '<span class="badge-dot" style="background:var(--amber)"></span> Belum ada foto';

        photoTaken = false;
        checkReady();
    };

    // ── Submit ──────────────────────────────────────────────
    window.submitCheckIn = function () {
        if (!locationValid) {
            showToast('Lokasi belum valid. Perbarui lokasi terlebih dahulu.', 'error');
            return;
        }
        if (!photoTaken) {
            showToast('Ambil foto selfie terlebih dahulu.', 'error');
            return;
        }
        document.getElementById('form-presences').submit();
    };

});
</script>
@endpush