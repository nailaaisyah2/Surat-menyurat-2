@extends('layouts.app')

@section('title', 'Buat Surat')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-envelope-plus text-primary"></i> Buat Surat Baru</h2>
    <p class="text-muted">Kirim surat untuk pertemuan individu atau rapat kantor ke divisi lain</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-file-text"></i> Form Surat</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('surat_masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="penerima_division_id" class="form-label fw-bold">
                        Tujuan Divisi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('penerima_division_id') is-invalid @enderror" 
                            id="penerima_division_id" name="penerima_division_id" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ old('penerima_division_id') == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('penerima_division_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jenis" class="form-label fw-bold">
                        Jenis Surat <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pertemuan_individu" {{ old('jenis') == 'pertemuan_individu' ? 'selected' : '' }}>
                            Pertemuan Individu
                        </option>
                        <option value="rapat_kantor" {{ old('jenis') == 'rapat_kantor' ? 'selected' : '' }}>
                            Rapat Kantor
                        </option>
                    </select>
                    @error('jenis')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Form Pilih Nama (muncul jika jenis = pertemuan_individu) -->
            <div class="mb-3" id="pilih-nama-container" style="display: none;">
                <label for="penerima_user_id" class="form-label fw-bold">
                    Pilih Nama Penerima <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('penerima_user_id') is-invalid @enderror" 
                        id="penerima_user_id" name="penerima_user_id">
                    <option value="">-- Pilih Nama --</option>
                </select>
                <small class="form-text text-muted">
                    <i class="bi bi-info-circle"></i> Pilih nama penerima dari divisi yang dipilih (termasuk admin, petugas, dan user)
                </small>
                @error('penerima_user_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="judul" class="form-label fw-bold">
                    Judul Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                       id="judul" name="judul" value="{{ old('judul') }}" 
                       placeholder="Masukkan judul surat" required>
                @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="isi" class="form-label fw-bold">
                    Isi Surat <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('isi') is-invalid @enderror" 
                          id="isi" name="isi" rows="6" 
                          placeholder="Tuliskan isi surat Anda di sini..." required>{{ old('isi') }}</textarea>
                @error('isi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_pertemuan" class="form-label fw-bold">
                        Tanggal Pertemuan <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('tanggal_pertemuan') is-invalid @enderror" 
                           id="tanggal_pertemuan" name="tanggal_pertemuan" 
                           value="{{ old('tanggal_pertemuan') }}" min="{{ date('Y-m-d') }}" required>
                    @error('tanggal_pertemuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jam_pertemuan" class="form-label fw-bold">
                        Jam Pertemuan <span class="text-danger">*</span>
                    </label>
                    <input type="time" class="form-control @error('jam_pertemuan') is-invalid @enderror" 
                           id="jam_pertemuan" name="jam_pertemuan" value="{{ old('jam_pertemuan') }}" required>
                    @error('jam_pertemuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="lampiran" class="form-label fw-bold">
                    Lampiran <small class="text-muted">(Opsional)</small>
                </label>
                <input type="file" class="form-control @error('lampiran') is-invalid @enderror @error('lampiran.*') is-invalid @enderror" 
                       id="lampiran" name="lampiran[]" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="form-text text-muted">
                    <i class="bi bi-info-circle"></i> Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 5MB)
                </small>
                @error('lampiran')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('lampiran.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Kirim Surat
                </button>
                <form action="{{ route('petugas.surat_masuk.tab') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="tab" value="keluar">
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                </form>
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tujuanDivisi = document.getElementById('penerima_division_id');
        const jenisSurat = document.getElementById('jenis');
        const pilihNamaContainer = document.getElementById('pilih-nama-container');
        const penerimaUserId = document.getElementById('penerima_user_id');

        function loadUsersByDivision(divisionId) {
            if (!divisionId) {
                penerimaUserId.innerHTML = '<option value="">-- Pilih Nama --</option>';
                pilihNamaContainer.style.display = 'none';
                return;
            }

            // Show loading state
            penerimaUserId.innerHTML = '<option value="">Memuat data...</option>';
            penerimaUserId.disabled = true;

            fetch(`/api/users-by-division/${divisionId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(users => {
                    penerimaUserId.innerHTML = '<option value="">-- Pilih Nama --</option>';
                    
                    if (users.length === 0) {
                        penerimaUserId.innerHTML = '<option value="">Tidak ada user di divisi ini</option>';
                        penerimaUserId.disabled = true;
                        return;
                    }

                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = `${user.name} (${user.email}) - ${user.role}`;
                        @if(old('penerima_user_id'))
                            if ({{ old('penerima_user_id') }} == user.id) {
                                option.selected = true;
                            }
                        @endif
                        penerimaUserId.appendChild(option);
                    });

                    penerimaUserId.disabled = false;

                    // Show container if jenis is pertemuan_individu
                    if (jenisSurat.value === 'pertemuan_individu') {
                        pilihNamaContainer.style.display = 'block';
                        penerimaUserId.required = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                    penerimaUserId.innerHTML = '<option value="">Error: Gagal memuat data user</option>';
                    penerimaUserId.disabled = false;
                });
        }

        function togglePilihNama() {
            const isPertemuanIndividu = jenisSurat.value === 'pertemuan_individu';
            const hasDivision = tujuanDivisi.value && tujuanDivisi.value !== '';

            if (isPertemuanIndividu && hasDivision) {
                pilihNamaContainer.style.display = 'block';
                penerimaUserId.required = true;
                loadUsersByDivision(tujuanDivisi.value);
            } else {
                pilihNamaContainer.style.display = 'none';
                penerimaUserId.required = false;
                penerimaUserId.value = '';
                penerimaUserId.innerHTML = '<option value="">-- Pilih Nama --</option>';
            }
        }

        // Event listener untuk perubahan divisi
        tujuanDivisi.addEventListener('change', function() {
            console.log('Divisi changed:', this.value);
            togglePilihNama();
        });

        // Event listener untuk perubahan jenis surat
        jenisSurat.addEventListener('change', function() {
            console.log('Jenis surat changed:', this.value);
            togglePilihNama();
        });

        // Initialize pada saat halaman dimuat
        // Jika sudah ada old value, load users
        @if(old('jenis') == 'pertemuan_individu' && old('penerima_division_id'))
            if (tujuanDivisi.value) {
                togglePilihNama();
            }
        @endif
    });
</script>
@endpush

