@extends('layouts.app')

@section('title', 'Detail Divisi')

@section('content')
<div class="mb-4">
    <a href="{{ route('divisions.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h2 class="fw-bold"><i class="bi bi-building text-primary"></i> Detail Divisi</h2>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Divisi</h4>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-building text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Divisi</small>
                                <strong class="fs-5">{{ $division->name }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-person-check text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dibuat Oleh</small>
                                <strong class="fs-5">
                                    @if($division->creator)
                                        {{ $division->creator->name }}/<span class="text-capitalize">{{ $division->creator->role }}</span>
                                    @else
                                        System
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <i class="bi bi-calendar3"></i> <strong>Tanggal Dibuat:</strong><br>
                            <span class="ms-4">{{ $division->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="bi bi-clock"></i> <strong>Waktu Dibuat:</strong><br>
                            <span class="ms-4">{{ $division->created_at->format('H:i:s') }} WIB</span>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-clock-history"></i> <strong>Terakhir Diupdate:</strong><br>
                            <span class="ms-4">{{ $division->updated_at->format('d F Y H:i:s') }} WIB</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-0">{{ $division->users->count() }}</h3>
                                <p class="text-muted mb-0">Total Anggota</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-envelope text-success" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-0">{{ $division->letters->count() }}</h3>
                                <p class="text-muted mb-0">Total Surat</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="d-flex gap-2">
                    <a href="{{ route('divisions.edit', $division) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Divisi
                    </a>
                    <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus Divisi
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Anggota Divisi</h5>
            </div>
            <div class="card-body">
                @if($division->users->count() > 0)
                <div class="list-group">
                    @foreach($division->users as $user)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person-circle text-primary me-2"></i>
                            <strong>{{ $user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <span class="badge 
                            @if($user->role === 'admin') bg-danger
                            @elseif($user->role === 'petugas') bg-warning
                            @else bg-info
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">Belum ada anggota</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-envelope"></i> Surat Terbaru</h5>
            </div>
            <div class="card-body">
                @if($division->letters->count() > 0)
                <div class="list-group">
                    @foreach($division->letters->take(5) as $letter)
                    <a href="{{ route('surat_masuk.show', $letter) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ Str::limit($letter->judul, 30) }}</h6>
                            <small>{{ $letter->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small text-muted">{{ Str::limit($letter->isi, 50) }}</p>
                        <small>
                            @if($letter->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                            @elseif($letter->status === 'diterima')
                            <span class="badge bg-success">Diterima</span>
                            @else
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </small>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">Belum ada surat</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

