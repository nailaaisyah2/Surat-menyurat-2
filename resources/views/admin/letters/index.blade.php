@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold"><i class="bi bi-envelope text-primary"></i> Daftar Surat</h2>
            <p class="text-muted mb-0">
                @if($tab === 'keluar')
                    Surat keluar dari divisi {{ auth()->user()->division->name ?? 'Anda' }}
                @else
                    Surat masuk ke divisi {{ auth()->user()->division->name ?? 'Anda' }}
                @endif
            </p>
        </div>
        @if($tab === 'keluar')
        <a href="{{ route('surat_masuk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Surat Baru
        </a>
        @endif
    </div>
</div>

<!-- Tab Navigation -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <form action="{{ route('admin.surat_masuk.tab') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="tab" value="masuk">
            <button type="submit" class="nav-link {{ $tab === 'masuk' ? 'active' : '' }}" style="border: none; background: none; cursor: pointer;">
                <i class="bi bi-inbox"></i> Surat Masuk 
                <span class="badge bg-primary">{{ $suratMasuk->count() }}</span>
            </button>
        </form>
    </li>
    <li class="nav-item" role="presentation">
        <form action="{{ route('admin.surat_masuk.tab') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="tab" value="keluar">
            <button type="submit" class="nav-link {{ $tab === 'keluar' ? 'active' : '' }}" style="border: none; background: none; cursor: pointer;">
                <i class="bi bi-send"></i> Surat Keluar 
                <span class="badge bg-success">{{ $suratKeluar->count() }}</span>
            </button>
        </form>
    </li>
</ul>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-search"></i> Pencarian Surat</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('surat_masuk.index') }}" class="row g-3">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-md-3">
                <label for="search" class="form-label">Judul Surat</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Cari judul surat...">
            </div>
            <div class="col-md-2">
                <label for="jenis" class="form-label">Jenis Surat</label>
                <select class="form-select" id="jenis" name="jenis">
                    <option value="">Semua Jenis</option>
                    <option value="pertemuan_individu" {{ request('jenis') === 'pertemuan_individu' ? 'selected' : '' }}>Pertemuan Individu</option>
                    <option value="rapat_kantor" {{ request('jenis') === 'rapat_kantor' ? 'selected' : '' }}>Rapat Kantor</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari" 
                       value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-2">
                <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai" 
                       value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            @if(request()->hasAny(['search', 'jenis', 'status', 'tanggal_dari', 'tanggal_sampai']))
            <div class="col-12">
                <a href="{{ route('surat_masuk.index', ['tab' => $tab]) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul"></i> 
            @if($tab === 'keluar')
                Daftar Surat Keluar
            @else
                Daftar Surat Masuk
            @endif
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Jenis</th>
                        @if($tab === 'keluar')
                        <th>Tujuan Divisi</th>
                        @else
                        <th>Dari</th>
                        <th>Divisi Pengirim</th>
                        @endif
                        <th>Tanggal Pertemuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $index => $letter)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $letter->judul }}</strong></td>
                        <td>
                            @if($letter->jenis === 'pertemuan_individu')
                            <span class="badge bg-info"><i class="bi bi-person"></i> Pertemuan Individu</span>
                            @else
                            <span class="badge bg-primary"><i class="bi bi-people"></i> Rapat Kantor</span>
                            @endif
                        </td>
                        @if($tab === 'keluar')
                        <td>
                            <i class="bi bi-building text-muted"></i> {{ $letter->penerimaDivision->name }}
                        </td>
                        @else
                        <td>
                            <i class="bi bi-person text-muted"></i> {{ $letter->pengirim->name }}
                        </td>
                        <td>
                            <i class="bi bi-building text-muted"></i> {{ $letter->pengirim->division->name ?? '-' }}
                        </td>
                        @endif
                        <td>
                            <i class="bi bi-calendar3 text-muted"></i> {{ $letter->tanggal_pertemuan->format('d/m/Y') }}<br>
                            <small class="text-muted"><i class="bi bi-clock"></i> {{ $letter->jam_pertemuan }}</small>
                        </td>
                        <td>
                            @if($letter->status === 'pending')
                            <span class="badge bg-warning"><i class="bi bi-clock-history"></i> Pending</span>
                            @elseif($letter->status === 'diterima')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                            @else
                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('surat_masuk.show', $letter) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $tab === 'keluar' ? '7' : '8' }}" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-2">
                                @if($tab === 'keluar')
                                    Belum ada surat keluar dari divisi {{ auth()->user()->division->name ?? 'Anda' }}
                                @else
                                    Belum ada surat masuk ke divisi {{ auth()->user()->division->name ?? 'Anda' }}
                                @endif
                            </p>
                            @if($tab === 'keluar')
                            <a href="{{ route('surat_masuk.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Buat Surat Baru
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
