<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Models\Angket_motivasi;
use App\Models\AngketMinat;
use App\Models\Hasil_belajar;
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

        // Ambil rata-rata pretest dan posttest
        $avgPretestRaw = Hasil_belajar::avg('pretest');
        $avgPosttestRaw = Hasil_belajar::avg('posttest');

        // Konversi ke persentase berdasarkan skala masing-masing
        $avgMinat = $avgMinatRaw ? number_format(($avgMinatRaw / 70) * 100, 1) : 0;
        $avgMotivasi = $avgMotivasiRaw ? number_format(($avgMotivasiRaw / 50) * 100, 1) : 0;
        $avgObservasi = $avgObservasiRaw ? number_format(($avgObservasiRaw / 35) * 100, 1) : 0;

        $ahpData = null;

        if ($avgMinat && $avgMotivasi && $avgObservasi) {
            // Hitung AHP dari nilai persentase keseluruhan
            $ahpResult = $this->calculateAHPProcess($avgMinat, $avgMotivasi, $avgObservasi);

            // Hitung Normalized Gain jika data pretest dan posttest tersedia
            $normalizedGainResult = null;
            if ($avgPretestRaw && $avgPosttestRaw) {
                $normalizedGainResult = $this->calculateNormalizedGain($avgPretestRaw, $avgPosttestRaw);
            }

            $ahpData = [
                'success' => true,
                'data_mentah' => [
                    'minat_raw' => round($avgMinatRaw, 2),
                    'motivasi_raw' => round($avgMotivasiRaw, 2),
                    'observasi_raw' => round($avgObservasiRaw, 2),
                    'pretest_raw' => $avgPretestRaw ? round($avgPretestRaw, 2) : null,
                    'posttest_raw' => $avgPosttestRaw ? round($avgPosttestRaw, 2) : null
                ],
                'rata_rata_nilai' => [
                    'minat' => $avgMinat,
                    'motivasi' => $avgMotivasi,
                    'observasi' => $avgObservasi,
                    'pretest' => $avgPretestRaw ? round($avgPretestRaw, 2) : null,
                    'posttest' => $avgPosttestRaw ? round($avgPosttestRaw, 2) : null,
                    'gain_rata_rata' => ($avgPretestRaw && $avgPosttestRaw) ? round($avgPosttestRaw - $avgPretestRaw, 2) : null
                ],
                'total_data' => [
                    'minat' => AngketMinat::count(),
                    'motivasi' => Angket_motivasi::count(),
                    'observasi' => Observasi::count(),
                    'hasil_belajar' => Hasil_belajar::count()
                ],
                'ahp' => $ahpResult,
                'normalized_gain' => $normalizedGainResult
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
            $avgPretestRaw = Hasil_belajar::avg('pretest');
            $avgPosttestRaw = Hasil_belajar::avg('posttest');

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
            $avgMotivasi = ($avgMotivasiRaw / 50) * 100;
            $avgObservasi = ($avgObservasiRaw / 35) * 100;

            // Hitung AHP
            $ahpResult = $this->calculateAHPProcess($avgMinat, $avgMotivasi, $avgObservasi);

            // Hitung Normalized Gain jika data pretest dan posttest tersedia
            $normalizedGainResult = null;
            if ($avgPretestRaw && $avgPosttestRaw) {
                $normalizedGainResult = $this->calculateNormalizedGain($avgPretestRaw, $avgPosttestRaw);
            }

            return response()->json([
                'success' => true,
                'data_mentah' => [
                    'minat_raw' => round($avgMinatRaw, 2),
                    'motivasi_raw' => round($avgMotivasiRaw, 2),
                    'observasi_raw' => round($avgObservasiRaw, 2),
                    'pretest_raw' => $avgPretestRaw ? round($avgPretestRaw, 2) : null,
                    'posttest_raw' => $avgPosttestRaw ? round($avgPosttestRaw, 2) : null
                ],
                'rata_rata_nilai' => [
                    'minat' => round($avgMinat, 2),
                    'motivasi' => round($avgMotivasi, 2),
                    'observasi' => round($avgObservasi, 2),
                    'pretest' => $avgPretestRaw ? round($avgPretestRaw, 2) : null,
                    'posttest' => $avgPosttestRaw ? round($avgPosttestRaw, 2) : null,
                    'gain_rata_rata' => ($avgPretestRaw && $avgPosttestRaw) ? round($avgPosttestRaw - $avgPretestRaw, 2) : null
                ],
                'ahp' => $ahpResult,
                'normalized_gain' => $normalizedGainResult,
                'total_data' => [
                    'minat' => AngketMinat::count(),
                    'motivasi' => Angket_motivasi::count(),
                    'observasi' => Observasi::count(),
                    'hasil_belajar' => Hasil_belajar::count()
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

        // LANGKAH 5: Hitung Consistency Test yang lebih detail
        $consistencyTest = $this->calculateDetailedConsistencyTest($matrix, $weights);

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

        // LANGKAH 8: Hitung skor gabungan menggunakan bobot AHP
        $skorGabungan = ($weights['minat'] * $minat) + ($weights['motivasi'] * $motivasi) + ($weights['observasi'] * $observasi);

        // LANGKAH 9: Tambahkan summary statistik untuk analisis lebih lanjut
        $summaryStatistics = $this->calculateSummaryStatistics($minat, $motivasi, $observasi);

        return [
            'weights' => [
                'minat' => round($weights['minat'], 6),
                'motivasi' => round($weights['motivasi'], 6),
                'observasi' => round($weights['observasi'], 6)
            ],
            'percentages' => [
                'minat' => $percentages['minat'],
                'motivasi' => $percentages['motivasi'],
                'observasi' => $percentages['observasi']
            ],
            'dominan' => $dominantLabels[$dominantKey],
            'persen_dominan' => $maxPercentage,
            'ranking' => $ranking,
            'skor_gabungan' => round($skorGabungan, 2),
            'summary_statistics' => $summaryStatistics,
            'consistency_test' => $consistencyTest,
            'matrix' => [
                'original' => $matrix,
                'normalized' => $normalized,
                'column_sums' => $columnSums
            ]
        ];
    }

    /**
     * Hitung Consistency Test yang lebih detail sesuai dengan dokumen
     */
    private function calculateDetailedConsistencyTest($matrix, $weights)
    {
        // LANGKAH 1: Kalikan masing-masing baris dalam matriks awal dengan bobot
        $weightedMatrix = [
            'minat' => [
                'result' => ($matrix['minat']['minat'] * $weights['minat']) +
                    ($matrix['minat']['motivasi'] * $weights['motivasi']) +
                    ($matrix['minat']['observasi'] * $weights['observasi']),
                'calculation' => sprintf(
                    "(%.6f × %.6f) + (%.6f × %.6f) + (%.6f × %.6f)",
                    $matrix['minat']['minat'],
                    $weights['minat'],
                    $matrix['minat']['motivasi'],
                    $weights['motivasi'],
                    $matrix['minat']['observasi'],
                    $weights['observasi']
                )
            ],
            'motivasi' => [
                'result' => ($matrix['motivasi']['minat'] * $weights['minat']) +
                    ($matrix['motivasi']['motivasi'] * $weights['motivasi']) +
                    ($matrix['motivasi']['observasi'] * $weights['observasi']),
                'calculation' => sprintf(
                    "(%.6f × %.6f) + (%.6f × %.6f) + (%.6f × %.6f)",
                    $matrix['motivasi']['minat'],
                    $weights['minat'],
                    $matrix['motivasi']['motivasi'],
                    $weights['motivasi'],
                    $matrix['motivasi']['observasi'],
                    $weights['observasi']
                )
            ],
            'observasi' => [
                'result' => ($matrix['observasi']['minat'] * $weights['minat']) +
                    ($matrix['observasi']['motivasi'] * $weights['motivasi']) +
                    ($matrix['observasi']['observasi'] * $weights['observasi']),
                'calculation' => sprintf(
                    "(%.6f × %.6f) + (%.6f × %.6f) + (%.6f × %.6f)",
                    $matrix['observasi']['minat'],
                    $weights['minat'],
                    $matrix['observasi']['motivasi'],
                    $weights['motivasi'],
                    $matrix['observasi']['observasi'],
                    $weights['observasi']
                )
            ]
        ];

        // LANGKAH 2: Hitung λ (lambda) untuk setiap kriteria
        $lambdas = [
            'minat' => $weightedMatrix['minat']['result'] / $weights['minat'],
            'motivasi' => $weightedMatrix['motivasi']['result'] / $weights['motivasi'],
            'observasi' => $weightedMatrix['observasi']['result'] / $weights['observasi']
        ];

        // LANGKAH 3: Hitung λ_max (rata-rata dari semua lambda)
        $lambdaMax = ($lambdas['minat'] + $lambdas['motivasi'] + $lambdas['observasi']) / 3;

        // LANGKAH 4: Hitung Consistency Index (CI)
        $n = 3; // jumlah kriteria
        $ci = ($lambdaMax - $n) / ($n - 1);

        // LANGKAH 5: Random Index untuk n=3
        $ri = 0.58;

        // LANGKAH 6: Hitung Consistency Ratio (CR)
        $cr = $ri > 0 ? $ci / $ri : 0;

        // LANGKAH 7: Tentukan status konsistensi
        $isConsistent = $cr <= 0.1;
        $consistencyStatus = '';

        if ($cr == 0) {
            $consistencyStatus = 'Konsisten Sempurna';
        } elseif ($cr <= 0.05) {
            $consistencyStatus = 'Sangat Konsisten';
        } elseif ($cr <= 0.1) {
            $consistencyStatus = 'Konsisten';
        } else {
            $consistencyStatus = 'Tidak Konsisten';
        }

        return [
            'weighted_matrix_results' => [
                'minat' => round($weightedMatrix['minat']['result'], 6),
                'motivasi' => round($weightedMatrix['motivasi']['result'], 6),
                'observasi' => round($weightedMatrix['observasi']['result'], 6)
            ],
            'weighted_matrix_calculations' => [
                'minat' => $weightedMatrix['minat']['calculation'],
                'motivasi' => $weightedMatrix['motivasi']['calculation'],
                'observasi' => $weightedMatrix['observasi']['calculation']
            ],
            'lambdas' => [
                'minat' => round($lambdas['minat'], 6),
                'motivasi' => round($lambdas['motivasi'], 6),
                'observasi' => round($lambdas['observasi'], 6)
            ],
            'lambda_max' => round($lambdaMax, 6),
            'consistency_index' => round($ci, 6),
            'random_index' => $ri,
            'consistency_ratio' => round($cr, 6),
            'is_consistent' => $isConsistent,
            'consistency_status' => $consistencyStatus,
            'interpretation' => $this->getConsistencyInterpretation($cr, $lambdaMax)
        ];
    }

    /**
     * Berikan interpretasi hasil konsistensi
     */
    private function getConsistencyInterpretation($cr, $lambdaMax)
    {
        $interpretation = [];

        if ($cr == 0) {
            $interpretation[] = "Matriks yang dihasilkan bersifat positif dan konsisten sempurna karena dibentuk dari rasio nilai aktual.";
            $interpretation[] = "λ_max = " . round($lambdaMax, 6) . " menunjukkan konsistensi ideal.";
        } elseif ($cr <= 0.05) {
            $interpretation[] = "Penilaian perbandingan berpasangan sangat konsisten (CR = " . round($cr, 4) . ").";
            $interpretation[] = "Hasil bobot dapat diandalkan untuk pengambilan keputusan.";
        } elseif ($cr <= 0.1) {
            $interpretation[] = "Penilaian perbandingan berpasangan masih dalam batas konsistensi yang dapat diterima (CR = " . round($cr, 4) . ").";
            $interpretation[] = "Hasil bobot cukup valid untuk digunakan.";
        } else {
            $interpretation[] = "Penilaian perbandingan berpasangan tidak konsisten (CR = " . round($cr, 4) . " > 0.1).";
            $interpretation[] = "Disarankan untuk meninjau kembali penilaian perbandingan atau menggunakan metode lain.";
        }

        return $interpretation;
    }

    /**
     * Hitung summary statistics untuk analisis tambahan
     */
    private function calculateSummaryStatistics($minat, $motivasi, $observasi)
    {
        $values = [$minat, $motivasi, $observasi];
        $labels = ['Minat', 'Motivasi', 'Observasi'];

        $total = array_sum($values);
        $mean = $total / count($values);
        $max = max($values);
        $min = min($values);
        $range = $max - $min;

        // Hitung standar deviasi
        $variance = 0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        $variance = $variance / count($values);
        $stdDev = sqrt($variance);

        // Koefisien variasi
        $coefficientOfVariation = ($mean != 0) ? ($stdDev / $mean) * 100 : 0;

        // Indeks dominasi (seberapa jauh nilai tertinggi dari rata-rata)
        $dominanceIndex = (($max - $mean) / $mean) * 100;

        return [
            'values' => array_combine($labels, $values),
            'total' => round($total, 2),
            'mean' => round($mean, 2),
            'max' => $max,
            'min' => $min,
            'range' => round($range, 2),
            'standard_deviation' => round($stdDev, 2),
            'coefficient_of_variation' => round($coefficientOfVariation, 2),
            'dominance_index' => round($dominanceIndex, 2),
            'interpretation' => $this->getSummaryInterpretation($coefficientOfVariation, $dominanceIndex, $range)
        ];
    }

    /**
     * Interpretasi summary statistics
     */
    private function getSummaryInterpretation($cv, $dominanceIndex, $range)
    {
        $interpretation = [];

        // Interpretasi Coefficient of Variation
        if ($cv < 10) {
            $interpretation[] = "Variabilitas rendah (CV = {$cv}%) - ketiga faktor relatif seimbang.";
        } elseif ($cv < 20) {
            $interpretation[] = "Variabilitas sedang (CV = {$cv}%) - ada perbedaan moderat antar faktor.";
        } else {
            $interpretation[] = "Variabilitas tinggi (CV = {$cv}%) - ada ketimpangan signifikan antar faktor.";
        }

        // Interpretasi Dominance Index
        if ($dominanceIndex < 15) {
            $interpretation[] = "Dominasi lemah - faktor tertinggi tidak terlalu menonjol dari rata-rata.";
        } elseif ($dominanceIndex < 30) {
            $interpretation[] = "Dominasi sedang - faktor tertinggi cukup menonjol dari rata-rata.";
        } else {
            $interpretation[] = "Dominasi kuat - faktor tertinggi sangat menonjol dari rata-rata.";
        }

        // Interpretasi Range
        if ($range < 10) {
            $interpretation[] = "Rentang nilai sempit ({$range}) - performa ketiga faktor relatif homogen.";
        } elseif ($range < 25) {
            $interpretation[] = "Rentang nilai sedang ({$range}) - ada perbedaan yang cukup berarti antar faktor.";
        } else {
            $interpretation[] = "Rentang nilai lebar ({$range}) - terdapat kesenjangan besar antar faktor.";
        }

        return $interpretation;
    }

    /**
     * Method untuk mendapatkan statistik lengkap hasil belajar
     */
    public function getDetailedLearningStatistics()
    {
        try {
            // Ambil semua data hasil belajar
            $hasilBelajar = Hasil_belajar::select('pretest', 'posttest')->get();

            if ($hasilBelajar->isEmpty()) {
                return response()->json([
                    'error' => 'Data hasil belajar tidak ditemukan'
                ], 400);
            }

            $pretests = $hasilBelajar->pluck('pretest')->toArray();
            $posttests = $hasilBelajar->pluck('posttest')->toArray();
            $gains = [];

            foreach ($hasilBelajar as $data) {
                $gains[] = $data->posttest - $data->pretest;
            }

            // Statistik deskriptif untuk setiap kategori
            $statistics = [
                'pretest' => $this->calculateDescriptiveStats($pretests, 'Pretest'),
                'posttest' => $this->calculateDescriptiveStats($posttests, 'Posttest'),
                'gain' => $this->calculateDescriptiveStats($gains, 'Gain'),
                'overall_analysis' => [
                    'total_samples' => count($hasilBelajar),
                    'average_pretest' => round(array_sum($pretests) / count($pretests), 2),
                    'average_posttest' => round(array_sum($posttests) / count($posttests), 2),
                    'average_gain' => round(array_sum($gains) / count($gains), 2),
                    'normalized_gain' => $this->calculateNormalizedGain(
                        array_sum($pretests) / count($pretests),
                        array_sum($posttests) / count($posttests)
                    )
                ]
            ];

            return response()->json([
                'success' => true,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung statistik deskriptif
     */
    private function calculateDescriptiveStats($data, $label)
    {
        if (empty($data)) {
            return null;
        }

        $count = count($data);
        $sum = array_sum($data);
        $mean = $sum / $count;

        sort($data);
        $min = $data[0];
        $max = $data[$count - 1];
        $range = $max - $min;

        // Median
        if ($count % 2 == 0) {
            $median = ($data[$count / 2 - 1] + $data[$count / 2]) / 2;
        } else {
            $median = $data[floor($count / 2)];
        }

        // Modus (nilai yang paling sering muncul)
        $frequency = array_count_values($data);
        arsort($frequency);
        $mode = array_keys($frequency)[0];
        $modeFreq = $frequency[$mode];

        // Varians dan standar deviasi
        $variance = 0;
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        $variance = $variance / $count;
        $stdDev = sqrt($variance);

        // Quartiles
        $q1Index = floor($count * 0.25);
        $q3Index = floor($count * 0.75);
        $q1 = $data[$q1Index];
        $q3 = $data[$q3Index];
        $iqr = $q3 - $q1;

        return [
            'label' => $label,
            'count' => $count,
            'sum' => round($sum, 2),
            'mean' => round($mean, 2),
            'median' => round($median, 2),
            'mode' => $mode,
            'mode_frequency' => $modeFreq,
            'min' => $min,
            'max' => $max,
            'range' => round($range, 2),
            'variance' => round($variance, 2),
            'standard_deviation' => round($stdDev, 2),
            'q1' => $q1,
            'q3' => $q3,
            'iqr' => round($iqr, 2),
            'coefficient_of_variation' => $mean != 0 ? round(($stdDev / $mean) * 100, 2) : 0
        ];
    }

    /**
     * Method tambahan untuk menghitung Normalized Gain (opsional, jika diperlukan)
     */
    /**
     * Method tambahan untuk menghitung Normalized Gain dengan skor maksimum 100
     */
    public function calculateNormalizedGain($pretest, $posttest, $maxScore = 100)
    {
        $gain = $posttest - $pretest;
        $maxPossibleGain = $maxScore - $pretest;

        if ($maxPossibleGain == 0) {
            return [
                'pretest' => $pretest,
                'posttest' => $posttest,
                'gain_absolut' => $gain,
                'max_possible_gain' => 0,
                'normalized_gain' => 0,
                'category' => 'Tidak dapat dihitung',
                'interpretation' => [
                    'Pretest sudah mencapai skor maksimum.',
                    'Tidak ada ruang untuk peningkatan.'
                ]
            ];
        }

        $normalizedGain = $gain / $maxPossibleGain;

        // Kategori Hake
        $category = '';
        if ($normalizedGain >= 0.7) {
            $category = 'Tinggi';
        } elseif ($normalizedGain >= 0.3) {
            $category = 'Sedang';
        } else {
            $category = 'Rendah';
        }

        return [
            'pretest' => $pretest,
            'posttest' => $posttest,
            'gain_absolut' => $gain,
            'max_possible_gain' => $maxPossibleGain,
            'normalized_gain' => round($normalizedGain, 4),
            'category' => $category,
            'interpretation' => $this->getGainInterpretation($normalizedGain, $gain, $category)
        ];
    }

    /**
     * Interpretasi Normalized Gain dengan penjelasan yang lebih komprehensif
     */
    private function getGainInterpretation($normalizedGain, $absoluteGain, $category)
    {
        $interpretation = [];

        $interpretation[] = "Peningkatan absolut: " . $absoluteGain . " poin dari skor maksimum 100.";
        $interpretation[] = "Normalized Gain (g): " . round($normalizedGain, 4) . " → kategori " . strtolower($category) . ".";

        if ($category == 'Rendah') {
            $interpretation[] = "Intervensi pembelajaran yang diberikan belum optimal meningkatkan capaian hasil belajar.";
            $interpretation[] = "Strategi penguatan minat dan motivasi perlu ditingkatkan secara signifikan.";
            $interpretation[] = "Disarankan untuk melakukan evaluasi menyeluruh terhadap metode pembelajaran yang digunakan.";
        } elseif ($category == 'Sedang') {
            $interpretation[] = "Intervensi pembelajaran menunjukkan efektivitas yang cukup baik.";
            $interpretation[] = "Masih ada ruang untuk optimalisasi strategi pembelajaran.";
            $interpretation[] = "Fokuskan pada faktor-faktor yang memiliki bobot AHP tertinggi untuk meningkatkan hasil.";
        } else {
            $interpretation[] = "Intervensi pembelajaran sangat efektif dalam meningkatkan hasil belajar.";
            $interpretation[] = "Strategi yang diterapkan sudah optimal dan dapat dipertahankan.";
            $interpretation[] = "Dapat dijadikan sebagai model pembelajaran untuk implementasi yang lebih luas.";
        }

        // Tambahkan interpretasi khusus berdasarkan nilai normalized gain
        if ($normalizedGain < 0) {
            $interpretation[] = "Perhatian: Terdapat penurunan hasil belajar yang perlu segera ditangani.";
        } elseif ($normalizedGain > 1) {
            $interpretation[] = "Catatan: Nilai normalized gain > 1 menunjukkan peningkatan yang melebihi ekspektasi maksimal.";
        }

        return $interpretation;
    }

    /**
     * Hitung korelasi antara faktor AHP dengan hasil belajar
     */
    public function calculateCorrelationAnalysis()
    {
        try {
            // Ambil semua data dengan join
            $data = DB::table('angket_minat as am')
                ->leftJoin('angket_motivasi as amv', 'am.siswa_id', '=', 'amv.siswa_id')
                ->leftJoin('observasi as o', 'am.siswa_id', '=', 'o.siswa_id')
                ->leftJoin('hasil_belajar as hb', 'am.siswa_id', '=', 'hb.siswa_id')
                ->select(
                    'am.siswa_id',
                    'am.total as minat_total',
                    'amv.total as motivasi_total',
                    'o.total as observasi_total',
                    'hb.pretest',
                    'hb.posttest'
                )
                ->whereNotNull('am.total')
                ->whereNotNull('amv.total')
                ->whereNotNull('o.total')
                ->whereNotNull('hb.pretest')
                ->whereNotNull('hb.posttest')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'error' => 'Tidak ada data lengkap untuk analisis korelasi'
                ], 400);
            }

            $correlations = [];
            $dataArray = $data->toArray();

            // Hitung korelasi setiap faktor dengan gain (posttest - pretest)
            $gains = [];
            $minatScores = [];
            $motivasiScores = [];
            $observasiScores = [];

            foreach ($dataArray as $row) {
                $gains[] = $row->posttest - $row->pretest;
                $minatScores[] = ($row->minat_total / 70) * 100;
                $motivasiScores[] = ($row->motivasi_total / 50) * 100;
                $observasiScores[] = ($row->observasi_total / 35) * 100;
            }

            // Hitung korelasi Pearson
            $correlations['minat_vs_gain'] = $this->calculateCorrelation($minatScores, $gains);
            $correlations['motivasi_vs_gain'] = $this->calculateCorrelation($motivasiScores, $gains);
            $correlations['observasi_vs_gain'] = $this->calculateCorrelation($observasiScores, $gains);

            // Hitung korelasi dengan posttest
            $posttestScores = array_column($dataArray, 'posttest');
            $correlations['minat_vs_posttest'] = $this->calculateCorrelation($minatScores, $posttestScores);
            $correlations['motivasi_vs_posttest'] = $this->calculateCorrelation($motivasiScores, $posttestScores);
            $correlations['observasi_vs_posttest'] = $this->calculateCorrelation($observasiScores, $posttestScores);

            return response()->json([
                'success' => true,
                'total_samples' => count($dataArray),
                'correlations' => $correlations,
                'interpretation' => $this->getCorrelationInterpretation($correlations)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung korelasi Pearson
     */
    private function calculateCorrelation($x, $y)
    {
        $n = count($x);
        if ($n != count($y) || $n == 0) {
            return null;
        }

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        $sumY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
            $sumY2 += $y[$i] * $y[$i];
        }

        $numerator = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = sqrt((($n * $sumX2) - ($sumX * $sumX)) * (($n * $sumY2) - ($sumY * $sumY)));

        if ($denominator == 0) {
            return 0;
        }

        return round($numerator / $denominator, 4);
    }

    /**
     * Interpretasi korelasi
     */
    private function getCorrelationInterpretation($correlations)
    {
        $interpretation = [];

        foreach ($correlations as $key => $value) {
            if ($value === null) continue;

            $absValue = abs($value);
            $strength = '';

            if ($absValue >= 0.8) {
                $strength = 'sangat kuat';
            } elseif ($absValue >= 0.6) {
                $strength = 'kuat';
            } elseif ($absValue >= 0.4) {
                $strength = 'sedang';
            } elseif ($absValue >= 0.2) {
                $strength = 'lemah';
            } else {
                $strength = 'sangat lemah';
            }

            $direction = $value > 0 ? 'positif' : 'negatif';

            $interpretation[$key] = [
                'correlation' => $value,
                'strength' => $strength,
                'direction' => $direction,
                'description' => "Korelasi $strength dengan arah $direction (r = $value)"
            ];
        }

        return $interpretation;
    }
}
