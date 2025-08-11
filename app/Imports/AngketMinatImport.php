<?php

namespace App\Imports;

use App\Models\AngketMinat;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AngketMinatImport implements ToModel, WithHeadingRow
{
    use Importable;

    private $headerRow = 1;

    public function __construct($filePath)
    {
        $spreedsheet = IOFactory::load(Storage::path($filePath));
        $sheet = $spreedsheet->getActiveSheet();

        foreach ($sheet->toArray() as $i => $row) {
            if (strtolower($row[0]) === 'nama') { // Kolom pertama = 'Nama'
                $this->headerRow = $i + 1; // +1 karena array index mulai 0
                break;
            }
        }
    }

    public function headingRow(): int
    {
        return $this->headerRow;
    }

    /**
     * Transform each row to model
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama']) || empty($row['kelas'])) {
            return null;
        }

        $namaId = Siswa::where('nama', $row['nama'])->value('id');
        $kelasId = Kelas::where('name', $row['kelas'])->value('id');

        // Cek duplikasi berdasarkan siswa_id dan kelas_id
        $exists = AngketMinat::where('siswa_id', $namaId)
            ->where('kelas_id', $kelasId)
            ->exists();
        if ($exists) {
            return null; // skip baris duplikat
        }

        // Helper: valid angka 1-5
        $fix = function ($v) {
            $v = (int) $v;
            return ($v >= 1 && $v <= 5) ? $v : 1;
        };

        // Calculate total nilai
        $totalNilai = 0;
        for ($i = 1; $i <= 14; $i++) {
            $totalNilai += $fix($row[$i] ?? 1);
        }

        return new AngketMinat([
            'siswa_id' => $namaId,
            'kelas_id' => $kelasId,
            'pertanyaan_1' => $fix($row['1'] ?? 1),
            'pertanyaan_2' => $fix($row['2'] ?? 1),
            'pertanyaan_3' => $fix($row['3'] ?? 1),
            'pertanyaan_4' => $fix($row['4'] ?? 1),
            'pertanyaan_5' => $fix($row['5'] ?? 1),
            'pertanyaan_6' => $fix($row['6'] ?? 1),
            'pertanyaan_7' => $fix($row['7'] ?? 1),
            'pertanyaan_8' => $fix($row['8'] ?? 1),
            'pertanyaan_9' => $fix($row['9'] ?? 1),
            'pertanyaan_10' => $fix($row['10'] ?? 1),
            'pertanyaan_11' => $fix($row['11'] ?? 1),
            'pertanyaan_12' => $fix($row['12'] ?? 1),
            'pertanyaan_13' => $fix($row['13'] ?? 1),
            'pertanyaan_14' => $fix($row['14'] ?? 1),
            'total' => $totalNilai,
        ]);
    }
}