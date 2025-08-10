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
                                <h4 class="card-title mb-0">Data Angket Motivasi</h4>
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
                        @if ($dataAngket->count() > 0)
                            <!-- Tabel Data Siswa -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <t4r>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </t4r>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataAngket as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->siswa->nama }}</td>
                                                <td>{{ $data->kelas->name }}</td>
                                                <td>{{ $data->pertanyaan_1 }}</td>
                                                <td>{{ $data->pertanyaan_2 }}</td>
                                                <td>{{ $data->pertanyaan_3 }}</td>
                                                <td>{{ $data->pertanyaan_4 }}</td>
                                                <td>{{ $data->pertanyaan_5 }}</td>
                                                <td>{{ $data->pertanyaan_6 }}</td>
                                                <td>{{ $data->pertanyaan_7 }}</td>
                                                <td>{{ $data->pertanyaan_8 }}</td>
                                                <td>{{ $data->pertanyaan_9 }}</td>
                                                <td>{{ $data->pertanyaan_10 }}</td>
                                                <td>{{ $data->total }}</td>
                                                <td>
                                                    <a href="{{ route('angket-motivasi.edit', $data->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('angket-motivasi.destroy', $data->id) }}"
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
                @if ($dataAngket->count() === 0)
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('angket-motivasi.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <input type="file" class="form-control" name="data_motivasi"
                                        accept=".xlsx, .xls, .csv">
                                    <small>Format File Harus .xlsx, .xls, atau csv. Maksimal 2MB</small>
                                    @error('data_motivasi')
                                        <small class="text-danger mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Import Data Angket Motivasi</button>
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
