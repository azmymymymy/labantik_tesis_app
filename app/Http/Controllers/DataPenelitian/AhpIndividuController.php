<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Models\Angket_motivasi;
use App\Models\AngketMinat;
use App\Models\Observasi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // <-- TAMBAHKAN INI


class AhpIndividuController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $siswa = Siswa::all();
        return view('data_penelitian.ahp_individu.index', compact('kelas','siswa'));
    }

    /**
     * Search siswa berdasarkan nama untuk dropdown Ajax
     */
    public function searchSiswa(Request $request)
{
    Log::info('=== SEARCH SISWA CALLED ===');
    
    try {
        $query = $request->get('q', '');
        Log::info('Query: ' . $query);
        
        if (strlen($query) < 2) {
            Log::info('Query too short, returning empty');
            return response()->json([]);
        }

        // Test basic DB connection first
        $total_siswa = DB::table('siswa')->count();
        Log::info('Total siswa in DB: ' . $total_siswa);

        // Main query
        $siswa = DB::table('siswa')
                   ->where('nama', 'LIKE', '%' . $query . '%')
                   ->select('id', 'nama')
                   ->limit(10)
                   ->get();

        Log::info('Found ' . $siswa->count() . ' students');
        Log::info('Students data: ' . json_encode($siswa));

        $results = $siswa->map(function($s) {
            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'text' => $s->nama
            ];
        });

        Log::info('Final results: ' . json_encode($results));

        return response()->json($results);

    } catch (\Exception $e) {
        Log::error('=== SEARCH ERROR ===');
        Log::error('Message: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'error' => $e->getMessage(),
            'debug' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }}
    
    /**
     * Get data siswa dari ketiga tabel berdasarkan siswa_id
     */
    public function getStudentData($siswa_id)
    {
        // Get data from all three tables
        $minat = AngketMinat::where('siswa_id', $siswa_id)->first();
        $motivasi = Angket_motivasi::where('siswa_id', $siswa_id)->first();
        $observasi = Observasi::where('siswa_id', $siswa_id)->first();
        $siswa = Siswa::with('kelas')->find($siswa_id);

        return [
            'siswa' => $siswa,
            'minat' => $minat,
            'motivasi' => $motivasi,
            'observasi' => $observasi
        ];
    }

    /**
     * Calculate AHP untuk siswa tertentu
     * 
     * 
     */
    public function getSiswaList()
{
    try {
        $siswa = Siswa::select('id', 'nama', 'kelas_id')
                     ->orderBy('nama', 'asc')
                     ->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data siswa'
        ], 500);
    }
}
    public function calculateAHP(Request $request, $siswa_id)
    {
        try {
            // Get student data
            $data = $this->getStudentData($siswa_id);
            
            // Validate if all data exists
            if (!$data['siswa'] || !$data['minat'] || !$data['motivasi'] || !$data['observasi']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak lengkap untuk siswa ini'
                ]);
            }

            $minat_total = $data['minat']->total ?? 0;
            $motivasi_total = $data['motivasi']->total ?? 0;
            $observasi_total = $data['observasi']->total ?? 0;

            // Calculate AHP
            $ahp_result = $this->performAHPCalculation($minat_total, $motivasi_total, $observasi_total);

            return response()->json([
                'success' => true,
                'data' => [
                    'siswa' => [
                        'id' => $data['siswa']->id,
                        'nama' => $data['siswa']->nama,
                        'kelas' => $data['siswa']->kelas->name ?? 'No Class'
                    ],
                    'raw_scores' => [
                        'minat' => $minat_total,
                        'motivasi' => $motivasi_total,
                        'observasi' => $observasi_total
                    ],
                    'ahp_result' => $ahp_result
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating AHP: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Core AHP calculation method
     */
    private function performAHPCalculation($minat, $motivasi, $observasi)
    {
        // Step 1: Create comparison matrix
        $comparison_matrix = $this->createComparisonMatrix($minat, $motivasi, $observasi);
        
        // Step 2: Normalize matrix
        $normalized_matrix = $this->normalizeMatrix($comparison_matrix);
        
        // Step 3: Calculate weights (priority vector)
        $weights = $this->calculateWeights($normalized_matrix);
        
        // Step 4: Calculate consistency ratio
        $consistency = $this->calculateConsistency($comparison_matrix, $weights);
        
        // Step 5: Determine dominance
        $dominance = $this->determineDominance($weights);
        
        return [
            'comparison_matrix' => $comparison_matrix,
            'normalized_matrix' => $normalized_matrix,
            'weights' => $weights,
            'consistency_ratio' => $consistency,
            'dominance' => $dominance,
            'raw_values' => [
                'minat' => $minat,
                'motivasi' => $motivasi,
                'observasi' => $observasi
            ]
        ];
    }

    /**
     * Create pairwise comparison matrix
     */
    private function createComparisonMatrix($minat, $motivasi, $observasi)
    {
        // Avoid division by zero
        $minat = max($minat, 1);
        $motivasi = max($motivasi, 1);
        $observasi = max($observasi, 1);

        $matrix = [
            [1, $minat/$motivasi, $minat/$observasi],
            [$motivasi/$minat, 1, $motivasi/$observasi],
            [$observasi/$minat, $observasi/$motivasi, 1]
        ];

        return [
            'matrix' => $matrix,
            'labels' => ['Minat', 'Motivasi', 'Observasi'],
            'formatted' => [
                ['', 'Minat', 'Motivasi', 'Observasi'],
                ['Minat', round($matrix[0][0], 4), round($matrix[0][1], 4), round($matrix[0][2], 4)],
                ['Motivasi', round($matrix[1][0], 4), round($matrix[1][1], 4), round($matrix[1][2], 4)],
                ['Observasi', round($matrix[2][0], 4), round($matrix[2][1], 4), round($matrix[2][2], 4)]
            ]
        ];
    }

    /**
     * Normalize the comparison matrix
     */
    private function normalizeMatrix($comparison_data)
    {
        $matrix = $comparison_data['matrix'];
        $n = count($matrix);
        
        // Calculate column sums
        $column_sums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $column_sums[$j] = $sum;
        }
        
        // Normalize each element
        $normalized = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $column_sums[$j];
            }
        }

        return [
            'matrix' => $normalized,
            'column_sums' => $column_sums,
            'formatted' => [
                ['', 'Minat', 'Motivasi', 'Observasi'],
                ['Minat', round($normalized[0][0], 4), round($normalized[0][1], 4), round($normalized[0][2], 4)],
                ['Motivasi', round($normalized[1][0], 4), round($normalized[1][1], 4), round($normalized[1][2], 4)],
                ['Observasi', round($normalized[2][0], 4), round($normalized[2][1], 4), round($normalized[2][2], 4)]
            ]
        ];
    }

    /**
     * Calculate priority weights from normalized matrix
     */
    private function calculateWeights($normalized_data)
    {
        $matrix = $normalized_data['matrix'];
        $n = count($matrix);
        
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j];
            }
            $weights[$i] = $sum / $n;
        }

        $labels = ['Minat', 'Motivasi', 'Observasi'];
        $percentages = array_map(function($w) { return round($w * 100, 2); }, $weights);

        return [
            'raw' => $weights,
            'percentages' => $percentages,
            'labeled' => [
                'Minat' => $percentages[0],
                'Motivasi' => $percentages[1],
                'Observasi' => $percentages[2]
            ]
        ];
    }

    /**
     * Calculate consistency ratio
     */
    private function calculateConsistency($comparison_data, $weights_data)
    {
        $matrix = $comparison_data['matrix'];
        $weights = $weights_data['raw'];
        $n = count($matrix);
        
        // Calculate weighted sum vector
        $weighted_sum = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weighted_sum[$i] = $sum;
        }
        
        // Calculate lambda max
        $lambda_max = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambda_max += $weighted_sum[$i] / $weights[$i];
            }
        }
        $lambda_max = $lambda_max / $n;
        
        // Calculate CI and CR
        $ci = ($lambda_max - $n) / ($n - 1);
        $ri = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41]; // Random Index
        $cr = $n > 2 ? $ci / $ri[$n-1] : 0;
        
        return [
            'lambda_max' => round($lambda_max, 4),
            'ci' => round($ci, 4),
            'ri' => $ri[$n-1] ?? 0,
            'cr' => round($cr, 4),
            'is_consistent' => $cr < 0.1
        ];
    }

    /**
     * Determine dominance based on weights
     */
    private function determineDominance($weights_data)
    {
        $labeled = $weights_data['labeled'];
        
        // Sort by percentage
        arsort($labeled);
        
        $ranking = [];
        $rank = 1;
        foreach ($labeled as $criteria => $percentage) {
            $ranking[] = [
                'rank' => $rank++,
                'criteria' => $criteria,
                'percentage' => $percentage
            ];
        }
        
        $dominant = $ranking[0];
        
        return [
            'dominant_criteria' => $dominant['criteria'],
            'dominant_percentage' => $dominant['percentage'],
            'ranking' => $ranking,
            'interpretation' => $this->getInterpretation($dominant['criteria'], $dominant['percentage'])
        ];
    }

    /**
     * Get interpretation of the dominant criteria
     */
    private function getInterpretation($criteria, $percentage)
    {
        $level = '';
        if ($percentage >= 50) {
            $level = 'sangat dominan';
        } elseif ($percentage >= 40) {
            $level = 'dominan';
        } elseif ($percentage >= 35) {
            $level = 'cukup berpengaruh';
        } else {
            $level = 'sedikit berpengaruh';
        }

        return "{$criteria} {$level} terhadap hasil belajar siswa dengan kontribusi sebesar {$percentage}%.";
    }

    /**
     * Get all students with their AHP calculations (for bulk analysis)
     */
    public function bulkAnalysis(Request $request)
    {
        $kelas_id = $request->get('kelas_id');
        
        $query = Siswa::with(['angketMinat', 'angketMotivasi', 'observasi', 'kelas']);
        
        if ($kelas_id) {
            $query->where('kelas_id', $kelas_id);
        }
        
        $students = $query->get();
        
        $results = [];
        
        foreach ($students as $siswa) {
            if ($siswa->angketMinat && $siswa->angketMotivasi && $siswa->observasi) {
                $ahp_result = $this->performAHPCalculation(
                    $siswa->angketMinat->total,
                    $siswa->angketMotivasi->total,
                    $siswa->observasi->total
                );
                
                $results[] = [
                    'siswa' => [
                        'id' => $siswa->id,
                        'nama' => $siswa->nama,
                        'kelas' => $siswa->kelas->name ?? 'No Class'
                    ],
                    'raw_scores' => [
                        'minat' => $siswa->angketMinat->total,
                        'motivasi' => $siswa->angketMotivasi->total,
                        'observasi' => $siswa->observasi->total
                    ],
                    'dominance' => $ahp_result['dominance']
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $results,
            'summary' => $this->generateSummary($results)
        ]);
    }

    /**
     * Generate summary statistics
     */
    private function generateSummary($results)
    {
        $criteria_count = [
            'Minat' => 0,
            'Motivasi' => 0,
            'Observasi' => 0
        ];
        
        foreach ($results as $result) {
            $dominant = $result['dominance']['dominant_criteria'];
            if (isset($criteria_count[$dominant])) {
                $criteria_count[$dominant]++;
            }
        }
        
        $total = count($results);
        $percentages = [];
        
        foreach ($criteria_count as $criteria => $count) {
            $percentages[$criteria] = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        }
        
        return [
            'total_students' => $total,
            'criteria_distribution' => $criteria_count,
            'criteria_percentages' => $percentages
        ];
    }
}