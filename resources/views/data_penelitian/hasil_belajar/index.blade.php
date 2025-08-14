@extends('layouts.app')

@section('title', 'Hasil Belajar')
@section('meta-description', 'Main dashboard for admin panel with statistics and overview')

@push('styles')
    <!-- Additional CSS for dashboard -->
    <style>
        .dashboard-card {
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
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
                                <h4 class="card-title mb-0">Data Hasil Belajar</h4>
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

                        <!-- Alert Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <div>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#importModalHasilBelajar">
                                    <i class=""></i> Import Excel
                                </button>
                                <a href="{{ route('hasil-belajar.create') }}" class="btn btn-primary">
                                    <i class=""></i> Tambah Data
                                </a>
                                @if ($dataHasilBelajar->count() > 0)
                                    {{-- <form id="clearFormHasilBelajar" method="POST"
                                        action="{{ route('hasil-belajar.clear') }}" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus SEMUA data hasil belajar? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="icon-trash"></i> Hapus Semua
                                        </button>
                                    </form> --}}
                                @endif
                            </div>
                            <div>
                                <span class="badge badge-info">Total Data: {{ $dataHasilBelajar->count() }}</span>
                            </div>
                        </div>

                        <!-- Tabel Data Hasil Belajar -->
                        @if ($dataHasilBelajar->count() > 0)
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>PreTest</th>
                                            <th>PostTest</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataHasilBelajar as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->siswa->nama }}</td>
                                                <td>{{ $data->kelas->name }}</td>
                                                <td>{{ $data->pretest }}</td>
                                                <td>{{ $data->posttest }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        
                                                        <a href="{{ route('hasil-belajar.edit', $data->id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="icon-pencil"></i> edit
                                                        </a>
                                                        <form action="{{ route('hasil-belajar.destroy', $data->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="icon-trash"></i>hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data hasil belajar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <!-- End Tabel Data Hasil Belajar -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModalHasilBelajar" tabindex="-1" aria-labelledby="importModalHasilBelajarLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('hasil-belajar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalHasilBelajarLabel">Import Data Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="file" name="data_hasilBelajar"
                                accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Format yang diizinkan: .xlsx, .xls, .csv (Maksimal 2MB)
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Format Excel:</strong><br>
                            Kolom 1: Nama<br>
                            Kolom 2: Kelas<br>
                            Kolom 3: PreTest<br>
                            Kolom 4: PostTest<br>
                            <em>Header harus dimulai dari baris ke-2</em>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection

@push('scripts')
    <!-- Dashboard specific scripts -->
    <script>
        $(document).ready(function() {
            // Initialize counter animation
            $('.counter').each(function() {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function(now) {
                        $(this).text(Math.ceil(now).toLocaleString());
                    }
                });
            });
        });
    </script>
@endpush
