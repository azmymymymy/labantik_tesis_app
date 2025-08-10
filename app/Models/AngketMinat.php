<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngketMinat extends Model
{
    use HasFactory;

    protected $table = 'angket_minat';

    protected $fillable = [
        'nama',
        'kelas',
        'nilai_1',
        'nilai_2',
        'nilai_3',
        'nilai_4',
        'nilai_5',
        'nilai_6',
        'nilai_7',
        'nilai_8',
        'nilai_9',
        'nilai_10',
        'nilai_11',
        'nilai_12',
        'nilai_13',
        'nilai_14',
        'total_nilai',
    ];

    protected $casts = [
        'nilai_1' => 'integer',
        'nilai_2' => 'integer',
        'nilai_3' => 'integer',
        'nilai_4' => 'integer',
        'nilai_5' => 'integer',
        'nilai_6' => 'integer',
        'nilai_7' => 'integer',
        'nilai_8' => 'integer',
        'nilai_9' => 'integer',
        'nilai_10' => 'integer',
        'nilai_11' => 'integer',
        'nilai_12' => 'integer',
        'nilai_13' => 'integer',
        'nilai_14' => 'integer',
        'total_nilai' => 'integer',
    ];

    /**
     * Get all nilai attributes as array
     */
    public function getNilaiAttribute()
    {
        return [
            $this->nilai_1,
            $this->nilai_2,
            $this->nilai_3,
            $this->nilai_4,
            $this->nilai_5,
            $this->nilai_6,
            $this->nilai_7,
            $this->nilai_8,
            $this->nilai_9,
            $this->nilai_10,
            $this->nilai_11,
            $this->nilai_12,
            $this->nilai_13,
            $this->nilai_14,
        ];
    }

    /**
     * Calculate and update total nilai
     */
    public function calculateTotalNilai()
    {
        $total = $this->nilai_1 + $this->nilai_2 + $this->nilai_3 + $this->nilai_4 + 
                $this->nilai_5 + $this->nilai_6 + $this->nilai_7 + $this->nilai_8 + 
                $this->nilai_9 + $this->nilai_10 + $this->nilai_11 + $this->nilai_12 + 
                $this->nilai_13 + $this->nilai_14;
        
        $this->total_nilai = $total;
        return $total;
    }

    /**
     * Get rata-rata nilai
     */
    public function getRataRataNilai()
    {
        return round($this->total_nilai / 14, 2);
    }

    /**
     * Get kategori minat berdasarkan total nilai
     */
    public function getKategoriMinat()
    {
        $total = $this->total_nilai;
        
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
        return $query->where('kelas', $kelas);
    }

    /**
     * Scope untuk filter berdasarkan range total nilai
     */
    public function scopeByTotalNilai($query, $min, $max)
    {
        return $query->whereBetween('total_nilai', [$min, $max]);
    }

    /**
     * Get daftar kelas unik
     */
    public static function getDaftarKelas()
    {
        return self::distinct('kelas')->pluck('kelas')->sort()->values();
    }
}