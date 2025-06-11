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
        Schema::create('pinjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah_pinjaman', 12, 2);
            $table->integer('jangka_waktu'); // dalam bulan
            $table->dateTime('tanggal_pinjaman');
            $table->dateTime('tanggal_pelunasan')->nullable();
            $table->decimal('bunga', 5, 2); // dalam persen
            $table->decimal('total_pinjaman', 12, 2); // jumlah pinjaman + bunga
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamen');
    }
};
