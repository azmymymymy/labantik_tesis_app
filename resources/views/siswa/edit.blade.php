@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Data Siswa</div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('siswa.update', $siswa->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama', $siswa->nama) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis"
                                    value="{{ old('nis', $siswa->nis) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">NISN</label>
                                <input type="text" class="form-control" id="nis" name="nisn"
                                    value="{{ old('nisn', $siswa->nisn) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas"
                                    value="{{ old('kelas', $siswa->kelas->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
