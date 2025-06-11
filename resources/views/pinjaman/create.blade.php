@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ajukan Pinjaman Baru</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pinjaman.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('jumlah_pinjaman') is-invalid @enderror" id="jumlah_pinjaman" name="jumlah_pinjaman" value="{{ old('jumlah_pinjaman') }}" min="0" required>
                            </div>
                            @error('jumlah_pinjaman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jangka_waktu" class="form-label">Jangka Waktu (bulan)</label>
                            <input type="number" class="form-control @error('jangka_waktu') is-invalid @enderror" id="jangka_waktu" name="jangka_waktu" value="{{ old('jangka_waktu') }}" min="1" max="36" required>
                            @error('jangka_waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pinjaman" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjaman') is-invalid @enderror" id="tanggal_pinjaman" name="tanggal_pinjaman" value="{{ old('tanggal_pinjaman', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjaman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="bunga" class="form-label">Bunga (%)</label>
                            <input type="number" class="form-control @error('bunga') is-invalid @enderror" id="bunga" name="bunga" value="{{ old('bunga') }}" min="0" max="100" step="0.01" required>
                            @error('bunga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection