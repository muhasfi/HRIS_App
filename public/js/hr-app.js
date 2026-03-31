/**
 * =====================================================
 *  HR APP — GLOBAL JAVASCRIPT
 *  File: public/js/hr-app.js
 *  Dipanggil dari: layouts/master.blade.php
 * =====================================================
 */

"use strict";

/* ══════════════════════════════════════════
   THEME TOGGLE
══════════════════════════════════════════ */
function toggleTheme() {
    const isDark =
        document.documentElement.getAttribute("data-theme") === "dark";
    const next = isDark ? "light" : "dark";
    document.documentElement.setAttribute("data-theme", next);
    localStorage.setItem("hr-theme", next);

    const icon = document.getElementById("theme-icon");
    if (!icon) return;
    icon.innerHTML = isDark
        ? '<path d="M7.5 1v1M7.5 13v1M1 7.5H0M15 7.5h-1M2.93 2.93l-.7-.7M13.47 13.47l-.7-.7M2.23 12.77l.7-.7M13.47 2.53l-.7.7"/><circle cx="7.5" cy="7.5" r="3"/>'
        : '<path d="M12 7.5a4.5 4.5 0 01-4.5 4.5A4.5 4.5 0 013 7.5 4.5 4.5 0 017.5 3c-.5 1-.8 2.2-.8 3.3C6.7 9.8 9 12 12 12c0-.1 0-.3 0-.5z"/>';
}

// Restore saved theme on load
(function () {
    const saved = localStorage.getItem("hr-theme");
    if (saved) document.documentElement.setAttribute("data-theme", saved);
})();

/* ══════════════════════════════════════════
   SIDEBAR (Mobile)
══════════════════════════════════════════ */
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;
    if (sidebar.classList.contains("open")) {
        closeSidebar();
    } else {
        sidebar.classList.add("open");
        document.getElementById("overlay")?.classList.add("open");
        document.body.style.overflow = "hidden";
    }
}
function closeSidebar() {
    document.getElementById("sidebar")?.classList.remove("open");
    document.getElementById("overlay")?.classList.remove("open");
    document.body.style.overflow = "";
}

/* ══════════════════════════════════════════
   TOAST NOTIFICATION
══════════════════════════════════════════ */
let _toastTimer;
function showToast(msg) {
    const t = document.getElementById("toast");
    if (!t) return;
    t.textContent = msg;
    t.classList.add("show");
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(() => t.classList.remove("show"), 2800);
}

/* ══════════════════════════════════════════
   HELPERS
══════════════════════════════════════════ */
function fmtDate(s) {
    if (!s || s === "—") return "—";
    const d = new Date(s);
    const m = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Ags",
        "Sep",
        "Okt",
        "Nov",
        "Des",
    ];
    return `${d.getDate()} ${m[d.getMonth()]} ${d.getFullYear()}`;
}

/* ══════════════════════════════════════════
   CHECK-IN — Live Clock
══════════════════════════════════════════ */
let _clockInterval;

function updateCheckinTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
    const dateStr = now.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });

    const ciDate = document.getElementById("ci-date");
    const ciTime = document.getElementById("ci-time");
    const badge = document.getElementById("checkin-time-badge");
    if (ciDate) ciDate.textContent = dateStr;
    if (ciTime) ciTime.textContent = timeStr.slice(0, 5);
    if (badge) badge.textContent = timeStr;
}

/* ══════════════════════════════════════════
   CHECK-IN — Map (Leaflet)
══════════════════════════════════════════ */
let _map, _marker, _locData;

function initMap() {
    if (_map || !document.getElementById("checkin-map")) return;
    const defaultLL = [-6.2088, 106.8456]; // Jakarta
    _map = L.map("checkin-map", {
        zoomControl: true,
        attributionControl: true,
    }).setView(defaultLL, 15);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap",
        maxZoom: 19,
    }).addTo(_map);
    const customIcon = L.divIcon({
        html: `<div style="width:36px;height:36px;background:var(--accent);border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        className: "",
    });
    _marker = L.marker(defaultLL, { icon: customIcon }).addTo(_map);
    _marker.bindPopup("<b>Lokasi Anda</b><br>Menunggu GPS...").openPopup();
    getLocation();
}

function getLocation() {
    const locAddr = document.getElementById("loc-addr");
    const locCoord = document.getElementById("loc-coord");
    const locDot = document.getElementById("loc-status-dot");
    const locTxt = document.getElementById("loc-status-txt");
    const ciLoc = document.getElementById("ci-loc");
    const locStatus = document.getElementById("checkin-loc-status");

    if (!navigator.geolocation) {
        if (locAddr) locAddr.textContent = "Browser tidak mendukung GPS";
        return;
    }
    if (locDot) locDot.style.background = "var(--amber)";
    if (locTxt) locTxt.textContent = "Mendapatkan lokasi GPS...";

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const { latitude: lat, longitude: lng } = pos.coords;
            _locData = { lat, lng };
            if (_map) _map.setView([lat, lng], 16);
            if (_marker) _marker.setLatLng([lat, lng]);
            if (locCoord)
                locCoord.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            if (locDot) locDot.style.background = "var(--green)";
            if (locTxt) locTxt.textContent = "Lokasi berhasil didapatkan";
            if (ciLoc)
                ciLoc.textContent = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            if (locStatus) {
                locStatus.className = "badge badge-green";
                locStatus.innerHTML =
                    '<span class="badge-dot" style="background:var(--green)"></span>Lokasi tersedia';
            }
            fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`,
            )
                .then((r) => r.json())
                .then((d) => {
                    const short = (d.display_name || "Lokasi ditemukan")
                        .split(",")
                        .slice(0, 3)
                        .join(", ");
                    if (locAddr) locAddr.textContent = short;
                    if (ciLoc) ciLoc.textContent = short;
                    if (_marker)
                        _marker.setPopupContent(
                            `<b>Lokasi Anda</b><br>${short}`,
                        );
                })
                .catch(() => {
                    if (locAddr)
                        locAddr.textContent = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                });
        },
        () => {
            _locData = { lat: -6.2088, lng: 106.8456 };
            if (locAddr)
                locAddr.textContent = "Jl. Sudirman, Jakarta Pusat (Demo)";
            if (locCoord) locCoord.textContent = "-6.208800, 106.845600";
            if (locDot) locDot.style.background = "var(--blue-text)";
            if (locTxt)
                locTxt.textContent = "Menggunakan lokasi demo (Jakarta)";
            if (ciLoc) ciLoc.textContent = "Jakarta Pusat (Demo)";
            if (locStatus) {
                locStatus.className = "badge badge-blue";
                locStatus.innerHTML =
                    '<span class="badge-dot" style="background:var(--blue-text)"></span>Mode Demo';
            }
        },
        { enableHighAccuracy: true, timeout: 10000 },
    );
}

/* ══════════════════════════════════════════
   CHECK-IN — Camera
══════════════════════════════════════════ */
let _cameraStream, _selfieData;

function startCamera() {
    const video = document.getElementById("camera-video");
    const placeholder = document.getElementById("cam-placeholder");
    const badge = document.getElementById("cam-badge");
    const overlay = document.getElementById("cam-overlay");
    const preview = document.getElementById("selfie-preview");
    if (!video) return;

    if (preview) preview.style.display = "none";
    if (video) video.style.display = "block";
    if (overlay) overlay.style.display = "flex";

    if (navigator.mediaDevices?.getUserMedia) {
        navigator.mediaDevices
            .getUserMedia({
                video: {
                    facingMode: "user",
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                },
            })
            .then((stream) => {
                _cameraStream = stream;
                video.srcObject = stream;
                if (placeholder) placeholder.style.display = "none";
                if (badge) {
                    badge.className = "badge badge-green";
                    badge.textContent = "Kamera Aktif";
                }
            })
            .catch(() => {
                if (video) video.style.display = "none";
                if (placeholder) placeholder.style.display = "flex";
                if (overlay) overlay.style.display = "none";
                if (badge) {
                    badge.className = "badge badge-red";
                    badge.textContent = "Kamera Error";
                }
            });
    } else {
        if (video) video.style.display = "none";
        if (placeholder) placeholder.style.display = "flex";
        if (badge) {
            badge.className = "badge badge-red";
            badge.textContent = "Tidak Didukung";
        }
    }
}

function capturePhoto() {
    const video = document.getElementById("camera-video");
    const canvas = document.getElementById("camera-canvas");
    const preview = document.getElementById("selfie-preview");
    const overlay = document.getElementById("cam-overlay");
    const shutterWrap = document.getElementById("shutter-wrap");
    const retakeWrap = document.getElementById("retake-wrap");
    const selfieStatus = document.getElementById("checkin-selfie-status");
    if (!video || !canvas) return;

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext("2d");
    ctx.save();
    ctx.scale(-1, 1);
    ctx.drawImage(video, -canvas.width, 0);
    ctx.restore();
    _selfieData = canvas.toDataURL("image/jpeg", 0.85);

    if (preview) {
        preview.src = _selfieData;
        preview.style.display = "block";
    }
    if (video) video.style.display = "none";
    if (overlay) overlay.style.display = "none";
    if (shutterWrap) shutterWrap.style.display = "none";
    if (retakeWrap) retakeWrap.style.display = "flex";
    if (selfieStatus) {
        selfieStatus.className = "badge badge-green";
        selfieStatus.innerHTML =
            '<span class="badge-dot" style="background:var(--green)"></span>Foto tersedia';
    }
    showToast("✓ Foto berhasil diambil");
}

function retakePhoto() {
    const video = document.getElementById("camera-video");
    const preview = document.getElementById("selfie-preview");
    const overlay = document.getElementById("cam-overlay");
    const shutterWrap = document.getElementById("shutter-wrap");
    const retakeWrap = document.getElementById("retake-wrap");
    const selfieStatus = document.getElementById("checkin-selfie-status");
    _selfieData = null;
    if (preview) {
        preview.style.display = "none";
        preview.src = "";
    }
    if (video) video.style.display = "block";
    if (overlay) overlay.style.display = "flex";
    if (shutterWrap) shutterWrap.style.display = "flex";
    if (retakeWrap) retakeWrap.style.display = "none";
    if (selfieStatus) {
        selfieStatus.className = "badge badge-amber";
        selfieStatus.innerHTML =
            '<span class="badge-dot" style="background:var(--amber)"></span>Belum ada foto';
    }
}

function submitCheckIn() {
    if (!_selfieData) {
        showToast("⚠️ Ambil foto selfie terlebih dahulu");
        return;
    }
    if (!_locData) {
        showToast("⚠️ Lokasi belum tersedia");
        return;
    }

    const type = document.getElementById("ci-type")?.value || "";
    const notes = document.getElementById("ci-notes")?.value || "";

    // Kirim ke backend via fetch (sesuaikan route)
    fetch("/checkin/store", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
        },
        body: JSON.stringify({
            selfie: _selfieData,
            lat: _locData.lat,
            lng: _locData.lng,
            attendance_type: type,
            notes,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                showToast("✓ Check In berhasil!");
                setTimeout(() => (window.location.href = "/presence"), 1500);
            } else {
                showToast("⚠️ " + (data.message || "Gagal check in"));
            }
        })
        .catch(() => {
            // Demo mode: hanya toast
            showToast("✓ Check In berhasil! (Demo)");
        });
}

function initCheckin() {
    updateCheckinTime();
    clearInterval(_clockInterval);
    _clockInterval = setInterval(updateCheckinTime, 1000);
    initMap();
    startCamera();
}

/* ══════════════════════════════════════════
   LEAVE FORM — Day Counter
══════════════════════════════════════════ */
function calcDays() {
    const start = document.getElementById("lf-start")?.value;
    const end = document.getElementById("lf-end")?.value;
    const wrap = document.getElementById("day-counter-wrap");
    const count = document.getElementById("day-count");
    if (!wrap || !count) return;
    if (start && end) {
        const s = new Date(start),
            e = new Date(end);
        if (e >= s) {
            let days = 0,
                cur = new Date(s);
            while (cur <= e) {
                const d = cur.getDay();
                if (d !== 0 && d !== 6) days++;
                cur.setDate(cur.getDate() + 1);
            }
            count.textContent = days;
            wrap.style.display = "block";
            return;
        }
    }
    wrap.style.display = "none";
}

/* ══════════════════════════════════════════
   LEAVE FORM — Attachment
══════════════════════════════════════════ */
function handleAttach(e) {
    const file = e.target.files[0];
    if (!file) return;
    document.getElementById("attached-name").textContent = file.name;
    document.getElementById("attached-file").style.display = "flex";
    document.getElementById("attach-area").style.display = "none";
    showToast("Lampiran berhasil ditambahkan");
}
function removeAttach() {
    document.getElementById("attached-file").style.display = "none";
    document.getElementById("attach-area").style.display = "block";
}

/* ══════════════════════════════════════════
   TASKS — Filter Tabs (jika data dari JS)
══════════════════════════════════════════ */
function filterTasks(filter, el) {
    document
        .querySelectorAll(".tab")
        .forEach((t) => t.classList.remove("active"));
    if (el) el.classList.add("active");
    // Filter row yang sudah di-render Blade
    document.querySelectorAll("#task-list [data-status]").forEach((row) => {
        row.style.display =
            filter === "all" || row.dataset.status === filter ? "" : "none";
    });
}

/* ══════════════════════════════════════════
   DOM READY
══════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", function () {
    /* Auto-close sidebar saat nav-item diklik di mobile */
    document.querySelectorAll(".nav-item").forEach(function (item) {
        item.addEventListener("click", function () {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    /* Tutup sidebar saat resize ke desktop */
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) closeSidebar();
    });

    /* ── PAYROLL BREAKDOWN ── */
    document.querySelectorAll(".payslip-row").forEach((row) => {
        row.addEventListener("click", function (e) {
            if (e.target.closest("a")) return;

            const salary = this.dataset.salary;
            const bonuses = this.dataset.bonuses;
            const deductions = this.dataset.deductions;
            const total = this.dataset.total;

            const format = (num) =>
                "Rp " + new Intl.NumberFormat("id-ID").format(num);

            const bdSalary = document.getElementById("bd-salary");
            const bdBonus = document.getElementById("bd-bonus");
            const bdDeduction = document.getElementById("bd-deduction");
            const bdTotal = document.getElementById("bd-total");

            if (bdSalary) bdSalary.innerText = format(salary);
            if (bdBonus) bdBonus.innerText = format(bonuses);
            if (bdDeduction) bdDeduction.innerText = "- " + format(deductions);
            if (bdTotal) bdTotal.innerText = format(total);

            document
                .querySelectorAll(".payslip-row")
                .forEach((r) => r.classList.remove("active"));
            this.classList.add("active");
        });
    });
});

console.log("HR APP LOADED");
