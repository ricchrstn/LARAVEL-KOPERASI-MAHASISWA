@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pinjaman</h5>
                    <a href="{{ route('pinjaman.create') }}" class="btn btn-primary">Ajukan Pinjaman</a>
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
                                    <th>Jumlah</th>
                                    <th>Bunga (%)</th>
                                    <th>Total</th>
                                    <th>Jangka Waktu (bulan)</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjaman as $index => $p)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    @if(auth()->user()->isAdmin())
                                    <td>{{ $p->user->name }}</td>
                                    @endif
                                    <td>Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                                    <td>{{ $p->bunga }}</td>
                                    <td>Rp {{ number_format($p->total_pinjaman, 0, ',', '.') }}</td>
                                    <td>{{ $p->jangka_waktu }}</td>
                                    <td>{{ $p->tanggal_pinjaman->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $p->status == 'approved' ? 'success' : ($p->status == 'rejected' ? 'danger' : ($p->status == 'paid' ? 'primary' : 'warning')) }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pinjaman.show', $p) }}" class="btn btn-sm btn-info">Detail</a>
                                        @can('update', $p)
                                        <a href="{{ route('pinjaman.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data pinjaman</td>
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