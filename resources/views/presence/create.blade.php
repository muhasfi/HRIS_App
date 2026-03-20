@extends('layouts.master')

@section('title', 'Create Presence')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Presence</h3>
                <p class="text-subtitle text-muted">Manage Presence data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Presence</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERROR --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('role') == 'HR')

                <form action="{{ route('presences.store') }}" method="POST">
                   @csrf
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control">
                            <option value="" selected disabled>-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="check_in" class="form-label">Check In</label>
                        <input type="text" class="form-control datetime @error('check_in') is-invalid @enderror" name="check_in" value="{{ old('check_in') }}" required>
                        @error('check_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="check_out" class="form-label">Check Out</label>
                        <input type="text" class="form-control datetime @error('check_out') is-invalid @enderror" name="check_out" value="{{ old('check_out') }}" required>
                        @error('check_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="text" class="form-control date @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="present">Present</option>
                            <option value="absen">Absen</option>
                            <option value="leave">Leave</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Create Presence</button>
                    <a href="{{ route('presences.index') }}" class="btn btn-secondary">Back to Presence List</a>

                </form>

                @else
                    
                <form action="{{ route('presences.store') }}" method="POST">
                    @csrf
                    <div class="alert alert-light-warning color-warning mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Note:</strong> Mohon izinkan akses <strong>lokasi</strong> & <strong>kamera</strong> sebelum melakukan presensi.
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-4">

                                <div class="mb-3">
                                        <label for="check_in_lat" class="form-label">Latitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo"></i></span>
                                            <input type="text" class="form-control" name="check_in_lat" id="check_in_lat"
                                                placeholder="Mendapatkan lokasi..." readonly required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="check_in_long" class="form-label">Longitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo"></i></span>
                                            <input type="text" class="form-control" name="check_in_long" id="check_in_long"
                                                placeholder="Mendapatkan lokasi..." readonly required>
                                        </div>
                                    </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-camera me-2"></i>Foto Selfie
                                    </h6>

                                    <div id="camera-wrapper">
                                        <video id="camera-stream" autoplay
                                            class="rounded border d-block w-100 mb-2"
                                            style="aspect-ratio: 4/3; object-fit: cover;">
                                        </video>
                                        <canvas id="canvas" class="d-none"></canvas>
                                    </div>

                                    <div id="preview-wrapper" class="d-none mb-2">
                                        <img id="photo-preview" src="" alt="Preview Selfie"
                                            class="rounded border w-100"
                                            style="aspect-ratio: 4/3; object-fit: cover;">
                                    </div>

                                    <div class="d-flex gap-2 mt-2">
                                        <button type="button" class="btn btn-info" id="btn-capture">
                                            <i class="bi bi-camera me-1"></i> Ambil Foto
                                        </button>
                                        <button type="button" class="btn btn-warning d-none" id="btn-retake">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Ulangi
                                        </button>
                                    </div>

                                    <input type="hidden" name="photo_check_in" id="photo-input">
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>Lokasi Anda
                                    </h6>

                                    <div class="ratio ratio-4x3">
                                        <iframe id="map-frame" src="" frameborder="0"
                                            scrolling="no" marginheight="0" marginwidth="0"
                                            class="rounded border">
                                        </iframe>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg px-5" id="btn-present" disabled>
                            <i class="bi bi-check-circle me-1"></i> Presensi
                        </button>
                    </div>
                </form>

                @endif
            </div>
        </div>
    </section>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const officeLat = -7.570312354872347;
    const officeLon = 110.80334127983431;
    const threshold = 0.01;

    let locationValid = false;
    let photoTaken = false;

    function checkReady() {
        if (locationValid && photoTaken) {
            document.getElementById('btn-present').removeAttribute('disabled');
        }
    }

    // === GEOLOCATION ===
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            document.getElementById('map-frame').src = `https://www.google.com/maps?q=${lat},${lon}&output=embed`;
            document.getElementById('check_in_lat').value = lat;
            document.getElementById('check_in_long').value = lon;

            const distance = Math.sqrt(
                Math.pow(lat - officeLat, 2) +
                Math.pow(lon - officeLon, 2)
            );

            if (distance <= threshold) {
                alert('Kamu berada di kantor');
                locationValid = true;
                checkReady();
            } else {
                alert('Kamu tidak berada di kantor');
            }
        }, function(err) {
            alert('Gagal mendapatkan lokasi: ' + err.message);
        });
    } else {
        alert('Browser tidak mendukung geolocation');
    }

    // === KAMERA ===
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('canvas');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const photoPreview = document.getElementById('photo-preview');
    const photoInput = document.getElementById('photo-input');
    const cameraWrapper = document.getElementById('camera-wrapper');
    const previewWrapper = document.getElementById('preview-wrapper');

    // Nyalakan kamera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(err) {
                alert('Tidak bisa mengakses kamera: ' + err.message);
            });
    }

    // Ambil foto
    btnCapture.addEventListener('click', function () {
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');

        photoInput.value = dataURL;
        photoPreview.src = dataURL;

        cameraWrapper.classList.add('d-none');
        previewWrapper.classList.remove('d-none');
        btnCapture.classList.add('d-none');
        btnRetake.classList.remove('d-none');

        photoTaken = true;
        checkReady();
    });

    // Ulangi foto
    btnRetake.addEventListener('click', function () {
        photoInput.value = '';
        photoPreview.src = '';

        cameraWrapper.classList.remove('d-none');
        previewWrapper.classList.add('d-none');
        btnCapture.classList.remove('d-none');
        btnRetake.classList.add('d-none');

        photoTaken = false;
        document.getElementById('btn-present').setAttribute('disabled', true);
    });

});
</script>


@endsection