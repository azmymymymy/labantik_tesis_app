<?php

namespace App\Imports;

use App\Models\AngketMinat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AngketMinatImport implements ToModel, WithStartRow, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;

    /**
     * Start from row 2 (assuming row 1 has headers)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Transform each row to model
     */
    public function model(array $row)
    {
        // Jika ada kolom No di depan, geser array ke kiri
        if (is_numeric($row[0]) && isset($row[1]) && isset($row[2])) {
            array_shift($row);
        }
        // Skip empty rows
        if (empty($row[0]) || empty($row[1])) {
            return null;
        }
        // Cek duplikasi berdasarkan nama dan kelas
        $exists = \App\Models\AngketMinat::where('nama', (string)$row[0])
            ->where('kelas', (string)$row[1])
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
        for ($i = 2; $i <= 15; $i++) {
            $totalNilai += $fix($row[$i] ?? 1);
        }

        return new AngketMinat([
            'nama' => (string) $row[0],
            'kelas' => (string) $row[1],
            'nilai_1' => $fix($row[2] ?? 1),
            'nilai_2' => $fix($row[3] ?? 1),
            'nilai_3' => $fix($row[4] ?? 1),
            'nilai_4' => $fix($row[5] ?? 1),
            'nilai_5' => $fix($row[6] ?? 1),
            'nilai_6' => $fix($row[7] ?? 1),
            'nilai_7' => $fix($row[8] ?? 1),
            'nilai_8' => $fix($row[9] ?? 1),
            'nilai_9' => $fix($row[10] ?? 1),
            'nilai_10' => $fix($row[11] ?? 1),
            'nilai_11' => $fix($row[12] ?? 1),
            'nilai_12' => $fix($row[13] ?? 1),
            'nilai_13' => $fix($row[14] ?? 1),
            'nilai_14' => $fix($row[15] ?? 1),
            'total_nilai' => $totalNilai,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            '0' => 'required', // nama
            '1' => 'required', // kelas
            // Hilangkan validasi numeric/min/max agar tidak error, cukup nullable
            '2' => 'nullable',
            '3' => 'nullable',
            '4' => 'nullable',
            '5' => 'nullable',
            '6' => 'nullable',
            '7' => 'nullable',
            '8' => 'nullable',
            '9' => 'nullable',
            '10' => 'nullable',
            '11' => 'nullable',
            '12' => 'nullable',
            '13' => 'nullable',
            '14' => 'nullable',
            '15' => 'nullable',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Nama pada baris :row harus diisi.',
            '1.required' => 'Kelas pada baris :row harus diisi.',
            '2.min' => 'Nilai 1 pada baris :row minimal 1.',
            '2.max' => 'Nilai 1 pada baris :row maksimal 5.',
            '3.min' => 'Nilai 2 pada baris :row minimal 1.',
            '3.max' => 'Nilai 2 pada baris :row maksimal 5.',
            '4.min' => 'Nilai 3 pada baris :row minimal 1.',
            '4.max' => 'Nilai 3 pada baris :row maksimal 5.',
            '5.min' => 'Nilai 4 pada baris :row minimal 1.',
            '5.max' => 'Nilai 4 pada baris :row maksimal 5.',
            '6.min' => 'Nilai 5 pada baris :row minimal 1.',
            '6.max' => 'Nilai 5 pada baris :row maksimal 5.',
            '7.min' => 'Nilai 6 pada baris :row minimal 1.',
            '7.max' => 'Nilai 6 pada baris :row maksimal 5.',
            '8.min' => 'Nilai 7 pada baris :row minimal 1.',
            '8.max' => 'Nilai 7 pada baris :row maksimal 5.',
            '9.min' => 'Nilai 8 pada baris :row minimal 1.',
            '9.max' => 'Nilai 8 pada baris :row maksimal 5.',
            '10.min' => 'Nilai 9 pada baris :row minimal 1.',
            '10.max' => 'Nilai 9 pada baris :row maksimal 5.',
            '11.min' => 'Nilai 10 pada baris :row minimal 1.',
            '11.max' => 'Nilai 10 pada baris :row maksimal 5.',
            '12.min' => 'Nilai 11 pada baris :row minimal 1.',
            '12.max' => 'Nilai 11 pada baris :row maksimal 5.',
            '13.min' => 'Nilai 12 pada baris :row minimal 1.',
            '13.max' => 'Nilai 12 pada baris :row maksimal 5.',
            '14.min' => 'Nilai 13 pada baris :row minimal 1.',
            '14.max' => 'Nilai 13 pada baris :row maksimal 5.',
            '15.min' => 'Nilai 14 pada baris :row minimal 1.',
            '15.max' => 'Nilai 14 pada baris :row maksimal 5.',
            '2.numeric' => 'Nilai 1 harus berupa angka antara 1-5.',
            '3.numeric' => 'Nilai 2 harus berupa angka antara 1-5.',
            '4.numeric' => 'Nilai 3 harus berupa angka antara 1-5.',
            '5.numeric' => 'Nilai 4 harus berupa angka antara 1-5.',
            '6.numeric' => 'Nilai 5 harus berupa angka antara 1-5.',
            '7.numeric' => 'Nilai 6 harus berupa angka antara 1-5.',
            '8.numeric' => 'Nilai 7 harus berupa angka antara 1-5.',
            '9.numeric' => 'Nilai 8 harus berupa angka antara 1-5.',
            '10.numeric' => 'Nilai 9 harus berupa angka antara 1-5.',
            '11.numeric' => 'Nilai 10 harus berupa angka antara 1-5.',
            '12.numeric' => 'Nilai 11 harus berupa angka antara 1-5.',
            '13.numeric' => 'Nilai 12 harus berupa angka antara 1-5.',
            '14.numeric' => 'Nilai 13 harus berupa angka antara 1-5.',
            '15.numeric' => 'Nilai 14 harus berupa angka antara 1-5.',
        ];
    }

    /**
     * Batch size for import
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
