<?php

namespace App\Imports;

use App\Models\Hasil_belajar;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HasilBelajarImport implements ToModel, WithHeadingRow
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
    public function model(array $row)
    {
        $kelas = Kelas::firstOrCreate(
            ['name' => $row['kelas']],
            [] // default values jika tidak ditemukan
        );

        $siswa = Siswa::firstOrCreate(
            ['nama' => $row['nama']],
            ['kelas_id' => $kelas->id] // default values
        );


        return new Hasil_belajar([
            'siswa_id'      => $siswa->id,
            'kelas_id'      => $kelas->id,
            'pretest'       => $row['pretest'],
            'posttest'      => $row['posttest'],
        ]);
    }
}
