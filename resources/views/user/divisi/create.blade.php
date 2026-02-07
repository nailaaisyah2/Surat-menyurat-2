@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-building text-primary"></i> Tambah Divisi</h2>
    <p class="text-muted">Buat divisi/kantor baru dalam sistem</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Divisi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('divisions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="form-label fw-bold">
                    Nama Divisi <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" 
                       placeholder="Masukkan nama divisi" required autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('divisions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

