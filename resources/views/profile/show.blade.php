@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-person-gear text-primary"></i> Profil Saya</h2>
    <p class="text-muted">Kelola informasi profil dan akun Anda</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <label for="profile_image" class="form-label fw-bold d-block">
                            Foto Profil
                        </label>
                        <div class="mb-3">
                            @if($user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" 
                                     alt="Foto Profil" 
                                     id="profile_image_preview"
                                     class="rounded-circle border border-primary border-3"
                                     style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                     onclick="document.getElementById('profile_image').click()">
                            @else
                                <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle border border-primary border-3"
                                     id="profile_image_preview"
                                     style="width: 150px; height: 150px; cursor: pointer;"
                                     onclick="document.getElementById('profile_image').click()">
                                    <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                                </div>
                            @endif
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   placeholder="Masukkan nama lengkap" required autofocus>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   placeholder="contoh@email.com" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold d-block">
                                Password Baru
                            </label>
                            <small class="text-muted d-block mb-2">(Kosongkan jika tidak ingin mengubah)</small>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Minimal 8 karakter">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-bold d-block">
                                Konfirmasi Password Baru
                            </label>
                            <small class="text-muted d-block mb-2" style="visibility: hidden;">Placeholder</small>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="division_id" class="form-label fw-bold">Divisi</label>
                        <select class="form-select @error('division_id') is-invalid @enderror" 
                                id="division_id" name="division_id">
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisions as $division)
                            <option value="{{ $division->id }}" 
                                    {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('division_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($user->profile_image)
                        <img src="{{ asset($user->profile_image) }}" 
                             alt="Foto Profil" 
                             class="rounded-circle border border-primary border-2 mb-3"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    @if($user->role === 'admin')
                    <span class="badge bg-danger"><i class="bi bi-shield-check"></i> Admin</span>
                    @elseif($user->role === 'petugas')
                    <span class="badge bg-warning"><i class="bi bi-person-badge"></i> Petugas</span>
                    @else
                    <span class="badge bg-info"><i class="bi bi-person"></i> User</span>
                    @endif
                </div>

                <hr>

                <div class="mb-3">
                    <label class="text-muted small">Divisi</label>
                    <div>
                        <i class="bi bi-building text-primary"></i> 
                        <strong>{{ $user->division->name ?? 'Tidak ada divisi' }}</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Bergabung Sejak</label>
                    <div>
                        <i class="bi bi-calendar3 text-primary"></i> 
                        <strong>{{ $user->created_at->format('d F Y') }}</strong>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="text-muted small">Terakhir Diupdate</label>
                    <div>
                        <i class="bi bi-clock-history text-primary"></i> 
                        <strong>{{ $user->updated_at->format('d F Y H:i') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-shield-check text-success"></i> Keamanan</h6>
                <p class="card-text small text-muted">
                    Pastikan password Anda kuat dan tidak mudah ditebak. Gunakan kombinasi huruf, angka, dan karakter khusus.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview profile image
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profile_image_preview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'profile_image_preview';
                    img.src = e.target.result;
                    img.className = 'rounded-circle border border-primary border-3';
                    img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; cursor: pointer;';
                    img.onclick = function() {
                        document.getElementById('profile_image').click();
                    };
                    preview.parentNode.replaceChild(img, preview);
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

