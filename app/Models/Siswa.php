<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nis',
        'nisn',
        'kelas_id',
        'jenis_kelamin',
    ];

   public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function angketMinat()
    {
        return $this->hasOne(AngketMinat::class, 'siswa_id');
    }

    public function angketMotivasi()
    {
        return $this->hasOne(Angket_motivasi::class, 'siswa_id');
    }

    public function observasi()
    {
        return $this->hasOne(Observasi::class, 'siswa_id');
    }

    // NEW: Add relation to hasil_belajar
    public function hasilBelajar()
    {
        return $this->hasOne(Hasil_belajar::class, 'siswa_id');
    }
}
