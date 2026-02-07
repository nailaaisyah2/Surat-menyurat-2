<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel surat.
     */
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_id') // Pengguna pembuat surat
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('penerima_division_id') // Divisi tujuan surat
                ->constrained('divisions')
                ->onDelete('cascade');
            $table->foreignId('penerima_user_id') // Penerima individu (opsional)
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->enum('jenis', ['pertemuan_individu', 'rapat_kantor']); // Jenis surat
            $table->string('judul'); // Judul surat
            $table->text('isi'); // Isi lengkap surat
            $table->date('tanggal_pertemuan'); // Tanggal kegiatan
            $table->time('jam_pertemuan'); // Waktu kegiatan
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending'); // Status persetujuan
            $table->text('catatan_petugas')->nullable(); // Catatan balasan
            $table->foreignId('responded_by')->nullable() // Petugas/admin yang merespons
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('responded_at')->nullable(); // Waktu respons
            $table->string('lampiran')->nullable(); // Lampiran dari pengirim
            $table->string('lampiran_response')->nullable(); // Lampiran balasan
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};

