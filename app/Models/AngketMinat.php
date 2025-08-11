<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngketMinat extends Model
{
    use HasFactory;

    protected $table = 'angket_minat';

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
        'pertanyaan_11',
        'pertanyaan_12',
        'pertanyaan_13',
        'pertanyaan_14',
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

    /**
     * Get all nilai attributes as array
     */
    public function getNilaiAttribute()
    {
        return [
            $this->pertanyaan_1,
            $this->pertanyaan_2,
            $this->pertanyaan_3,
            $this->pertanyaan_4,
            $this->pertanyaan_5,
            $this->pertanyaan_6,
            $this->pertanyaan_7,
            $this->pertanyaan_8,
            $this->pertanyaan_9,
            $this->pertanyaan_10,
            $this->pertanyaan_11,
            $this->pertanyaan_12,
            $this->pertanyaan_13,
            $this->pertanyaan_14,
        ];
    }

    /**
     * Calculate and update total nilai
     */
    public function calculateTotalNilai()
    {
        $total = $this->pertanyaan_1 + $this->pertanyaan_2 + $this->pertanyaan_3 + $this->pertanyaan_4 + 
                $this->pertanyaan_5 + $this->pertanyaan_6 + $this->pertanyaan_7 + $this->pertanyaan_8 + 
                $this->pertanyaan_9 + $this->pertanyaan_10 + $this->pertanyaan_11 + $this->pertanyaan_12 + 
                $this->pertanyaan_13 + $this->pertanyaan_14;
        
        $this->total = $total;
        return $total;
    }

    /**
     * Get rata-rata nilai
     */
    public function getRataRataNilai()
    {
        return round($this->total / 14, 2);
    }

    /**
     * Get kategori minat berdasarkan total nilai
     */
    public function getKategoriMinat()
    {
        $total = $this->total;
        
        if ($total >= 56) {
            return 'Sangat Tinggi';
        } elseif ($total >= 49) {
            return 'Tinggi';
        } elseif ($total >= 42) {
            return 'Sedang';
        } elseif ($total >= 35) {
            return 'Rendah';
        } else {
            return 'Sangat Rendah';
        }
    }

    /**
     * Scope untuk filter berdasarkan kelas
     */
    public function scopeByKelas($query, $kelas)
    {
        return $query->where('kelas_id', $kelas);
    }

    /**
     * Scope untuk filter berdasarkan range total nilai
     */
    public function scopeByTotalNilai($query, $min, $max)
    {
        return $query->whereBetween('total', [$min, $max]);
    }

    /**
     * Get daftar kelas unik
     */
    public static function getDaftarKelas()
    {
        return self::with('kelas')->get()->pluck('kelas.name')->unique()->sort()->values();
    }
}