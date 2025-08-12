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
                                            ÷ 40 × 100</small>
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
                                        ( {{ number_format($ahpData['ahp']['matrix']['normalized']['minat']['minat'], 3) }}
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
