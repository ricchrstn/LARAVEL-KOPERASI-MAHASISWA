<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $pinjaman = $user->isAdmin() 
            ? Pinjaman::with('user')->latest()->get()
            : $user->pinjaman()->latest()->get();
            
        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        return view('pinjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:0',
            'jangka_waktu' => 'required|integer|min:1|max:36',
            'tanggal_pinjaman' => 'required|date',
            'bunga' => 'required|numeric|between:0,100',
            'keterangan' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['total_pinjaman'] = $validated['jumlah_pinjaman'] * (1 + ($validated['bunga'] / 100));

        Pinjaman::create($validated);

        return redirect()->route('pinjaman.index')
            ->with('success', 'Pengajuan pinjaman berhasil dibuat');
    }

    public function show(Pinjaman $pinjaman)
    {
        $this->authorize('view', $pinjaman);
        return view('pinjaman.show', compact('pinjaman'));
    }

    public function edit(Pinjaman $pinjaman)
    {
        $this->authorize('update', $pinjaman);
        return view('pinjaman.edit', compact('pinjaman'));
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        $this->authorize('update', $pinjaman);

        $validated = $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:0',
            'jangka_waktu' => 'required|integer|min:1|max:36',
            'tanggal_pinjaman' => 'required|date',
            'tanggal_pelunasan' => 'nullable|date|after:tanggal_pinjaman',
            'bunga' => 'required|numeric|between:0,100',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,paid'
        ]);

        $validated['total_pinjaman'] = $validated['jumlah_pinjaman'] * (1 + ($validated['bunga'] / 100));

        $pinjaman->update($validated);

        return redirect()->route('pinjaman.index')
            ->with('success', 'Data pinjaman berhasil diperbarui');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        $this->authorize('delete', $pinjaman);
        $pinjaman->delete();

        return redirect()->route('pinjaman.index')
            ->with('success', 'Pinjaman berhasil dihapus');
    }
}