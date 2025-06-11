<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Firestore;

class PinjamanController extends Controller
{
    protected $firestore;
    
    public function __construct()
    {
        $this->firestore = app('firebase.firestore');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:100000',
            'tenor_bulan' => 'required|integer|min:1|max:24',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Hitung bunga dan cicilan
        $bunga = 1;
        $total = $validated['jumlah'] * (1 + ($bunga * $validated['tenor_bulan'] / 100));
        $cicilan = $total / $validated['tenor_bulan'];

        // Save to MySQL
        $pinjaman = Pinjaman::create([
            'user_id' => Auth::id(),
            'jumlah' => $validated['jumlah'],
            'bunga_persen' => $bunga,
            'tenor_bulan' => $validated['tenor_bulan'],
            'total_pembayaran' => $total,
            'cicilan_per_bulan' => $cicilan,
            'tanggal_pengajuan' => now(),
            'status' => 'pending',
            'keterangan' => $validated['keterangan']
        ]);

        // Save to Firebase
        $pinjamanRef = $this->firestore->database()->collection('pinjaman')->document($pinjaman->id);
        $pinjamanRef->set([
            'user_id' => Auth::id(),
            'jumlah' => (float) $validated['jumlah'],
            'bunga_persen' => (float) $bunga,
            'tenor_bulan' => (int) $validated['tenor_bulan'],
            'total_pembayaran' => (float) $total,
            'cicilan_per_bulan' => (float) $cicilan,
            'tanggal_pengajuan' => now()->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'keterangan' => $validated['keterangan'] ?? '',
            'created_at' => \Google\Cloud\Core\Timestamp::fromDateTime(now())
        ]);

        return redirect()->route('pinjaman.show', $pinjaman)
                        ->with('success', 'Pengajuan pinjaman berhasil dibuat.');
    }

    public function approve(Pinjaman $pinjaman)
    {
        // Update MySQL
        $pinjaman->update([
            'status' => 'approved',
            'tanggal_approval' => now()
        ]);

        // Update Firebase
        $pinjamanRef = $this->firestore->database()->collection('pinjaman')->document($pinjaman->id);
        $pinjamanRef->update([
            ['path' => 'status', 'value' => 'approved'],
            ['path' => 'tanggal_approval', 'value' => now()->format('Y-m-d H:i:s')],
            ['path' => 'updated_at', 'value' => \Google\Cloud\Core\Timestamp::fromDateTime(now())]
        ]);
        
        return back()->with('success', 'Pinjaman telah disetujui');
    }
}