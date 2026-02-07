@extends('layouts.app')

@section('title', 'Edit Divisi')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-building text-primary"></i> Edit Divisi</h2>
    <p class="text-muted">Ubah informasi divisi/kantor</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil"></i> Form Edit Divisi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('divisions.update', $division) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="form-label fw-bold">
                    Nama Divisi <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $division->name) }}" 
                       placeholder="Masukkan nama divisi" required autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('divisions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
