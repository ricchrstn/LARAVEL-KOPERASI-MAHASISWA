<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Firestore;

class DashboardController extends Controller
{
    protected $firestore;
    
    public function __construct()
    {
        $this->firestore = app('firebase.firestore');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get data from MySQL
        $mysqlData = [
            'total_simpanan' => Simpanan::where('user_id', $user->id)
                                      ->where('status', 'approved')
                                      ->sum('jumlah'),
            'total_pinjaman' => Pinjaman::where('user_id', $user->id)
                                      ->where('status', 'approved')
                                      ->sum('jumlah'),
            'simpanan_list' => Simpanan::where('user_id', $user->id)
                                      ->latest()
                                      ->take(5)
                                      ->get(),
            'pinjaman_list' => Pinjaman::where('user_id', $user->id)
                                      ->latest()
                                      ->take(5)
                                      ->get()
        ];

        // Get data from Firebase
        $simpananRef = $this->firestore->database()
            ->collection('simpanan')
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5);
        
        $pinjamanRef = $this->firestore->database()
            ->collection('pinjaman')
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5);

        $firebaseData = [
            'simpanan_firebase' => $simpananRef->documents(),
            'pinjaman_firebase' => $pinjamanRef->documents()
        ];

        return view('dashboard.user', array_merge($mysqlData, $firebaseData));
    }
}