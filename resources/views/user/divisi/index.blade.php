@extends('layouts.app')

@section('title', 'Daftar Divisi')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold"><i class="bi bi-building text-primary"></i> Daftar Divisi</h2>
            <p class="text-muted mb-0">Kelola divisi/kantor dalam sistem</p>
        </div>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'user')
        <a href="{{ route('divisions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Divisi
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Divisi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Divisi</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($divisions as $index => $division)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building text-primary me-2"></i>
                                <strong>{{ $division->name }}</strong>
                            </div>
                        </td>
                        <td>
                            <i class="bi bi-person text-muted"></i> 
                            @if($division->creator)
                                {{ $division->creator->name }}/<span class="text-capitalize">{{ $division->creator->role }}</span>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-calendar3 text-muted"></i> {{ $division->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('divisions.show', $division) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('divisions.edit', $division) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-building" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Tidak ada divisi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

