@extends('layouts.app')

@section('title', 'Analisis AHP Individu')
@push('styles')
    <style>
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        
        .siswa-row {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .siswa-row:hover {
            background-color: #f8f9fa;
        }
        
        .analyze-btn {
            transition: all 0.3s ease;
        }
        
        .analyze-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .analyze-btn.loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                            <div>
                                <h4 class="card-title mb-1">Analisis AHP Individu</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item active" aria-current="page">Data Penelitian</li>
                                    </ol>
                                </nav>
                            </div>
                            
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- <!-- Search Box -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="autocomplete" style="width: 100%;">
                                <input type="text" 
                                       id="siswaSearch" 
                                       class="form-control" 
                                       placeholder="Cari siswa (minimal 2 karakter)..."
                                       autocomplete="off">
                                <div id="autocompleteResults" class="autocomplete-items"></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
                <!-- Daftar Siswa -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar Siswa</h5>
                            <span class="badge bg-primary">{{ $siswa->count() }} Siswa</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($siswa->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="siswaTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="40%">Nama Siswa</th>
                                        <th width="25%">Kelas</th>
                                        <th width="30%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $data)
                                    <tr class="siswa-row" data-siswa-id="{{ $data->id }}" data-siswa-nama="{{ $data->nama }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ strtoupper(substr($data->nama, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $data->nama }}</strong>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $data->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $data->kelas->name ?? 'Kelas tidak tersedia' }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary analyze-btn" 
                                                    data-siswa-id="{{ $data->id }}" 
                                                    data-siswa-nama="{{ $data->nama }}">
                                                <i class="bi bi-calculator"></i> Analisis AHP
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-0">Belum ada data siswa</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i>
                            Tidak ada data siswa ditemukan.
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Results Container -->
                <div id="individuResults" class="d-none mt-3">
                    <!-- Hasil akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Autocomplete siswa
        $('#siswaSearch').on('input', function() {
            const query = $(this).val();
            if (query.length < 2) {
                $('#autocompleteResults').empty();
                return;
            }

            $.get('/ahp/search-siswa', { q: query }, function(data) {
                $('#autocompleteResults').empty();
                if (data.length === 0) {
                    $('#autocompleteResults').append('<div>No results found</div>');
                    return;
                }

                data.forEach(function(siswa) {
                    const item = $('<div></div>')
                        .text(siswa.text)
                        .data('siswa', siswa)
                        .click(function() {
                            $('#siswaSearch').val(siswa.nama);
                            $('#autocompleteResults').empty();
                            loadSiswaData(siswa.id, siswa.nama);
                        });
                    $('#autocompleteResults').append(item);
                });
            });
        });
        
        // Event handler untuk tombol analisis AHP
        $('.analyze-btn').on('click', function(e) {
            e.stopPropagation();
            const siswaId = $(this).data('siswa-id');
            const siswaNama = $(this).data('siswa-nama');
            
            // Reset search box
            $('#siswaSearch').val(siswaNama);
            $('#autocompleteResults').empty();
            
            // Load data AHP
            loadSiswaData(siswaId, siswaNama, $(this));
        });
        
        // Event handler untuk klik row (optional)
        $('.siswa-row').on('click', function(e) {
            if (!$(e.target).closest('.analyze-btn').length) {
                $(this).find('.analyze-btn').click();
            }
        });

        // Load data siswa dan hitung AHP
        function loadSiswaData(siswaId, siswaNama, buttonElement = null) {
            // Show loading state pada button yang diklik
            if (buttonElement) {
                buttonElement.addClass('loading');
                buttonElement.html('<span class="loading-spinner"></span> Menghitung...');
                $('.analyze-btn').not(buttonElement).prop('disabled', true);
            }
            
            // Show loading di results area
            $('#individuResults').removeClass('d-none').html(`
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="loading-spinner mb-3"></div>
                        <h5>Menghitung Analisis AHP</h5>
                        <p class="text-muted">Sedang memproses data untuk ${siswaNama} (ID: ${siswaId})...</p>
                        <small class="text-muted">Mohon tunggu, proses ini membutuhkan beberapa detik</small>
                    </div>
                </div>
            `);
            
            // Scroll ke hasil
            $('html, body').animate({
                scrollTop: $('#individuResults').offset().top - 100
            }, 500);
            
            // Tambahkan timeout untuk request yang lama
            const xhr = $.get(`/ahp/calculate/${siswaId}`)
                .done(function(response) {
                    console.log('Response received:', response); // Debug log
                    
                    if (response.success) {
                        renderAHPResult(response.data);
                    } else {
                        showAHPError(response.message || 'Terjadi kesalahan saat menghitung AHP');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Request failed:', xhr, status, error); // Debug log
                    
                    let errorMessage = 'Terjadi kesalahan saat memuat data';
                    
                    if (xhr.status === 404) {
                        errorMessage = 'Endpoint tidak ditemukan. Periksa routing aplikasi.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Error server internal. Periksa log aplikasi.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (e) {
                            errorMessage = `Error ${xhr.status}: ${error}`;
                        }
                    }
                    
                    showAHPError(errorMessage);
                })
                .always(function() {
                    // Remove loading state
                    if (buttonElement) {
                        buttonElement.removeClass('loading');
                        buttonElement.html('<i class="bi bi-calculator"></i> Analisis AHP');
                        $('.analyze-btn').prop('disabled', false);
                    }
                });
                
            // Set timeout 30 detik
            setTimeout(function() {
                if (xhr.readyState !== 4) {
                    xhr.abort();
                    showAHPError('Request timeout - Proses memakan waktu terlalu lama');
                    if (buttonElement) {
                        buttonElement.removeClass('loading');
                        buttonElement.html('<i class="bi bi-calculator"></i> Analisis AHP');
                        $('.analyze-btn').prop('disabled', false);
                    }
                }
            }, 30000);
        }
        
        // Function untuk menampilkan error AHP
        function showAHPError(message) {
            $('#individuResults').html(`
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-triangle"></i> Error Analisis AHP
                            </h5>
                            <p class="mb-1"><strong>Pesan Error:</strong> ${message}</p>
                            <hr>
                            <p class="mb-0">
                                <small class="text-muted">
                                    Kemungkinan penyebab:
                                    <ul class="mb-0 mt-2">
                                        <li>Data angket siswa belum lengkap</li>
                                        <li>Ada field yang kosong atau null</li>
                                        <li>Data belum tersimpan di database</li>
                                    </ul>
                                </small>
                            </p>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh Halaman
                            </button>
                        </div>
                    </div>
                </div>
            `);
        }

        // Render hasil AHP
        function renderAHPResult(data) {
            const { siswa, raw_scores, ahp_result } = data;
            const { comparison_matrix, normalized_matrix, weights, consistency_ratio, dominance } = ahp_result;
            
            let html = `
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Hasil Analisis AHP - ${siswa.nama}</h4>
                    <span class="badge bg-light text-dark">${siswa.kelas}</span>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Data Mentah</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Minat (C1)
                                            <span class="badge bg-primary">${raw_scores.minat}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Motivasi (C2)
                                            <span class="badge bg-primary">${raw_scores.motivasi}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Observasi (C3)
                                            <span class="badge bg-primary">${raw_scores.observasi}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Bobot Kriteria</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Minat</span>
                                        <span><strong>${weights.percentages[0].toFixed(2)}%</strong></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                            style="width: ${weights.percentages[0].toFixed(0)}%" 
                                            aria-valuenow="${weights.percentages[0].toFixed(0)}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Motivasi</span>
                                        <span><strong>${weights.percentages[1].toFixed(2)}%</strong></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                            style="width: ${weights.percentages[1].toFixed(0)}%" 
                                            aria-valuenow="${weights.percentages[1].toFixed(0)}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Observasi</span>
                                        <span><strong>${weights.percentages[2].toFixed(2)}%</strong></span>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                            style="width: ${weights.percentages[2].toFixed(0)}%" 
                                            aria-valuenow="${weights.percentages[2].toFixed(0)}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Kesimpulan Analisis</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-primary">
                                        <i class="bi bi-lightbulb"></i> ${dominance.interpretation}
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="d-block">Kriteria Dominan:</span>
                                            <span class="h4">${dominance.dominant_criteria}</span>
                                        </div>
                                        <div class="display-4 text-primary">${dominance.dominant_percentage.toFixed(2)}%</div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h6>Ranking Kriteria:</h6>
                                        <ol class="list-group list-group-numbered">
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">${dominance.ranking[0].criteria}</div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">${dominance.ranking[0].percentage.toFixed(2)}%</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">${dominance.ranking[1].criteria}</div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">${dominance.ranking[1].percentage.toFixed(2)}%</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">${dominance.ranking[2].criteria}</div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">${dominance.ranking[2].percentage.toFixed(2)}%</span>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Matriks Perbandingan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
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
                                                    <td>${comparison_matrix.matrix[0][0].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[0][1].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[0][2].toFixed(4)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Motivasi</th>
                                                    <td>${comparison_matrix.matrix[1][0].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[1][1].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[1][2].toFixed(4)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Observasi</th>
                                                    <td>${comparison_matrix.matrix[2][0].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[2][1].toFixed(4)}</td>
                                                    <td>${comparison_matrix.matrix[2][2].toFixed(4)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Matriks Normalisasi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
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
                                                    <td>${normalized_matrix.matrix[0][0].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[0][1].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[0][2].toFixed(4)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Motivasi</th>
                                                    <td>${normalized_matrix.matrix[1][0].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[1][1].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[1][2].toFixed(4)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Observasi</th>
                                                    <td>${normalized_matrix.matrix[2][0].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[2][1].toFixed(4)}</td>
                                                    <td>${normalized_matrix.matrix[2][2].toFixed(4)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            `;
            
            $('#individuResults').removeClass('d-none').html(html);
        }
        
        // Clear search when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.autocomplete').length) {
                $('#autocompleteResults').empty();
            }
        });
    });
</script>
@endpush