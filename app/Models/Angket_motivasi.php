<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angket_motivasi extends Model
{
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'pertanyaan_1',
        'pertanyaan_2',
        'pertanyaan_3',
        'pertanyaan_4',
        'pertanyaan_5',
        'pertanyaan_6',
        'pertanyaan_7',
        'pertanyaan_8',
        'pertanyaan_9',
        'pertanyaan_10',
        'total',
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
