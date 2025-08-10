<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    use Importable;

    private $headerRow = 1;

    public function __construct($filePath)
    {
        // Baca file excel mentah pakai PhpSpreadsheet
        $spreadsheet = IOFactory::load(Storage::path($filePath));
        $sheet = $spreadsheet->getActiveSheet();

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

    public function model(array $row)
    {
        $kelasId = Kelas::where('name', $row['kelas'])->value('id');
        return new Siswa([
            'nama'          => $row['nama'],
            'nis'           => $row['nis'],
            'nisn'          => $row['nisn'],
            'kelas_id'      => $kelasId,
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}
