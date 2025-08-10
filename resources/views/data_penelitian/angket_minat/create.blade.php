@extends('layouts.app')

@section('title', 'Tambah Angket Minat')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Data Angket Minat</div>
                    <div class="card-body">
                        <form action="{{ route('angket-minat.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas"
                                    value="{{ old('kelas') }}" required>
                            </div>
                            @for ($i = 1; $i <= 14; $i++)
                                <div class="mb-3">
                                    <label for="nilai_{{ $i }}" class="form-label">Nilai
                                        {{ $i }}</label>
                                    <input type="number" class="form-control" id="nilai_{{ $i }}"
                                        name="nilai_{{ $i }}" value="{{ old('nilai_' . $i) }}" min="1"
                                        max="5" required>
                                </div>
                            @endfor
                            <button type="submit" class="btn btn-primary"><i class="icon-plus"></i> Simpan</button>
                            <a href="{{ route('angket-minat.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
