<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Models\Angket_motivasi;
use App\Models\AngketMinat;
use App\Models\Kelas;
use App\Models\Observasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpKelompokController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $ahpData = null;

        // Jika ada kelas_id di request, hitung AHP
        if ($request->has('kelas_id')) {
            $kelas_id = $request->get('kelas_id');

            try {
                // Ambil data kelas
                $kelasData = Kelas::findOrFail($kelas_id);

                // Hitung rata-rata nilai per kelas
                $avgMinat = AngketMinat::whereHas('siswa', function ($query) use ($kelas_id) {
                    $query->where('kelas_id', $kelas_id);
                })->avg('total');

                $avgMotivasi = Angket_motivasi::whereHas('siswa', function ($query) use ($kelas_id) {
                    $query->where('kelas_id', $kelas_id);
                })->avg('total');

                $avgObservasi = Observasi::where('kelas_id', $kelas_id)->avg('total');

                // Cek apakah data lengkap
                if ($avgMinat && $avgMotivasi && $avgObservasi) {
                    // Hitung AHP
                    $ahpResult = $this->calculateAHP($avgMinat, $avgMotivasi, $avgObservasi);

                    // Siapkan data untuk view
                    $ahpData = [
                        'success' => true,
                        'kelas' => [
                            'id' => $kelasData->id,
                            'nama' => $kelasData->name
                        ],
                        'rata_rata_nilai' => [
                            'minat' => round($avgMinat, 2),
                            'motivasi' => round($avgMotivasi, 2),
                            'observasi' => round($avgObservasi, 2)
                        ],
                        'ahp' => $ahpResult
                    ];
                }
            } catch (\Exception $e) {
                // Handle error jika diperlukan
                $ahpData = [
                    'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                ];
            }
        }

        return view('data_penelitian.ahp-kelompok.index', compact('kelas', 'ahpData'));
    }

    public function getClassAHP($kelas_id)
    {
        try {
            // Ambil data kelas
            $kelas = Kelas::findOrFail($kelas_id);

            // Hitung rata-rata nilai per kelas (bukan per siswa)
            // Rata-rata minat dari siswa di kelas ini
            $avgMinat = AngketMinat::whereHas('siswa', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })->avg('total');

            // Rata-rata motivasi dari siswa di kelas ini
            $avgMotivasi = Angket_motivasi::whereHas('siswa', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })->avg('total');

            // Rata-rata observasi dari data observasi kelas
            $avgObservasi = Observasi::where('kelas_id', $kelas_id)->avg('total');

            // Validasi data lengkap
            if (!$avgMinat || !$avgMotivasi || !$avgObservasi) {
                return response()->json([
                    'error' => 'Data tidak lengkap untuk kelas ini',
                    'missing' => [
                        'minat' => !$avgMinat,
                        'motivasi' => !$avgMotivasi,
                        'observasi' => !$avgObservasi
                    ]
                ], 400);
            }

            // Hitung AHP berdasarkan rata-rata kelas
            $ahpResult = $this->calculateAHP($avgMinat, $avgMotivasi, $avgObservasi);

            return response()->json([
                'success' => true,
                'kelas' => [
                    'id' => $kelas->id,
                    'nama' => $kelas->nama
                ],
                'rata_rata_nilai' => [
                    'minat' => round($avgMinat, 2),
                    'motivasi' => round($avgMotivasi, 2),
                    'observasi' => round($avgObservasi, 2)
                ],
                'ahp' => $ahpResult
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung AHP menggunakan rata-rata nilai kelas
     */
    private function calculateAHP($minat, $motivasi, $observasi)
    {
        // Validasi input
        if ($minat <= 0 || $motivasi <= 0 || $observasi <= 0) {
            throw new \InvalidArgumentException('Semua nilai harus lebih besar dari 0');
        }

        // LANGKAH 1: Membuat Matriks Perbandingan Berpasangan
        $matrix = [
            'minat' => [
                'minat' => 1,
                'motivasi' => $minat / $motivasi,
                'observasi' => $minat / $observasi
            ],
            'motivasi' => [
                'minat' => $motivasi / $minat,
                'motivasi' => 1,
                'observasi' => $motivasi / $observasi
            ],
            'observasi' => [
                'minat' => $observasi / $minat,
                'motivasi' => $observasi / $motivasi,
                'observasi' => 1
            ]
        ];

        // LANGKAH 2: Hitung jumlah setiap kolom
        $columnSums = [
            'minat' => $matrix['minat']['minat'] + $matrix['motivasi']['minat'] + $matrix['observasi']['minat'],
            'motivasi' => $matrix['minat']['motivasi'] + $matrix['motivasi']['motivasi'] + $matrix['observasi']['motivasi'],
            'observasi' => $matrix['minat']['observasi'] + $matrix['motivasi']['observasi'] + $matrix['observasi']['observasi']
        ];

        // LANGKAH 3: Normalisasi matriks
        $normalized = [
            'minat' => [
                'minat' => $matrix['minat']['minat'] / $columnSums['minat'],
                'motivasi' => $matrix['minat']['motivasi'] / $columnSums['motivasi'],
                'observasi' => $matrix['minat']['observasi'] / $columnSums['observasi']
            ],
            'motivasi' => [
                'minat' => $matrix['motivasi']['minat'] / $columnSums['minat'],
                'motivasi' => $matrix['motivasi']['motivasi'] / $columnSums['motivasi'],
                'observasi' => $matrix['motivasi']['observasi'] / $columnSums['observasi']
            ],
            'observasi' => [
                'minat' => $matrix['observasi']['minat'] / $columnSums['minat'],
                'motivasi' => $matrix['observasi']['motivasi'] / $columnSums['motivasi'],
                'observasi' => $matrix['observasi']['observasi'] / $columnSums['observasi']
            ]
        ];

        // LANGKAH 4: Hitung bobot (rata-rata setiap baris)
        $weights = [
            'minat' => ($normalized['minat']['minat'] + $normalized['minat']['motivasi'] + $normalized['minat']['observasi']) / 3,
            'motivasi' => ($normalized['motivasi']['minat'] + $normalized['motivasi']['motivasi'] + $normalized['motivasi']['observasi']) / 3,
            'observasi' => ($normalized['observasi']['minat'] + $normalized['observasi']['motivasi'] + $normalized['observasi']['observasi']) / 3
        ];

        // LANGKAH 5: Hitung Consistency Ratio (CR) untuk validasi
        $cr = $this->calculateConsistencyRatio($matrix, $weights);

        // LANGKAH 6: Konversi ke persentase
        $percentages = [
            'minat' => round($weights['minat'] * 100, 1),
            'motivasi' => round($weights['motivasi'] * 100, 1),
            'observasi' => round($weights['observasi'] * 100, 1)
        ];

        // LANGKAH 7: Tentukan yang dominan
        $maxPercentage = max($percentages);
        $dominantKey = array_search($maxPercentage, $percentages);

        $dominantLabels = [
            'minat' => 'Minat',
            'motivasi' => 'Motivasi',
            'observasi' => 'Observasi'
        ];

        // Ranking
        arsort($percentages);
        $ranking = [];
        $rank = 1;
        foreach ($percentages as $key => $value) {
            $ranking[] = [
                'rank' => $rank++,
                'criteria' => $dominantLabels[$key],
                'percentage' => $value
            ];
        }

        return [
            'weights' => [
                'minat' => round($weights['minat'], 3),
                'motivasi' => round($weights['motivasi'], 3),
                'observasi' => round($weights['observasi'], 3)
            ],
            'percentages' => [
                'minat' => $percentages['minat'],
                'motivasi' => $percentages['motivasi'],
                'observasi' => $percentages['observasi']
            ],
            'dominan' => $dominantLabels[$dominantKey],
            'persen_dominan' => $maxPercentage,
            'ranking' => $ranking,
            'consistency_ratio' => $cr,
            'is_consistent' => $cr <= 0.1, // CR harus <= 0.1 untuk konsisten
            'matrix' => [
                'original' => $matrix,
                'normalized' => $normalized,
                'column_sums' => $columnSums
            ]
        ];
    }

    /**
     * Hitung Consistency Ratio untuk validasi AHP
     */
    private function calculateConsistencyRatio($matrix, $weights)
    {
        // Hitung weighted sum vector
        $weightedSum = [
            'minat' => ($matrix['minat']['minat'] * $weights['minat']) +
                ($matrix['minat']['motivasi'] * $weights['motivasi']) +
                ($matrix['minat']['observasi'] * $weights['observasi']),
            'motivasi' => ($matrix['motivasi']['minat'] * $weights['minat']) +
                ($matrix['motivasi']['motivasi'] * $weights['motivasi']) +
                ($matrix['motivasi']['observasi'] * $weights['observasi']),
            'observasi' => ($matrix['observasi']['minat'] * $weights['minat']) +
                ($matrix['observasi']['motivasi'] * $weights['motivasi']) +
                ($matrix['observasi']['observasi'] * $weights['observasi'])
        ];

        // Hitung λmax
        $lambdaMax = ($weightedSum['minat'] / $weights['minat'] +
            $weightedSum['motivasi'] / $weights['motivasi'] +
            $weightedSum['observasi'] / $weights['observasi']) / 3;

        // Hitung Consistency Index (CI)
        $n = 3; // jumlah kriteria
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random Index untuk n=3
        $ri = 0.58;

        // Hitung Consistency Ratio (CR)
        $cr = $ci / $ri;

        return round($cr, 4);
    }

    /**
     * Hitung AHP untuk semua kelas
     */
    public function calculateAll()
    {
        try {
            $results = [];

            // Ambil semua kelas
            $kelasList = Kelas::all();

            foreach ($kelasList as $kelas) {
                // Hitung rata-rata nilai per kelas
                $avgMinat = AngketMinat::whereHas('siswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })->avg('total');

                $avgMotivasi = Angket_motivasi::whereHas('siswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })->avg('total');

                $avgObservasi = Observasi::where('kelas_id', $kelas->id)->avg('total');

                // Skip jika data tidak lengkap
                if (!$avgMinat || !$avgMotivasi || !$avgObservasi) {
                    continue;
                }

                $ahpResult = $this->calculateAHP($avgMinat, $avgMotivasi, $avgObservasi);

                $results[] = [
                    'kelas_id' => $kelas->id,
                    'nama_kelas' => $kelas->nama,
                    'rata_rata_nilai' => [
                        'minat' => round($avgMinat, 2),
                        'motivasi' => round($avgMotivasi, 2),
                        'observasi' => round($avgObservasi, 2)
                    ],
                    'dominan' => $ahpResult['dominan'],
                    'persen_dominan' => $ahpResult['persen_dominan'],
                    'percentages' => $ahpResult['percentages'],
                    'consistency_ratio' => $ahpResult['consistency_ratio'],
                    'is_consistent' => $ahpResult['is_consistent']
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'summary' => [
                    'total_kelas' => count($results),
                    'dominan_minat' => count(array_filter($results, fn($r) => $r['dominan'] === 'Minat')),
                    'dominan_motivasi' => count(array_filter($results, fn($r) => $r['dominan'] === 'Motivasi')),
                    'dominan_observasi' => count(array_filter($results, fn($r) => $r['dominan'] === 'Observasi')),
                    'konsisten' => count(array_filter($results, fn($r) => $r['is_consistent'] === true))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
