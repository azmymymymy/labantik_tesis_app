<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Observasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ObservasiImport implements ToModel, WithHeadingRow
{
    private $headerRow = 1;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

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
    public function model(array $row)
    {
        $kelas = Kelas::firstOrCreate(['name', $row['kelas']], []);
        $siswa = Siswa::firstOrCreate(['nama', $row['nama']], ['kelas_id', $kelas->id]);

        return new Observasi([
            'siswa_id'      => $siswa->id,
            'kelas_id'      => $kelas->id,
            'pertanyaan_1' => $row['1'],
            'pertanyaan_2' => $row['3'],
            'pertanyaan_3' => $row['3'],
            'pertanyaan_4' => $row['4'],
            'pertanyaan_5' => $row['5'],
            'pertanyaan_6' => $row['6'],
            'pertanyaan_7' => $row['7'],
            'total' => $row['total'],
        ]);
    }
}
