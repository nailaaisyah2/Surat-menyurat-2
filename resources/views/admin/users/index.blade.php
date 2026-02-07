@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold"><i class="bi bi-people text-primary"></i> Daftar User</h2>
            <p class="text-muted mb-0">Kelola pengguna dalam sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar User</h5>
    </div>
    <div class="card-body">
        <!-- Filter dan Search -->
        <form method="POST" action="{{ route('users.filter.apply') }}" class="mb-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" name="role_filter">
                        <option value="">-- Semua Role --</option>
                        <option value="admin" {{ ($filters['role_filter'] ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ ($filters['role_filter'] ?? '') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="user" {{ ($filters['role_filter'] ?? '') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Cari nama atau email...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Terapkan
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" action="{{ route('users.filter.reset') }}" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
            </button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Divisi</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                @endif
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>
                            <i class="bi bi-envelope text-muted"></i> {{ $user->email }}
                        </td>
                        <td>
                            @if($user->role === 'admin')
                            <span class="badge bg-danger"><i class="bi bi-shield-check"></i> Admin</span>
                            @elseif($user->role === 'petugas')
                            <span class="badge bg-warning"><i class="bi bi-person-badge"></i> Petugas</span>
                            @else
                            <span class="badge bg-info"><i class="bi bi-person"></i> User</span>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-building text-muted"></i> 
                            {{ $user->division->name ?? 'Tidak ada divisi' }}
                        </td>
                        <td>
                            <i class="bi bi-calendar3 text-muted"></i> {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Tidak ada user</p>
                            @if(isset($divisionId) && $divisionId)
                                <p class="text-muted small">Menampilkan user dari divisi Anda</p>
                            @else
                                <p class="text-muted small">Anda belum memiliki divisi. Silakan hubungi administrator untuk menetapkan divisi.</p>
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
