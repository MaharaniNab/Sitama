<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaSidang extends Model
{
    protected $table = 'ta_sidang';

    public function ta()
    {
        // Menggunakan 'ta_id' sebagai foreign key untuk menghubungkan relasi 'ta'
        return $this->belongsTo(Ta::class, 'ta_id', 'ta_id');
    }

    // Tambahkan relasi dengan 'mahasiswa' menggunakan 'mhs_nim' sebagai foreign key
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mhs_nim', 'mhs_nim');
    }

    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_id', 'jadwal_id');
    }

    // Anda juga perlu menambahkan relasi untuk mendapatkan data dosen
    // Contoh, jika ada atribut pembimbing dan penguji pada tabel ta_sidang

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_nama', 'dosen_nip');
    }

}
