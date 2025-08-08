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
                                <h4 class="card-title mb-0">Data Siswa</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                                <a href="{{ route('siswa.create') }}" class="btn btn-success btn-sm ms-2">Tambah Siswa</a>
                            </div>
                        </div>
                        <!-- Tabel Data Siswa -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswas as $siswa)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $siswa->nis }}</td>
                                            <td>{{ $siswa->kelas }}</td>
                                            <td>{{ $siswa->jenis_kelamin }}</td>
                                            <td>{{ $siswa->alamat }}</td>
                                            <td>
                                                <a href="{{ route('siswa.edit', $siswa->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                    style="display:inline-block;">
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
                    </div>
                </div>
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
