<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_simpanan' => 'required|in:pokok,wajib,sukarela',
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            // Simpan ke MySQL
            $simpanan = Simpanan::create([
                'user_id' => Auth::id(),
                'jenis_simpanan' => $validated['jenis_simpanan'],
                'jumlah' => $validated['jumlah'],
                'tanggal' => now(),
                'status' => 'pending',
                'keterangan' => $validated['keterangan']
            ]);

            // Simpan ke Firestore
            $this->firebase->saveToFirestore('simpanan', $simpanan->id, [
                'user_id' => Auth::id(),
                'jenis_simpanan' => $validated['jenis_simpanan'],
                'jumlah' => (float) $validated['jumlah'],
                'tanggal' => now()->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'keterangan' => $validated['keterangan'] ?? '',
                'created_at' => \Google\Cloud\Core\Timestamp::fromDateTime(now())
            ]);

            return redirect()->route('simpanan.show', $simpanan)
                           ->with('success', 'Pengajuan simpanan berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}