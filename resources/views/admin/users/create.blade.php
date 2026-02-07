@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-person-plus text-primary"></i> Tambah User</h2>
    <p class="text-muted">Buat user baru untuk divisi apapun (termasuk divisi lain)</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 text-center">
                <label for="profile_image" class="form-label fw-bold d-block mb-3">
                    Foto Profil <span class="text-muted">(Opsional)</span>
                </label>
                <div class="mb-3">
                    <div class="d-inline-block position-relative">
                        <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle border border-primary border-3"
                             id="profile_image_preview"
                             style="width: 120px; height: 120px; cursor: pointer; transition: all 0.3s ease;"
                             onclick="document.getElementById('profile_image').click()">
                            <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" 
                             style="cursor: pointer; transform: translate(25%, 25%);"
                             onclick="document.getElementById('profile_image').click()">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                    </div>
                </div>
                <input type="file" 
                       class="form-control @error('profile_image') is-invalid @enderror" 
                       id="profile_image" 
                       name="profile_image" 
                       accept="image/jpeg,image/jpg,image/png,image/gif"
                       style="display: none;"
                       onchange="previewProfileImage(this)">
                <small class="form-text text-muted d-block">
                    <i class="bi bi-info-circle"></i> Format: JPEG, JPG, PNG, GIF (Maksimal: 2MB)
                </small>
                @error('profile_image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" 
                       placeholder="Masukkan nama lengkap" required autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="Masukkan email" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">
                    Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" 
                           placeholder="Minimal 8 karakter" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">
                    Konfirmasi Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" 
                           placeholder="Ulangi password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                        <i class="bi bi-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="division_id" class="form-label fw-bold">
                    Divisi <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('division_id') is-invalid @enderror" 
                        id="division_id" name="division_id" required>
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->id }}" 
                            {{ old('division_id', auth()->user()->division_id) == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                        @if($division->id == auth()->user()->division_id)
                            <span class="text-muted">(Divisi Saya)</span>
                        @endif
                    </option>
                    @endforeach
                </select>
                @error('division_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Pilih divisi untuk user ini. Bisa divisi Anda sendiri atau divisi lain.</small>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label fw-bold">
                    Role <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('role') is-invalid @enderror" 
                        id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Pilih role untuk user. Admin bisa membuat admin untuk divisi lain.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview profile image
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById('profile_image_preview').parentElement;
                const preview = document.getElementById('profile_image_preview');
                
                // Check if already an image
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'profile_image_preview';
                    img.src = e.target.result;
                    img.alt = 'Preview Foto Profil';
                    img.className = 'rounded-circle border border-primary border-3';
                    img.style.cssText = 'width: 120px; height: 120px; object-fit: cover; cursor: pointer; transition: all 0.3s ease;';
                    img.onclick = function() {
                        document.getElementById('profile_image').click();
                    };
                    
                    // Keep the camera icon
                    const cameraIcon = previewContainer.querySelector('.position-absolute');
                    previewContainer.replaceChild(img, preview);
                    if (cameraIcon) {
                        previewContainer.appendChild(cameraIcon);
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && password && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if (type === 'password') {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            } else {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }
        });
    }

    // Toggle password confirmation visibility
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirm = document.getElementById('password_confirmation');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    if (togglePasswordConfirm && passwordConfirm && eyeIconConfirm) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            
            if (type === 'password') {
                eyeIconConfirm.classList.remove('bi-eye-slash');
                eyeIconConfirm.classList.add('bi-eye');
            } else {
                eyeIconConfirm.classList.remove('bi-eye');
                eyeIconConfirm.classList.add('bi-eye-slash');
            }
        });
    }
</script>
@endpush
@endsection
