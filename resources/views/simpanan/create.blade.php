@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Simpanan Baru</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('simpanan.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="jenis_simpanan" class="form-label">Jenis Simpanan</label>
                            <select class="form-select @error('jenis_simpanan') is-invalid @enderror" id="jenis_simpanan" name="jenis_simpanan" required>
                                <option value="">Pilih jenis simpanan</option>
                                <option value="pokok" {{ old('jenis_simpanan') == 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                                <option value="wajib" {{ old('jenis_simpanan') == 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                                <option value="sukarela" {{ old('jenis_simpanan') == 'sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                            </select>
                            @error('jenis_simpanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Simpanan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="0" required>
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
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
                            <a href="{{ route('simpanan.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection