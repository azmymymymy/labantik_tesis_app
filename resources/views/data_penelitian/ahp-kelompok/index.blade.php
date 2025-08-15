@extends('layouts.app')

@section('title', 'Hasil Belajar')
@push('styles')
    <style>
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-default-sec">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Kolom kiri -->
                            <div>
                                <h4 class="card-title mb-1">Perhitungan AHP Kelompok</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item active" aria-current="page">Data Penelitian</li>
                                    </ol>
                                </nav>
                            </div>
                            <!-- Kolom kanan -->
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if (isset($ahpData))
                    <!-- Card 1: Data Awal & Matriks Perbandingan -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Langkah AHP - Data Awal & Matriks Perbandingan</h4>
                            <div class="mb-3">
                                <p><strong>Data Rata-rata Kelompok:</strong></p>
                                <ul
                                    style="list-style: none; padding: 0; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:100px; font-weight: bold;">Minat</span>
                                        = {{ number_format($ahpData['data_mentah']['minat_raw'] ?? 0, 2) }}
                                        <small class="text-muted">(dari {{ $ahpData['total_data']['minat'] ?? 0 }}
                                            data)</small>
                                    </li>
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:100px; font-weight: bold;">Motivasi</span>
                                        = {{ number_format($ahpData['data_mentah']['motivasi_raw'] ?? 0, 2) }}
                                        <small class="text-muted">(dari {{ $ahpData['total_data']['motivasi'] ?? 0 }}
                                            data)</small>
                                    </li>
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:100px; font-weight: bold;">Observasi</span>
                                        = {{ number_format($ahpData['data_mentah']['observasi_raw'] ?? 0, 2) }}
                                        <small class="text-muted">(dari {{ $ahpData['total_data']['observasi'] ?? 0 }}
                                            data)</small>
                                    </li>
                                </ul>
                            </div>

                            <!-- Kolom Kanan: Normalisasi -->
                            <div class="col-md-6">
                                <p><strong>Hasil Normalisasi ke Persentase:</strong></p>
                                <ul
                                    style="list-style: none; padding: 0; background-color: #e8f5e8; padding: 15px; border-radius: 5px;">
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:80px; font-weight: bold;">C1</span> =
                                        Minat:
                                        <strong>{{ $ahpData['rata_rata_nilai']['minat'] }}%</strong>
                                        <br><small
                                            class="text-muted">{{ number_format($ahpData['data_mentah']['minat_raw'] ?? 0, 2) }}
                                            ÷ 70 × 100</small>
                                    </li>
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:80px; font-weight: bold;">C2</span> =
                                        Motivasi:
                                        <strong>{{ $ahpData['rata_rata_nilai']['motivasi'] }}%</strong>
                                        <br><small
                                            class="text-muted">{{ number_format($ahpData['data_mentah']['motivasi_raw'] ?? 0, 2) }}
                                            ÷ 50 × 100</small>
                                    </li>
                                    <li style="margin-bottom: 8px;">
                                        <span style="display:inline-block; width:80px; font-weight: bold;">C3</span> =
                                        Observasi:
                                        <strong>{{ $ahpData['rata_rata_nilai']['observasi'] }}%</strong>
                                        <br><small
                                            class="text-muted">{{ number_format($ahpData['data_mentah']['observasi_raw'] ?? 0, 2) }}
                                            ÷ 35 × 100</small>
                                    </li>
                                </ul>
                            </div>





                            <!-- Matriks Perbandingan Formula -->
                            <div class="table-responsive mt-5">
                                <h6>1. Matriks Perbandingan (Formula)</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>C1/C1</td>
                                            <td>C1/C2</td>
                                            <td>C1/C3</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>C2/C1</td>
                                            <td>C2/C2</td>
                                            <td>C2/C3</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>C3/C1</td>
                                            <td>C3/C2</td>
                                            <td>C3/C3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Matriks Perbandingan Nilai Asli -->
                            <div class="table-responsive mt-5">
                                <h6>2. Matriks Perbandingan (Substitusi Nilai)</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>{{ $ahpData['rata_rata_nilai']['minat'] }}/{{ $ahpData['rata_rata_nilai']['minat'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['minat'] }}/{{ $ahpData['rata_rata_nilai']['motivasi'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['minat'] }}/{{ $ahpData['rata_rata_nilai']['observasi'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>{{ $ahpData['rata_rata_nilai']['motivasi'] }}/{{ $ahpData['rata_rata_nilai']['minat'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['motivasi'] }}/{{ $ahpData['rata_rata_nilai']['motivasi'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['motivasi'] }}/{{ $ahpData['rata_rata_nilai']['observasi'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>{{ $ahpData['rata_rata_nilai']['observasi'] }}/{{ $ahpData['rata_rata_nilai']['minat'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['observasi'] }}/{{ $ahpData['rata_rata_nilai']['motivasi'] }}
                                            </td>
                                            <td>{{ $ahpData['rata_rata_nilai']['observasi'] }}/{{ $ahpData['rata_rata_nilai']['observasi'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Matriks Perbandingan Hasil -->
                            <div class="table-responsive mt-5">
                                <h6>3. Matriks Perbandingan (Hasil Perhitungan)</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <p><strong>Jumlah Kolom:</strong></p>
                                    <ul style="list-style: none; padding: 0;">
                                        <li>Minat:
                                            {{ number_format($ahpData['ahp']['matrix']['column_sums']['minat'], 3) }}</li>
                                        <li>Motivasi:
                                            {{ number_format($ahpData['ahp']['matrix']['column_sums']['motivasi'], 3) }}
                                        </li>
                                        <li>Observasi:
                                            {{ number_format($ahpData['ahp']['matrix']['column_sums']['observasi'], 3) }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Normalisasi -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Normalisasi untuk Mendapat Bobot</h4>

                            <div class="table-responsive mt-4">
                                <h6>Formula Normalisasi</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['minat'], 3) }}
                                                / {{ number_format($ahpData['ahp']['matrix']['column_sums']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['motivasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['minat']['observasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['minat'], 3) }}
                                                / {{ number_format($ahpData['ahp']['matrix']['column_sums']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['motivasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['motivasi']['observasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['minat'], 3) }}
                                                / {{ number_format($ahpData['ahp']['matrix']['column_sums']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['motivasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['original']['observasi']['observasi'], 3) }}
                                                /
                                                {{ number_format($ahpData['ahp']['matrix']['column_sums']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4">
                                <h6>Hasil Normalisasi</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['minat'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['motivasi'], 3) }}
                                            </td>
                                            <td>{{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['observasi'], 3) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <!-- Card 3: Bobot (Weight) -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Bobot (Weight)</h4>

                            <div class="mb-4 mt-4">
                                <h6>Perhitungan Bobot (Rata-rata per baris):</h6>
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 10px">
                                        <span style="display:inline-block; width:80px;"><strong>Minat</strong></span> :
                                        (
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['minat'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['motivasi'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['observasi'], 3) }}
                                        ) / 3 =
                                        {{ $ahpData['ahp']['weights']['minat'] }} =
                                        {{ $ahpData['ahp']['percentages']['minat'] }}%
                                    </li>
                                    <li style="margin-bottom: 10px">
                                        <span style="display:inline-block; width:80px;"><strong>Motivasi</strong></span> :
                                        (
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['minat'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['motivasi'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['motivasi']['observasi'], 3) }}
                                        ) / 3 =
                                        {{ $ahpData['ahp']['weights']['motivasi'] }} =
                                        {{ $ahpData['ahp']['percentages']['motivasi'] }}%
                                    </li>
                                    <li style="margin-bottom: 10px">
                                        <span style="display:inline-block; width:80px;"><strong>Observasi</strong></span> :
                                        (
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['minat'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['motivasi'], 3) }}
                                        +
                                        {{ number_format($ahpData['ahp']['matrix']['normalized']['observasi']['observasi'], 3) }}
                                        ) / 3 =
                                        {{ $ahpData['ahp']['weights']['observasi'] }} =
                                        {{ $ahpData['ahp']['percentages']['observasi'] }}%
                                    </li>
                                </ul>
                            </div>

                            <!-- Ranking -->
                            <div class="table-responsive mt-4">
                                <h6>Ranking Kriteria:</h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ranking</th>
                                            <th>Kriteria</th>
                                            <th>Bobot</th>
                                            <th>Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ahpData['ahp']['ranking'] as $rank)
                                            <tr>
                                                <td>{{ $rank['rank'] }}</td>
                                                <td>{{ $rank['criteria'] }}</td>
                                                <td>{{ $ahpData['ahp']['weights'][strtolower(str_replace(' ', '', $rank['criteria']))] ?? 'N/A' }}
                                                </td>
                                                <td>{{ $rank['percentage'] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Hasil Akhir -->
                            <div class="alert alert-success mt-4">
                                <i class="bi bi-trophy"></i>
                                <strong>{{ $ahpData['ahp']['dominan'] }}</strong> lebih berpengaruh terhadap hasil belajar
                                sebanyak
                                <strong>{{ $ahpData['ahp']['persen_dominan'] }}%</strong>.
                            </div>

                        </div>
                    </div>
                    <!-- Card: Uji Konsistensi Matriks -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Uji Konsistensi Matriks AHP</h4>

                            <!-- Langkah 1: Perkalian Matriks dengan Bobot -->
                            <div class="mb-4">
                                <h6>1. Perkalian Matriks dengan Bobot (Ax)</h6>
                                <p>Mengalikan setiap baris matriks awal dengan vektor bobot (W):</p>

                                <!-- Formula Perhitungan -->
                                <div class="table-responsive mb-3">
                                    <h6>Formula:</h6>
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th>Perhitungan</th>
                                                <th>Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Minat</strong></td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_calculations']['minat'] }}
                                                </td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['minat'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Motivasi</strong></td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_calculations']['motivasi'] }}
                                                </td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['motivasi'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Observasi</strong></td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_calculations']['observasi'] }}
                                                </td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['observasi'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 2: Hitung Lambda -->
                            <div class="mb-4">
                                <h6>2. Perhitungan Lambda (λ)</h6>
                                <p>Membagi hasil perkalian dengan bobot masing-masing kriteria:</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th>Formula</th>
                                                <th>Perhitungan</th>
                                                <th>Lambda (λ)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Minat</strong></td>
                                                <td>Ax / W</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['minat'] }}
                                                    / {{ $ahpData['ahp']['weights']['minat'] }}</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['lambdas']['minat'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Motivasi</strong></td>
                                                <td>Ax / W</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['motivasi'] }}
                                                    / {{ $ahpData['ahp']['weights']['motivasi'] }}</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['lambdas']['motivasi'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Observasi</strong></td>
                                                <td>Ax / W</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['weighted_matrix_results']['observasi'] }}
                                                    / {{ $ahpData['ahp']['weights']['observasi'] }}</td>
                                                <td>{{ $ahpData['ahp']['consistency_test']['lambdas']['observasi'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Langkah 3: Lambda Max -->
                            <div class="mb-4">
                                <h6>3. Lambda Maksimum (λ<sub>max</sub>)</h6>
                                <div class="bg-info bg-opacity-10 p-3 rounded border border-info">
                                    <p class="mb-2 text-dark">
                                        <strong>Rumus:</strong> λ<sub>max</sub> = (λ<sub>1</sub> + λ<sub>2</sub> +
                                        λ<sub>3</sub>) / n
                                    </p>
                                    <p class="mb-2 text-dark">
                                        λ<sub>max</sub> = ({{ $ahpData['ahp']['consistency_test']['lambdas']['minat'] }} +
                                        {{ $ahpData['ahp']['consistency_test']['lambdas']['motivasi'] }} +
                                        {{ $ahpData['ahp']['consistency_test']['lambdas']['observasi'] }}) / 3
                                    </p>
                                    <p class="mb-0 text-dark">
                                        <strong>λ<sub>max</sub> =
                                            {{ $ahpData['ahp']['consistency_test']['lambda_max'] }}</strong>
                                    </p>
                                </div>
                            </div>

                            <!-- Langkah 4: Consistency Index -->
                            <div class="mb-4">
                                <h6>4. Consistency Index (CI)</h6>
                                <div class="bg-warning bg-opacity-10 p-3 rounded border border-warning">
                                    <p class="mb-2 text-dark">
                                        <strong>Rumus:</strong> CI = (λ<sub>max</sub> - n) / (n - 1)
                                    </p>
                                    <p class="mb-2 text-dark">
                                        CI = ({{ $ahpData['ahp']['consistency_test']['lambda_max'] }} - 3) / (3 - 1)
                                    </p>
                                    <p class="mb-0 text-dark">
                                        <strong>CI =
                                            {{ $ahpData['ahp']['consistency_test']['consistency_index'] }}</strong>
                                    </p>
                                </div>
                            </div>

                            <!-- Langkah 5: Random Index -->
                            <div class="mb-4">
                                <h6>5. Random Index (RI)</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>n</th>
                                                <th>1</th>
                                                <th>2</th>
                                                <th class="bg-success">3</th>
                                                <th>4</th>
                                                <th>5</th>
                                                <th>6</th>
                                                <th>7</th>
                                                <th>8</th>
                                                <th>9</th>
                                                <th>10</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>RI</th>
                                                <td>0</td>
                                                <td>0</td>
                                                <td class="bg-success text-white">
                                                    <strong>{{ $ahpData['ahp']['consistency_test']['random_index'] }}</strong>
                                                </td>
                                                <td>0.90</td>
                                                <td>1.12</td>
                                                <td>1.24</td>
                                                <td>1.32</td>
                                                <td>1.41</td>
                                                <td>1.45</td>
                                                <td>1.49</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="mb-0"><small class="text-muted">Untuk matriks 3×3, RI =
                                        {{ $ahpData['ahp']['consistency_test']['random_index'] }}</small></p>
                            </div>

                            <!-- Langkah 6: Consistency Ratio -->
                            <div class="mb-4">
                                <h6>6. Consistency Ratio (CR)</h6>
                                <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary">
                                    <p class="mb-2 text-dark">
                                        <strong>Rumus:</strong> CR = CI / RI
                                    </p>
                                    <p class="mb-2 text-dark">
                                        CR = {{ $ahpData['ahp']['consistency_test']['consistency_index'] }} /
                                        {{ $ahpData['ahp']['consistency_test']['random_index'] }}
                                    </p>
                                    <p class="mb-0 text-dark">
                                        <strong>CR =
                                            {{ $ahpData['ahp']['consistency_test']['consistency_ratio'] }}</strong>
                                    </p>
                                </div>
                            </div>

                            <!-- Hasil Uji Konsistensi -->
                            @php
                                $consistencyClass = '';
                                $consistencyIcon = '';
                                if ($ahpData['ahp']['consistency_test']['consistency_ratio'] == 0) {
                                    $consistencyClass = 'alert-success';
                                    $consistencyIcon = 'bi-check-circle-fill';
                                } elseif ($ahpData['ahp']['consistency_test']['consistency_ratio'] <= 0.05) {
                                    $consistencyClass = 'alert-success';
                                    $consistencyIcon = 'bi-check-circle-fill';
                                } elseif ($ahpData['ahp']['consistency_test']['consistency_ratio'] <= 0.1) {
                                    $consistencyClass = 'alert-warning';
                                    $consistencyIcon = 'bi-exclamation-triangle-fill';
                                } else {
                                    $consistencyClass = 'alert-danger';
                                    $consistencyIcon = 'bi-x-circle-fill';
                                }
                            @endphp

                            <div class="alert {{ $consistencyClass }} border d-flex align-items-center">
                                <i class="{{ $consistencyIcon }} me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-2">
                                        <strong>{{ $ahpData['ahp']['consistency_test']['consistency_status'] }}</strong>
                                    </h5>
                                    <p class="mb-2">
                                        <strong>CR =
                                            {{ $ahpData['ahp']['consistency_test']['consistency_ratio'] }}</strong>
                                        @if ($ahpData['ahp']['consistency_test']['is_consistent'])
                                            ≤ 0.1 ✓ (Konsisten)
                                        @else
                                            > 0.1 ✗ (Tidak Konsisten)
                                        @endif
                                    </p>

                                    <!-- Kriteria Konsistensi -->
                                    <div class="mb-3">
                                        <strong>Kriteria Konsistensi:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li>CR = 0: Konsisten Sempurna</li>
                                            <li>CR ≤ 0.05: Sangat Konsisten</li>
                                            <li>CR ≤ 0.1: Konsisten (dapat diterima)</li>
                                            <li>CR > 0.1: Tidak Konsisten (perlu revisi)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Interpretasi -->
                            <div class="mt-4">
                                <h6>Interpretasi:</h6>
                                <div class="bg-light p-3 rounded border">
                                    @foreach ($ahpData['ahp']['consistency_test']['interpretation'] as $interpretation)
                                        <p class="mb-2 text-dark">• {{ $interpretation }}</p>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kesimpulan -->
                            <div class="mt-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-dark">
                                            <i class="bi bi-clipboard-check"></i> Kesimpulan Uji Konsistensi
                                        </h6>
                                        @if ($ahpData['ahp']['consistency_test']['is_consistent'])
                                            <p class="card-text text-success">
                                                ✓ Matriks perbandingan berpasangan <strong>konsisten</strong> dengan CR =
                                                {{ $ahpData['ahp']['consistency_test']['consistency_ratio'] }}.
                                                Hasil bobot AHP dapat <strong>diandalkan</strong> untuk pengambilan
                                                keputusan.
                                            </p>
                                        @else
                                            <p class="card-text text-danger">
                                                ✗ Matriks perbandingan berpasangan <strong>tidak konsisten</strong> dengan
                                                CR = {{ $ahpData['ahp']['consistency_test']['consistency_ratio'] }}.
                                                Disarankan untuk <strong>meninjau kembali</strong> penilaian perbandingan
                                                atau menggunakan metode lain.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Skor Gabungan AHP -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Skor Gabungan AHP</h4>

                            <div class="mb-4">
                                <h6>Rumus Skor Gabungan:</h6>
                                <div class="bg-light p-3 rounded border">
                                    <p class="mb-2 text-dark">
                                        <strong>Skor Gabungan = (W<sub>minat</sub> × C<sub>1</sub>) + (W<sub>motivasi</sub>
                                            × C<sub>2</sub>) + (W<sub>observasi</sub> × C<sub>3</sub>)</strong>
                                    </p>
                                    <small class="text-muted">
                                        Dimana W = bobot AHP dan C = nilai persentase masing-masing kriteria
                                    </small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6>Substitusi Nilai:</h6>
                                <div class="bg-info bg-opacity-10 p-3 rounded border border-info">
                                    <p class="mb-2 text-dark">
                                        Skor Gabungan = ({{ $ahpData['ahp']['weights']['minat'] }} ×
                                        {{ $ahpData['rata_rata_nilai']['minat'] }}) +
                                        ({{ $ahpData['ahp']['weights']['motivasi'] }} ×
                                        {{ $ahpData['rata_rata_nilai']['motivasi'] }}) +
                                        ({{ $ahpData['ahp']['weights']['observasi'] }} ×
                                        {{ $ahpData['rata_rata_nilai']['observasi'] }})
                                    </p>
                                    <p class="mb-0 text-dark">
                                        <strong>Skor Gabungan = {{ $ahpData['ahp']['skor_gabungan'] }}</strong>
                                    </p>
                                </div>
                            </div>

                            <div class="alert alert-primary">
                                <i class="bi bi-calculator"></i>
                                <strong>Hasil Skor Gabungan AHP: {{ $ahpData['ahp']['skor_gabungan'] }}</strong>
                                <br>
                                <small>Skor ini menunjukkan hasil evaluasi tertimbang berdasarkan kepentingan relatif setiap
                                    kriteria.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Analisis Hasil Belajar -->
                    @if (isset($ahpData['normalized_gain']))
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Analisis Hasil Belajar</h4>

                                <!-- Data Dasar -->
                                <div class="mb-4">
                                    <h6>Data Dasar Hasil Belajar:</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary">
                                                <h6 class="text-primary mb-1">Pretest (Rata-rata)</h6>
                                                <h4 class="mb-0">
                                                    {{ $ahpData['normalized_gain']['data_dasar']['pretest'] }}</h4>
                                                <small class="text-muted">dari
                                                    {{ $ahpData['total_data']['hasil_belajar'] }} siswa</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="bg-success bg-opacity-10 p-3 rounded border border-success">
                                                <h6 class="text-success mb-1">Posttest (Rata-rata)</h6>
                                                <h4 class="mb-0">
                                                    {{ $ahpData['normalized_gain']['data_dasar']['posttest'] }}</h4>
                                                <small class="text-muted">dari
                                                    {{ $ahpData['total_data']['hasil_belajar'] }} siswa</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="bg-warning bg-opacity-10 p-3 rounded border border-warning">
                                                <h6 class="text-warning-dark mb-1">Skor Maksimum</h6>
                                                <h4 class="mb-0">
                                                    {{ $ahpData['normalized_gain']['data_dasar']['max_score'] }}</h4>
                                                <small class="text-muted">skala penilaian</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gain Sederhana -->
                                <div class="mb-4">
                                    <h6>1. Selisih (Gain) Sederhana:</h6>
                                    <div class="bg-light p-3 rounded border">
                                        <p class="mb-2 text-dark">
                                            <strong>Rumus:</strong> Gain = Posttest - Pretest
                                        </p>
                                        <p class="mb-2 text-dark">
                                            Gain = {{ $ahpData['normalized_gain']['data_dasar']['posttest'] }} -
                                            {{ $ahpData['normalized_gain']['data_dasar']['pretest'] }} =
                                            <strong>{{ $ahpData['normalized_gain']['data_dasar']['gain_absolut'] }}
                                                poin</strong>
                                        </p>
                                        <small class="text-muted">
                                            Peningkatan {{ $ahpData['normalized_gain']['data_dasar']['gain_absolut'] }}
                                            poin secara absolut dari nilai pretest ke posttest.
                                        </small>
                                    </div>
                                </div>

                                <!-- Normalized Gain (Hake) -->
                                <div class="mb-4">
                                    <h6>2. Normalized Gain (g) - Hake (1998):</h6>

                                    <!-- Rumus -->
                                    <div class="mb-3">
                                        <div class="bg-info bg-opacity-10 p-3 rounded border border-info">
                                            <p class="mb-2 text-dark">
                                                <strong>Rumus:</strong>
                                                {{ $ahpData['normalized_gain']['rumus']['formula'] }}
                                            </p>
                                            <p class="mb-2 text-dark">
                                                <strong>Substitusi:</strong>
                                                {{ $ahpData['normalized_gain']['rumus']['substitusi'] }}
                                            </p>
                                            <p class="mb-2 text-dark">
                                                <strong>Perhitungan:</strong>
                                                {{ $ahpData['normalized_gain']['rumus']['calculation'] }}
                                            </p>
                                            <p class="mb-0 text-dark">
                                                <strong>Hasil:</strong>
                                                {{ $ahpData['normalized_gain']['rumus']['hasil'] }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Kriteria Hake -->
                                    <div class="mb-3">
                                        <h6>Kriteria Hake:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Kategori</th>
                                                        <th>Kriteria</th>
                                                        <th>Interpretasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="{{ $ahpData['normalized_gain']['category'] == 'Tinggi' ? 'bg-success bg-opacity-10' : '' }}">
                                                        <td><strong>Tinggi</strong></td>
                                                        <td>g ≥ 0.7</td>
                                                        <td>Pembelajaran sangat efektif</td>
                                                    </tr>
                                                    <tr
                                                        class="{{ $ahpData['normalized_gain']['category'] == 'Sedang' ? 'bg-warning bg-opacity-10' : '' }}">
                                                        <td><strong>Sedang</strong></td>
                                                        <td>0.3 ≤ g &lt; 0.7</td>
                                                        <td>Pembelajaran cukup efektif</td>
                                                    </tr>
                                                    <tr
                                                        class="{{ $ahpData['normalized_gain']['category'] == 'Rendah' ? 'bg-danger bg-opacity-10' : '' }}">
                                                        <td><strong>Rendah</strong></td>
                                                        <td>g &lt; 0.3</td>
                                                        <td>Pembelajaran belum efektif</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <small class="text-muted">
                                            <strong>Referensi:</strong>
                                            {{ $ahpData['normalized_gain']['kriteria_hake']['referensi'] }}
                                        </small>
                                    </div>

                                    <!-- Hasil -->
                                    @php
                                        $categoryClass = '';
                                        $categoryIcon = '';
                                        switch ($ahpData['normalized_gain']['category']) {
                                            case 'Tinggi':
                                                $categoryClass = 'alert-success';
                                                $categoryIcon = 'bi-check-circle-fill';
                                                break;
                                            case 'Sedang':
                                                $categoryClass = 'alert-warning';
                                                $categoryIcon = 'bi-exclamation-triangle-fill';
                                                break;
                                            case 'Rendah':
                                                $categoryClass = 'alert-danger';
                                                $categoryIcon = 'bi-x-circle-fill';
                                                break;
                                            default:
                                                $categoryClass = 'alert-secondary';
                                                $categoryIcon = 'bi-info-circle-fill';
                                        }
                                    @endphp

                                    <div class="alert {{ $categoryClass }} d-flex align-items-center border">
                                        <i class="{{ $categoryIcon }} me-2"></i>
                                        <div>
                                            <strong>g = {{ $ahpData['normalized_gain']['normalized_gain'] }}</strong> →
                                            kategori
                                            <strong>{{ strtolower($ahpData['normalized_gain']['category']) }}</strong>
                                            <br>
                                            <small>{{ $ahpData['normalized_gain']['category_description'] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Interpretasi -->
                                <div class="mb-4">
                                    <h6>Interpretasi Hasil:</h6>
                                    <div class="bg-light p-3 rounded border">
                                        @foreach ($ahpData['normalized_gain']['interpretation'] as $interpretation)
                                            <p class="mb-2 text-dark">• {{ $interpretation }}</p>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Kaitan dengan AHP -->
                                <div class="alert alert-info border">
                                    <h6 class="mb-2">
                                        <i class="bi bi-lightbulb"></i>
                                        Kaitan dengan Hasil AHP:
                                    </h6>
                                    <p class="mb-2">
                                        Dikaitkan dengan bobot AHP, faktor
                                        <strong>{{ $ahpData['ahp']['dominan'] }}</strong>
                                        ({{ $ahpData['ahp']['persen_dominan'] }}%) adalah yang paling berpengaruh terhadap
                                        hasil belajar.
                                    </p>
                                    @if ($ahpData['normalized_gain']['category'] == 'Rendah')
                                        <p class="mb-0">
                                            Untuk meningkatkan efektivitas pembelajaran, fokuskan strategi penguatan pada
                                            faktor-faktor dengan bobot tertinggi:
                                            @foreach ($ahpData['ahp']['ranking'] as $index => $rank)
                                                <strong>{{ $rank['criteria'] }}
                                                    ({{ $rank['percentage'] }}%)
                                                </strong>{{ $index < count($ahpData['ahp']['ranking']) - 1 ? ', ' : '.' }}
                                            @endforeach
                                        </p>
                                    @elseif($ahpData['normalized_gain']['category'] == 'Sedang')
                                        <p class="mb-0">
                                            Pembelajaran sudah cukup efektif, namun masih dapat dioptimalkan dengan
                                            memperkuat faktor dominan: <strong>{{ $ahpData['ahp']['dominan'] }}</strong>.
                                        </p>
                                    @else
                                        <p class="mb-0">
                                            Pembelajaran sangat efektif! Strategi yang memperkuat
                                            <strong>{{ $ahpData['ahp']['dominan'] }}</strong> dapat dipertahankan dan
                                            dijadikan model untuk implementasi lebih luas.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Data hasil belajar (pretest/posttest) belum tersedia untuk analisis Normalized Gain.
                        </div>
                    @endif
                @elseif (!isset($ahpData))
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Data AHP belum tersedia. Pastikan data minat, motivasi, dan observasi sudah lengkap untuk kelas ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
