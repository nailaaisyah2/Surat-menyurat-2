@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-speedometer2 text-primary"></i> Dashboard User</h2>
    <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
</div>

<!-- Surat Saya Section -->
<div class="mb-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-send text-primary"></i> Surat Saya</h4>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2 text-white-50">Total Surat</h6>
                            <h2 class="mb-0 fw-bold">{{ $suratSaya }}</h2>
                        </div>
                        <i class="bi bi-envelope" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2 text-white-50">Pending</h6>
                            <h2 class="mb-0 fw-bold">{{ $suratPending }}</h2>
                        </div>
                        <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2 text-white-50">Diterima</h6>
                            <h2 class="mb-0 fw-bold">{{ $suratDiterima }}</h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2 text-white-50">Ditolak</h6>
                            <h2 class="mb-0 fw-bold">{{ $suratDitolak }}</h2>
                        </div>
                        <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Lainnya -->
<div class="mb-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-graph-up text-info"></i> Statistik Lainnya</h4>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-calendar-event text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0 text-muted">Pertemuan Individu</h6>
                            <h3 class="mb-0 text-info fw-bold">{{ $suratPertemuanIndividu }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0 text-muted">Rapat Kantor</h6>
                            <h3 class="mb-0 text-primary fw-bold">{{ $suratRapatKantor }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-calendar-day text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0 text-muted">Surat Hari Ini</h6>
                            <h3 class="mb-0 text-success fw-bold">{{ $suratHariIni }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-calendar-month text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-0 text-muted">Surat Bulan Ini</h6>
                            <h3 class="mb-0 text-warning fw-bold">{{ $suratBulanIni }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-building text-info" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">Divisi Anda</h5>
                        <p class="card-text mb-0 text-muted">{{ auth()->user()->division->name ?? 'Tidak ada divisi' }}</p>
                    </div>
                    <a href="{{ route('surat_masuk.index') }}" class="btn btn-primary">
                        <i class="bi bi-send"></i> Lihat Surat Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Diagram Section -->
<div class="row g-4 mt-2">
    <!-- Pie Chart - Status Surat -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Diagram Status Surat</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Bar Chart - Surat Per Bulan -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Trend Surat Saya (6 Bulan Terakhir)</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart Jenis Surat -->
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Diagram Jenis Surat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="jenisSuratChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div>
                            <h5>Detail Jenis Surat:</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-calendar-event text-info"></i> 
                                    <strong>Pertemuan Individu:</strong> {{ $suratPertemuanIndividu }} surat
                                </li>
                                <li>
                                    <i class="bi bi-people text-primary"></i> 
                                    <strong>Rapat Kantor:</strong> {{ $suratRapatKantor }} surat
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart - Status Surat
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Pending', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $suratPending }},
                    {{ $suratDiterima }},
                    {{ $suratDitolak }}
                ],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' surat';
                            return label;
                        }
                    }
                }
            }
        }
    });
    }

    // Bar Chart - Surat Per Bulan
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Jumlah Surat',
                data: @json($monthlyData),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Surat: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
    }

    // Pie Chart - Jenis Surat
    const jenisSuratCtx = document.getElementById('jenisSuratChart');
    if (jenisSuratCtx) {
        new Chart(jenisSuratCtx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Pertemuan Individu', 'Rapat Kantor'],
            datasets: [{
                data: [
                    {{ $suratPertemuanIndividu }},
                    {{ $suratRapatKantor }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' surat';
                            return label;
                        }
                    }
                }
            }
        }
    });
    }
    });
</script>
@endpush

