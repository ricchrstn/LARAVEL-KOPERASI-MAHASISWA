<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $simpanan = $user->isAdmin() 
            ? Simpanan::with('user')->latest()->get()
            : $user->simpanan()->latest()->get();
            
        return view('simpanan.index', compact('simpanan'));
    }

    public function create()
    {
        return view('simpanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_simpanan' => 'required|string|in:pokok,wajib,sukarela',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Simpanan::create($validated);

        return redirect()->route('simpanan.index')
            ->with('success', 'Simpanan berhasil ditambahkan');
    }

    public function show(Simpanan $simpanan)
    {
        $this->authorize('view', $simpanan);
        return view('simpanan.show', compact('simpanan'));
    }

    public function edit(Simpanan $simpanan)
    {
        $this->authorize('update', $simpanan);
        return view('simpanan.edit', compact('simpanan'));
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        $this->authorize('update', $simpanan);

        $validated = $request->validate([
            'jenis_simpanan' => 'required|string|in:pokok,wajib,sukarela',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $simpanan->update($validated);

        return redirect()->route('simpanan.index')
            ->with('success', 'Simpanan berhasil diperbarui');
    }

    public function destroy(Simpanan $simpanan)
    {
        $this->authorize('delete', $simpanan);
        $simpanan->delete();

        return redirect()->route('simpanan.index')
            ->with('success', 'Simpanan berhasil dihapus');
    }
}