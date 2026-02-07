@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="mb-4">
    <a href="{{ route('surat_masuk.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h2 class="fw-bold"><i class="bi bi-envelope-paper text-primary"></i> Detail Surat</h2>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-file-text"></i> Informasi Surat</h4>
            </div>
            <div class="card-body p-4">
                <!-- Header Info -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-person-circle text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Pembuat Surat</small>
                                <strong class="fs-5">{{ $letter->pengirim->name }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-building text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dari Divisi</small>
                                <strong class="fs-5">{{ $letter->pengirim->division->name ?? 'Tidak ada divisi' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanggal & Waktu Dibuat -->
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <i class="bi bi-calendar3"></i> <strong>Tanggal Dibuat:</strong><br>
                            <span class="ms-4">{{ $letter->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="bi bi-clock"></i> <strong>Waktu Dibuat:</strong><br>
                            <span class="ms-4">{{ $letter->created_at->format('H:i:s') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Surat -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-info-circle text-primary"></i> Informasi Surat
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="text-muted small">Jenis Surat</label>
                                <div>
                                    @if($letter->jenis === 'pertemuan_individu')
                                    <span class="badge bg-info fs-6"><i class="bi bi-person"></i> Pertemuan Individu</span>
                                    @else
                                    <span class="badge bg-primary fs-6"><i class="bi bi-people"></i> Rapat Kantor</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="text-muted small">Tujuan Divisi</label>
                                <div><strong>{{ $letter->penerimaDivision->name }}</strong></div>
                            </div>
                        </div>
                        @if($letter->jenis === 'pertemuan_individu' && $letter->penerimaUser)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="text-muted small">Penerima (Pertemuan Individu)</label>
                                <div>
                                    <strong><i class="bi bi-person-check"></i> {{ $letter->penerimaUser->name }}</strong><br>
                                    <small class="text-muted">{{ $letter->penerimaUser->email }} - {{ ucfirst($letter->penerimaUser->role) }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="text-muted small">Tanggal Pertemuan</label>
                                <div><strong><i class="bi bi-calendar3"></i> {{ $letter->tanggal_pertemuan->format('d F Y') }}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="text-muted small">Jam Pertemuan</label>
                                <div><strong><i class="bi bi-clock"></i> {{ $letter->jam_pertemuan }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Judul & Isi -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-file-text text-primary"></i> Isi Surat
                    </h5>
                    <h4 class="mb-3">{{ $letter->judul }}</h4>
                    <div class="bg-light p-4 rounded border-start border-primary border-4">
                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8;">{{ $letter->isi }}</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-flag text-primary"></i> Status
                    </h5>
                    <div>
                        @if($letter->status === 'pending')
                        <span class="badge bg-warning fs-6 px-4 py-2">
                            <i class="bi bi-clock-history"></i> Menunggu - Belum Ditanggapi
                        </span>
                        @elseif($letter->status === 'diterima')
                        <span class="badge bg-success fs-6 px-4 py-2">
                            <i class="bi bi-check-circle"></i> Diterima
                        </span>
                        @else
                        <span class="badge bg-danger fs-6 px-4 py-2">
                            <i class="bi bi-x-circle"></i> Ditolak
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Tanggapan (jika sudah ditanggapi) -->
                @if($letter->status !== 'pending' && $letter->responded_by)
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-chat-left-text text-primary"></i> Tanggapan
                    </h5>
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-check text-primary me-2"></i>
                                        <div>
                                            <label class="text-muted small">Ditanggapi Oleh</label>
                                            <div><strong>{{ $letter->responder->name }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-success me-2"></i>
                                        <div>
                                            <label class="text-muted small">Divisi</label>
                                            <div><strong>{{ $letter->responder->division->name ?? '-' }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 text-info me-2"></i>
                                        <div>
                                            <label class="text-muted small">Tanggal Ditanggapi</label>
                                            <div><strong>{{ $letter->responded_at->format('d F Y') }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-info me-2"></i>
                                        <div>
                                            <label class="text-muted small">Waktu Ditanggapi</label>
                                            <div><strong>{{ $letter->responded_at->format('H:i:s') }} WIB</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-muted small">Catatan</label>
                                <div class="bg-white p-3 rounded border mt-2">
                                    <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8;">{{ $letter->catatan_petugas }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lampiran -->
                @php
                    $lampiranList = $letter->lampiran ?? [];
                @endphp
                @if(count($lampiranList) > 0)
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-paperclip text-primary"></i> Lampiran
                    </h5>
                    <div class="row g-3">
                        @foreach($lampiranList as $index => $lampiranPath)
                            @php
                                $extension = strtolower(pathinfo($lampiranPath, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $cleanPath = str_replace('storage/', '', $lampiranPath);
                                $lampiranUrl = asset('storage/' . $cleanPath);
                            @endphp
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Lampiran {{ $loop->iteration }}</strong>
                                        <span class="badge bg-light text-dark">{{ strtoupper($extension) }}</span>
                                    </div>
                                    @if($isImage)
                                        <img src="{{ $lampiranUrl }}" alt="Lampiran" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 320px; object-fit: contain;">
                                    @else
                                        <div class="text-muted small mb-3">
                                            <i class="bi bi-file-earmark-text"></i> {{ basename($lampiranPath) }}
                                        </div>
                                    @endif
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('surat_masuk.download', ['letter' => $letter->id, 'file' => $index]) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                        @if($isImage)
                                            <a href="{{ $lampiranUrl }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-box-arrow-up-right"></i> Buka
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Lampiran Tanggapan (jika sudah ditanggapi) -->
                @php
                    $lampiranResponseList = $letter->lampiran_response ?? [];
                @endphp
                @if($letter->status !== 'pending' && count($lampiranResponseList) > 0)
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-paperclip text-success"></i> Lampiran Tanggapan
                    </h5>
                    <div class="row g-3">
                        @foreach($lampiranResponseList as $lampiranResponsePath)
                            @php
                                $extensionResponse = strtolower(pathinfo($lampiranResponsePath, PATHINFO_EXTENSION));
                                $isImageResponse = in_array($extensionResponse, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $cleanPathResponse = str_replace('storage/', '', $lampiranResponsePath);
                                $lampiranResponseUrl = asset('storage/' . $cleanPathResponse);
                            @endphp
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Lampiran {{ $loop->iteration }}</strong>
                                        <span class="badge bg-light text-dark">{{ strtoupper($extensionResponse) }}</span>
                                    </div>
                                    @if($isImageResponse)
                                        <img src="{{ $lampiranResponseUrl }}" alt="Lampiran Tanggapan" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 320px; object-fit: contain;">
                                    @else
                                        <div class="text-muted small mb-3">
                                            <i class="bi bi-file-earmark-text"></i> {{ basename($lampiranResponsePath) }}
                                        </div>
                                    @endif
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ $lampiranResponseUrl }}" download class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                        @if($isImageResponse)
                                            <a href="{{ $lampiranResponseUrl }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-box-arrow-up-right"></i> Buka
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Form Tanggapan (untuk user yang bisa respond) -->
                @php
                    $user = auth()->user();
                    $divisionId = $user->division_id;
                    $isSuratMasuk = $divisionId && $letter->penerima_division_id == $divisionId;
                    $isSuratIndividu = $letter->penerima_user_id == $user->id;
                    // User bisa respond jika surat masuk ke divisinya ATAU surat pertemuan individu yang ditujukan ke mereka, dan status masih pending
                    $canRespond = ($isSuratMasuk || $isSuratIndividu) && $letter->status === 'pending' && $letter->pengirim_id !== $user->id;
                @endphp
                @if($canRespond)
                <div class="mt-4 pt-4 border-top">
                    <h5 class="mb-3">
                        <i class="bi bi-reply text-primary"></i> Beri Tanggapan
                    </h5>
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <form action="{{ route('surat_masuk.respond', $letter) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-bold">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="diterima" {{ old('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="catatan_petugas" class="form-label fw-bold">
                                        Catatan/Komentar <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('catatan_petugas') is-invalid @enderror" 
                                              id="catatan_petugas" name="catatan_petugas" rows="4" 
                                              placeholder="Tuliskan catatan, komentar, atau alasan..." required>{{ old('catatan_petugas') }}</textarea>
                                    @error('catatan_petugas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="lampiran_response" class="form-label fw-bold">
                                        Lampiran Tanggapan <small class="text-muted">(Opsional)</small>
                                    </label>
                                    <input type="file" class="form-control @error('lampiran_response') is-invalid @enderror @error('lampiran_response.*') is-invalid @enderror" 
                                           id="lampiran_response" name="lampiran_response[]" multiple
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle"></i> Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal: 5MB per file)
                                    </small>
                                    @error('lampiran_response')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('lampiran_response.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Kirim Tanggapan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

