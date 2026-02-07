@extends('layouts.app')

@section('title', 'Menunggu Persetujuan')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold"><i class="bi bi-hourglass-split text-primary"></i> User Menunggu Persetujuan</h2>
            <p class="text-muted mb-0">User dari divisi Anda yang menunggu persetujuan</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar User Pending</h5>
    </div>
    <div class="card-body">
        @if($pendingUsers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Divisi</th>
                        <th>Tanggal Registrasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
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
                            <i class="bi bi-building text-muted"></i> 
                            {{ $user->division->name ?? 'Tidak ada divisi' }}
                        </td>
                        <td>
                            <i class="bi bi-calendar3 text-muted"></i> {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('users.approve', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menyetujui user {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                        <i class="bi bi-check-circle"></i> Setujui
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $user->id }}" title="Tolak">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('users.reject', $user) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Yakin ingin menolak user <strong>{{ $user->name }}</strong>?</p>
                                        <div class="mb-3">
                                            <label for="reason{{ $user->id }}" class="form-label">Alasan (Opsional)</label>
                                            <textarea class="form-control" id="reason{{ $user->id }}" name="reason" 
                                                      rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">Tidak Ada User Pending</h4>
            <p class="text-muted">Semua user dari divisi Anda sudah disetujui atau tidak ada yang menunggu persetujuan.</p>
        </div>
        @endif
    </div>
</div>
@endsection

