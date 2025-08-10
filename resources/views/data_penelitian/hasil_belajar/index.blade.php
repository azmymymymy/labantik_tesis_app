@extends('layouts.app')

@section('title', 'Dashboard - Admin Panel')
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
        <!-- Breadcrumb Start -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Data Hasil Belajar</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif
                        @if ($dataHasilBelajar->count() > 0)
                            <!-- Tabel Data Siswa -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <t4r>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>PreTest</th>
                                            <th>PostTest</th>
                                            <th>Action</th>
                                        </t4r>
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
                                                    <a href="{{ route('hasil-belajar.edit', $data->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('hasil-belajar.destroy', $data->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data siswa.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Tabel Data Siswa -->
                        @endif

                    </div>
                </div>
                @if ($dataHasilBelajar->count() === 0)
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('hasil-belajar.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <input type="file" class="form-control" name="data_hasilBelajar"
                                        accept=".xlsx, .xls, .csv">
                                    <small>Format File Harus .xlsx, .xls, atau csv. Maksimal 2MB</small>
                                    @error('data_hasilBelajar')
                                        <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Import Data Hasil Belajar</button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <!-- Breadcrumb End -->
    </div>
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
