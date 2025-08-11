<?php

namespace App\Imports;

use App\Models\AngketMinat;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AngketMinatImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function headingRow(): int
    {
        return 1; // sesuaikan kalau header di baris lain
    }

    public function model(array $row)
    {
        // Normalisasi key header: trim, lowercase, spasi->underscore, titik/dash->underscore
        $normalized = [];
        foreach ($row as $k => $v) {
            $key = strtolower(trim((string) $k));
            $key = preg_replace('/[\s\.\-]+/', '_', $key);
            $normalized[$key] = $v;
        }

        // Cari nomor urut di Excel (biasanya header "NO" -> normalized "no")
        $no = $normalized['no'] ?? $normalized['no_'] ?? $normalized['nomor'] ?? null;
        $siswa = null;
        if ($no !== null && $no !== '') {
            $siswa = Siswa::find((int) $no);
        }

        // Ambil id dan kelas dari DB kalau ada
        $siswaId = $siswa ? $siswa->id : null;
        $kelasId = $siswa ? $siswa->kelas_id : null;

        // Ambil nilai pertanyaan 1..14 — cek beberapa kemungkinan key (angka, atau 'pertanyaan_1' dst.)
        $dataNilai = [];
        $totalCalc = 0;
        for ($i = 1; $i <= 14; $i++) {
            $candidates = [
                (string)$i,
                "pertanyaan_{$i}",
                "pertanyaan_{$i}", // same but kept for clarity
                "pertanyaan_{$i}", // fallback duplicates harmless
                "p{$i}",
                "p_{$i}"
            ];

            $val = null;
            foreach ($candidates as $c) {
                if (array_key_exists($c, $normalized) && $normalized[$c] !== null && $normalized[$c] !== '') {
                    $val = (int) $normalized[$c];
                    break;
                }
            }

            // default 0 kalau kosong (hindari NOT NULL error)
            $val = is_numeric($val) ? (int)$val : 0;
            $dataNilai["pertanyaan_{$i}"] = $val;
            $totalCalc += $val;
        }

        // total: pakai kolom 'total' kalau ada, kalau nggak gunakan perhitungan
        $total = null;
        if (array_key_exists('total', $normalized) && $normalized['total'] !== null && $normalized['total'] !== '') {
            $total = (int) $normalized['total'];
        } else {
            $total = $totalCalc;
        }

        return new AngketMinat(array_merge([
            'siswa_id' => $siswaId,
            'kelas_id' => $kelasId,
            'total'    => $total,
        ], $dataNilai));
    }
}
