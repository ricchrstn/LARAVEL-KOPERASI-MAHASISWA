@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Simpanan</h5>
                    <a href="{{ route('simpanan.create') }}" class="btn btn-primary">Tambah Simpanan</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if(auth()->user()->isAdmin())
                                    <th>Anggota</th>
                                    @endif
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($simpanan as $index => $s)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    @if(auth()->user()->isAdmin())
                                    <td>{{ $s->user->name }}</td>
                                    @endif
                                    <td>{{ ucfirst($s->jenis_simpanan) }}</td>
                                    <td>Rp {{ number_format($s->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $s->tanggal->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $s->status == 'approved' ? 'success' : ($s->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($s->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('simpanan.show', $s) }}" class="btn btn-sm btn-info">Detail</a>
                                        @can('update', $s)
                                        <a href="{{ route('simpanan.edit', $s) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data simpanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
