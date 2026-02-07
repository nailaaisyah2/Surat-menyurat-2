@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-clock-history text-primary"></i> Log Aktivitas</h2>
    <p class="text-muted">Riwayat aktivitas user, petugas, dan admin di divisi {{ auth()->user()->division->name ?? 'Anda' }}</p>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('activity_logs.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="action" class="form-label fw-bold">Aksi</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Semua Aksi</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Membuat</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Mengupdate</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Menghapus</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="respond" {{ request('action') == 'respond' ? 'selected' : '' }}>Menanggapi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label fw-bold">User</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label fw-bold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label fw-bold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </div>
            @if(request()->hasAny(['action', 'user_id', 'date_from', 'date_to']))
            <div class="mt-3">
                <a href="{{ route('activity_logs.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Activity Logs Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Aktivitas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $index => $log)
                    <tr>
                        <td>{{ $activityLogs->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <i class="bi bi-calendar3 text-muted"></i> 
                                {{ $log->created_at->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $log->created_at->format('H:i:s') }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($log->user->profile_image)
                                    <img src="{{ asset($log->user->profile_image) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle me-2"
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle text-muted me-2"></i>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $log->user->name }}</div>
                                    <small class="text-muted">
                                        <span class="badge 
                                            @if($log->user->role === 'admin') bg-danger
                                            @elseif($log->user->role === 'petugas') bg-warning
                                            @else bg-info
                                            @endif">
                                            {{ ucfirst($log->user->role) }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $actionColors = [
                                    'create' => 'success',
                                    'update' => 'primary',
                                    'delete' => 'danger',
                                    'login' => 'info',
                                    'logout' => 'secondary',
                                    'respond' => 'warning',
                                ];
                                $actionLabels = [
                                    'create' => 'Membuat',
                                    'update' => 'Mengupdate',
                                    'delete' => 'Menghapus',
                                    'login' => 'Login',
                                    'logout' => 'Logout',
                                    'respond' => 'Menanggapi',
                                ];
                                $color = $actionColors[$log->action] ?? 'secondary';
                                $label = $actionLabels[$log->action] ?? ucfirst($log->action);
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                        </td>
                        <td>
                            <div>{{ $log->description }}</div>
                            @if($log->model_type)
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> {{ class_basename($log->model_type) }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $log->ip_address ?? '-' }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-clock-history" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada aktivitas yang tercatat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activityLogs->hasPages())
        <div class="mt-4">
            {{ $activityLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

