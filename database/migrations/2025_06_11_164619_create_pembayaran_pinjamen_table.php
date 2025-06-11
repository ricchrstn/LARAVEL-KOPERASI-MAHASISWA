<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('pembayaran_pinjamen', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pinjaman_id')->constrained()->onDelete('cascade');
        $table->decimal('jumlah_bayar', 15, 2);
        $table->date('tanggal_bayar');
        $table->string('bukti_pembayaran')->nullable();
        $table->enum('status', ['pending', 'verified'])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pinjamen');
    }
};
