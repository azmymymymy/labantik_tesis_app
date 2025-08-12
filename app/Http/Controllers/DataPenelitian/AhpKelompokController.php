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
    public function index()
    {
        // Ambil rata-rata keseluruhan dari database tanpa filter kelas
        $avgMinatRaw = AngketMinat::avg('total');
        $avgMotivasiRaw = Angket_motivasi::avg('total');
        $avgObservasiRaw = Observasi::avg('total');

        // Konversi ke persentase berdasarkan skala masing-masing
        $avgMinat = $avgMinatRaw ? number_format(($avgMinatRaw / 70) * 100, 1) : 0;
        $avgMotivasi = $avgMotivasiRaw ? number_format(($avgMotivasiRaw / 40) * 100, 1) : 0;
        $avgObservasi = $avgObservasiRaw ? number_format(($avgObservasiRaw / 35) * 100, 1) : 0;

        $ahpData = null;

        if ($avgMinat && $avgMotivasi && $avgObservasi) {
            // Hitung AHP dari nilai persentase keseluruhan
            $ahpResult = $this->calculateAHPProcess($avgMinat, $avgMotivasi, $avgObservasi);

            $ahpData = [
                'success' => true,
                'data_mentah' => [
                    'minat_raw' => round($avgMinatRaw, 2),
                    'motivasi_raw' => round($avgMotivasiRaw, 2),
                    'observasi_raw' => round($avgObservasiRaw, 2)
                ],
                'rata_rata_nilai' => [
                    'minat' => $avgMinat,
                    'motivasi' => $avgMotivasi,
                    'observasi' => $avgObservasi
                ],
                'total_data' => [
                    'minat' => AngketMinat::count(),
                    'motivasi' => Angket_motivasi::count(),
                    'observasi' => Observasi::count()
                ],
                'ahp' => $ahpResult
            ];
        } else {
            $ahpData = [
                'error' => 'Data belum lengkap atau kosong'
            ];
        }

        return view('data_penelitian.ahp-kelompok.index', compact('ahpData'));
    }

    /**
     * Hitung AHP dari semua data tanpa pengelompokan kelas
     */
    public function calculateAHP()
    {
        try {
            // Ambil rata-rata langsung dari semua data di database
            $avgMinatRaw = AngketMinat::avg('total');
            $avgMotivasiRaw = Angket_motivasi::avg('total');
            $avgObservasiRaw = Observasi::avg('total');

            // Validasi data ada
            if (!$avgMinatRaw || !$avgMotivasiRaw || !$avgObservasiRaw) {
                return response()->json([
                    'error' => 'Data tidak lengkap atau kosong',
                    'missing' => [
                        'minat' => !$avgMinatRaw,
                        'motivasi' => !$avgMotivasiRaw,
                        'observasi' => !$avgObservasiRaw
                    ]
                ], 400);
            }

            // Normalisasi sesuai batas max masing-masing
            $avgMinat = ($avgMinatRaw / 70) * 100;
            $avgMotivasi = ($avgMotivasiRaw / 40) * 100;
            $avgObservasi = ($avgObservasiRaw / 35) * 100;

            // Hitung AHP
            $ahpResult = $this->calculateAHPProcess($avgMinat, $avgMotivasi, $avgObservasi);

            return response()->json([
                'success' => true,
                'data_mentah' => [
                    'minat_raw' => round($avgMinatRaw, 2),
                    'motivasi_raw' => round($avgMotivasiRaw, 2),
                    'observasi_raw' => round($avgObservasiRaw, 2)
                ],
                'rata_rata_nilai' => [
                    'minat' => round($avgMinat, 2),
                    'motivasi' => round($avgMotivasi, 2),
                    'observasi' => round($avgObservasi, 2)
                ],
                'ahp' => $ahpResult,
                'total_data' => [
                    'minat' => AngketMinat::count(),
                    'motivasi' => Angket_motivasi::count(),
                    'observasi' => Observasi::count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung AHP menggunakan rata-rata nilai
     */
    private function calculateAHPProcess($minat, $motivasi, $observasi)
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
        $sortedPercentages = $percentages;
        arsort($sortedPercentages);
        $ranking = [];
        $rank = 1;
        foreach ($sortedPercentages as $key => $value) {
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
}
