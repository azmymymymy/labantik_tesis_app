<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil_belajar extends Model
{
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'pretest',
        'posttest',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
