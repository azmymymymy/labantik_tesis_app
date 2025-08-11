@extends('layouts.app')

@section('title', 'Detail Angket Minat')

@section('content')
    <div class="dashboard-default-sec">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Detail Data Angket Minat</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('angket-minat.index') }}">Data Angket Minat</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Student Information -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Informasi Siswa</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Nama:</strong></td>
                                                <td>{{ $angketMinat->siswa->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kelas:</strong></td>
                                                <td>{{ $angketMinat->kelas->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Input:</strong></td>
                                                <td>{{ $angketMinat->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Terakhir Update:</strong></td>
                                                <td>{{ $angketMinat->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Statistik</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Total Nilai:</strong></td>
                                                <td><span class="badge badge-primary fs-6">{{ $angketMinat->total }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Rata-rata:</strong></td>
                                                <td><span class="badge badge-info fs-6">{{ $angketMinat->getRataRataNilai() }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kategori Minat:</strong></td>
                                                <td>
                                                    @php
                                                        $kategori = $angketMinat->getKategoriMinat();
                                                        $badgeClass = match($kategori) {
                                                            'Sangat Tinggi' => 'badge-success',
                                                            'Tinggi' => 'badge-primary',
                                                            'Sedang' => 'badge-warning',
                                                            'Rendah' => 'badge-secondary',
                                                            'Sangat Rendah' => 'badge-danger',
                                                            default => 'badge-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} fs-6">{{ $kategori }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Details -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Detail Jawaban Angket</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @for ($i = 1; $i <= 14; $i++)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card border-left-primary">
                                                        <div class="card-body text-center">
                                                            <h6 class="card-title">Pertanyaan {{ $i }}</h6>
                                                            <h3 class="text-primary">{{ $angketMinat->{'pertanyaan_' . $i} }}</h3>
                                                            <div class="progress" style="height: 5px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                                     style="width: {{ ($angketMinat->{'pertanyaan_' . $i} / 5) * 100 }}%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart Visualization -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Visualisasi Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="minatChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('angket-minat.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <a href="{{ route('angket-minat.edit', $angketMinat->id) }}" class="btn btn-warning me-2">
                                <i class="icon-pencil"></i> Edit
                            </a>
                            <form action="{{ route('angket-minat.destroy', $angketMinat->id) }}" method="POST" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="icon-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Data for chart
    const data = {
        labels: [
            @for ($i = 1; $i <= 14; $i++)
                'P{{ $i }}'{{ $i < 14 ? ',' : '' }}
            @endfor
        ],
        datasets: [{
            label: 'Nilai Pertanyaan',
            data: [
                @for ($i = 1; $i <= 14; $i++)
                    {{ $angketMinat->{'pertanyaan_' . $i} }}{{ $i < 14 ? ',' : '' }}
                @endfor
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true
        }]
    };

    // Configuration
    const config = {
        type: 'radar',
        data: data,
        options: {
            responsive: true,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 5,
                    min: 1,
                    stepSize: 1
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Profil Minat Siswa'
                }
            }
        }
    };

    // Render chart
    const ctx = document.getElementById('minatChart').getContext('2d');
    new Chart(ctx, config);
});
</script>
@endpush