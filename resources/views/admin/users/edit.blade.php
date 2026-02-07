@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-person-gear text-primary"></i> Edit User</h2>
    <p class="text-muted">Ubah informasi user</p>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil"></i> Form Edit User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
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

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                       placeholder="Masukkan email" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">
                    Password Baru <span class="text-muted">(Opsional)</span>
                </label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" 
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password</small>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">
                    Konfirmasi Password Baru
                </label>
                <div class="input-group">
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" 
                           placeholder="Ulangi password baru">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                        <i class="bi bi-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label fw-bold">
                    Role <span class="text-danger">*</span>
                    @if($user->role === 'admin')
                        <span class="badge bg-danger ms-2">Default</span>
                    @endif
                </label>
                <select class="form-select @error('role') is-invalid @enderror" 
                        id="role" name="role" required
                        @if($user->role === 'admin') disabled @endif>
                    <option value="">-- Pilih Role --</option>
                    @if($user->role === 'admin')
                        {{-- Admin tidak bisa diubah --}}
                        <option value="admin" selected>Admin</option>
                    @else
                        {{-- Petugas dan User tidak bisa menjadi admin (karena sudah ada admin di divisi) --}}
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    @endif
                </select>
                @if($user->role === 'admin')
                    <input type="hidden" name="role" value="admin">
                @endif
                @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    @if($user->role === 'admin')
                        Status role admin sudah default dan tidak bisa diubah.
                    @else
                        Petugas dan User dapat saling diubah. Admin tidak dapat dipilih karena sudah ada admin di divisi ini.
                    @endif
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
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
    // Prevent role change for admin users
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const form = document.getElementById('editUserForm');
        const userRole = '{{ $user->role }}';
        
        // Create notification element
        function showNotification(message) {
            // Remove existing notification if any
            const existingAlert = document.querySelector('.role-change-alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show role-change-alert';
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert before form
            const cardBody = form.closest('.card-body');
            cardBody.insertBefore(alertDiv, form);
            
            // Auto remove after 5 seconds
            setTimeout(function() {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        if (userRole === 'admin' && roleSelect) {
            // Admin tidak bisa diubah
            roleSelect.addEventListener('change', function(e) {
                if (e.target.value !== 'admin') {
                    e.preventDefault();
                    e.target.value = 'admin';
                    showNotification('Status role admin sudah default dan tidak bisa diubah.');
                }
            });
            
            // Also prevent form submission if role is changed
            form.addEventListener('submit', function(e) {
                const selectedRole = roleSelect.value;
                if (selectedRole !== 'admin') {
                    e.preventDefault();
                    showNotification('Status role admin sudah default dan tidak bisa diubah.');
                    roleSelect.value = 'admin';
                    return false;
                }
            });
        } else if (roleSelect) {
            // Petugas dan User tidak bisa menjadi admin
            roleSelect.addEventListener('change', function(e) {
                if (e.target.value === 'admin') {
                    e.preventDefault();
                    // Kembalikan ke role sebelumnya
                    e.target.value = userRole;
                    showNotification('Tidak dapat mengubah role menjadi admin. Admin sudah ada di divisi ini.');
                }
            });
            
            // Also prevent form submission if trying to become admin
            form.addEventListener('submit', function(e) {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'admin') {
                    e.preventDefault();
                    showNotification('Tidak dapat mengubah role menjadi admin. Admin sudah ada di divisi ini.');
                    roleSelect.value = userRole;
                    return false;
                }
            });
        }
    });

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
