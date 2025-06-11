<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'bunga_persen',
        'tenor_bulan',
        'total_pembayaran',
        'cicilan_per_bulan',
        'tanggal_pengajuan',
        'tanggal_approval',
        'status', // pending, approved, rejected, completed
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_approval' => 'datetime',
        'jumlah' => 'decimal:2',
        'bunga_persen' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
        'cicilan_per_bulan' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranPinjaman::class);
    }
}