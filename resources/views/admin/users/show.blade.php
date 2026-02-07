@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h2 class="fw-bold"><i class="bi bi-person-badge text-primary"></i> Detail Pengguna</h2>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Profil</h5>
                <span class="badge 
                    @if($user->role === 'admin') bg-danger
                    @elseif($user->role === 'petugas') bg-warning
                    @else bg-info text-dark
                    @endif">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($user->profile_image)
                        <img src="{{ asset($user->profile_image) }}" 
                             alt="Foto Profil" 
                             class="rounded-circle border border-primary border-3 mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle border border-primary border-3 mb-3" 
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted fw-semibold small">Nama Lengkap</label>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle text-primary"></i>
                            <span class="fw-bold">{{ $user->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted fw-semibold small">Email</label>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope text-primary"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted fw-semibold small">Divisi</label>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-building text-primary"></i>
                            <span>{{ $user->division->name ?? 'Tidak ada divisi' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted fw-semibold small">Status Akun</label>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary"></i>
                            <span class="badge 
                                @if($user->status === 'approved') bg-success
                                @elseif($user->status === 'pending') bg-warning
                                @else bg-danger
                                @endif">
                                {{ ucfirst($user->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info d-flex justify-content-between flex-wrap">
                    <div class="mb-2">
                        <small class="text-muted d-block">Bergabung</small>
                        <strong><i class="bi bi-calendar3"></i> {{ $user->created_at->format('d F Y') }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Terakhir Diperbarui</small>
                        <strong><i class="bi bi-clock-history"></i> {{ $user->updated_at->format('d F Y H:i') }}</strong>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-envelope"></i> Aktivitas Surat</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="text-primary fw-bold" style="font-size: 1.8rem;">
                                {{ $user->lettersSent->count() }}
                            </div>
                            <small class="text-muted">Surat Dibuat</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="text-success fw-bold" style="font-size: 1.8rem;">
                                {{ $user->lettersResponded->count() }}
                            </div>
                            <small class="text-muted">Surat Ditanggapi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                @if($user->lettersSent->count() > 0)
                <div class="list-group">
                    @foreach($user->lettersSent->take(5) as $letter)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ Str::limit($letter->judul, 40) }}</strong>
                            <small>{{ $letter->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted">Jenis: {{ str_replace('_', ' ', $letter->jenis) }}</small><br>
                        <small>Status: 
                            <span class="badge 
                                @if($letter->status === 'pending') bg-warning
                                @elseif($letter->status === 'diterima') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($letter->status) }}
                            </span>
                        </small>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">Belum ada aktivitas surat.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

