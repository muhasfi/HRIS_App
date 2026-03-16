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

                        <div class="mb-3"><b>Note</b> : Mohon izinkan akses lokasi & kamera</div>

                        <div class="mb-3">
                            <label for="check_in_lat" class="form-label">Latitude</label>
                            <input type="text" class="form-control" name="check_in_lat" id="check_in_lat" required>
                        </div>

                        <div class="mb-3">
                            <label for="check_in_long" class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="check_in_long" id="check_in_long" required>
                        </div>

                        <div class="mb-3">
                            <iframe width="500" height="300" src="" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Selfie</label>
                            <div>
                                <video id="camera-stream" width="320" height="240" autoplay style="border:1px solid #ccc; border-radius:8px;"></video>
                                <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-info" id="btn-capture">📷 Ambil Foto</button>
                                <button type="button" class="btn btn-warning" id="btn-retake" style="display:none;">🔄 Ulangi</button>
                            </div>
                            <div class="mt-2">
                                <img id="photo-preview" src="" alt="Preview" style="display:none; width:320px; border-radius:8px; border:1px solid #ccc;">
                            </div>
                            {{-- Foto disimpan sebagai base64 --}}
                            <input type="hidden" name="photo_check_in" id="photo-input">
                        </div>

                        <button type="submit" class="btn btn-primary" id="btn-present" disabled>Presence</button>

                    </form>

                @endif
            </div>
        </div>
    </section>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const iframe = document.querySelector('iframe');
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

            iframe.src = `https://www.google.com/maps?q=${lat},${lon}&output=embed`;
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
        });
    }

    // === KAMERA ===
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('canvas');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const photoPreview = document.getElementById('photo-preview');
    const photoInput = document.getElementById('photo-input');

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
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');

        photoInput.value = dataURL;
        photoPreview.src = dataURL;
        photoPreview.style.display = 'block';

        video.style.display = 'none';
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-block';

        photoTaken = true;
        checkReady();
    });

    // Ulangi foto
    btnRetake.addEventListener('click', function () {
        photoInput.value = '';
        photoPreview.src = '';
        photoPreview.style.display = 'none';

        video.style.display = 'block';
        btnCapture.style.display = 'inline-block';
        btnRetake.style.display = 'none';

        photoTaken = false;
        document.getElementById('btn-present').setAttribute('disabled', true);
    });

});
</script>


@endsection