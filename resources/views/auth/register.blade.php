<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Surat Menyurat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <a href="{{ url('/') }}"
       class="btn btn-outline-secondary position-fixed top-0 start-0 m-3 shadow-sm"
       style="z-index: 1050;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                            <h2 class="mt-3 mb-1">Daftar Akun Baru</h2>
                            <p class="text-muted">Buat akun untuk mulai menggunakan sistem</p>
                        </div>

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
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
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Divisi</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="division_option" 
                                           id="existing_division" value="existing" checked onchange="toggleDivisionInput()">
                                    <label class="form-check-label" for="existing_division">
                                        Pilih Divisi yang Ada
                                    </label>
                                </div>
                                <select class="form-select" id="division_id" name="division_id" onchange="toggleDivisionInput()">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                    @endforeach
                                </select>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="division_option" 
                                           id="new_division" value="new" onchange="toggleDivisionInput()">
                                    <label class="form-check-label" for="new_division">
                                        Buat Divisi Baru
                                    </label>
                                </div>
                                <input type="text" class="form-control mt-2 d-none" id="new_division_input" 
                                       name="new_division" placeholder="Nama Divisi Baru" value="{{ old('new_division') }}">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-person-plus"></i> Daftar
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDivisionInput() {
            const existingRadio = document.getElementById('existing_division');
            const newRadio = document.getElementById('new_division');
            const divisionSelect = document.getElementById('division_id');
            const newDivisionInput = document.getElementById('new_division_input');

            if (newRadio.checked) {
                divisionSelect.classList.add('d-none');
                divisionSelect.removeAttribute('required');
                newDivisionInput.classList.remove('d-none');
                newDivisionInput.setAttribute('required', 'required');
            } else {
                divisionSelect.classList.remove('d-none');
                divisionSelect.setAttribute('required', 'required');
                newDivisionInput.classList.add('d-none');
                newDivisionInput.removeAttribute('required');
            }
        }

        // Initialize on page load
        toggleDivisionInput();

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

        // Toggle password confirmation visibility
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirm = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

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
    </script>
</body>
</html>

