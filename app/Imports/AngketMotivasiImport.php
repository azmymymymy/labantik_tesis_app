<?php

namespace App\Imports;

use App\Models\Angket_motivasi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AngketMotivasiImport implements ToModel, WithHeadingRow
{
    use Importable;

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
        $namaId = Siswa::where('nama', $row['nama'])->value('id');
        $kelasId = Kelas::where('name', $row['kelas'])->value('id');
        return new Angket_motivasi([
            'siswa_id'      => $namaId,
            'kelas_id'      => $kelasId,
            'pertanyaan_1'  => $row['1'],
            'pertanyaan_2'  => $row['2'],
            'pertanyaan_3'  => $row['3'],
            'pertanyaan_4'  => $row['4'],
            'pertanyaan_5'  => $row['5'],
            'pertanyaan_6'  => $row['6'],
            'pertanyaan_7'  => $row['7'],
            'pertanyaan_8'  => $row['8'],
            'pertanyaan_9'  => $row['9'],
            'pertanyaan_10' => $row['10'],
            'total' => $row['total'],
        ]);
    }
}
