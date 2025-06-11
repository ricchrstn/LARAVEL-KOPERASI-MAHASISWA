<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPinjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjaman_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_pembayaran',
        'status' // pending, verified
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'decimal:2'
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}