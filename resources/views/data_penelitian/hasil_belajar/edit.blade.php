@extends('layouts.app')

@section('title', 'Edit Angket Motivasi')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Hasil Belajar</div>
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
                        <form method="POST" action="{{ route('hasil-belajar.update', $hasilBelajar->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="siswa_id"
                                    value="{{ old('siswa_id', $hasilBelajar->siswa_id) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="nis" name="kelas_id"
                                    value="{{ old('kelas_id', $hasilBelajar->kelas_id) }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="nis" class="form-label">PreTest</label>
                                <input type="text" class="form-control" id="nis" name="pretest"
                                    value="{{ old('pretest', $hasilBelajar->pretest) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">PostTest</label>
                                <input type="text" class="form-control" id="kelas" name="posttest"
                                    value="{{ old('posttest', $hasilBelajar->posttest) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('angket-motivasi.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
