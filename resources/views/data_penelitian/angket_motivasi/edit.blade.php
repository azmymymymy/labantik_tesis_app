@extends('layouts.app')

@section('title', 'Edit Angket Motivasi')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Angket Motivasi</div>
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
                        <form method="POST" action="{{ route('angket-motivasi.update', $angketMo->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="siswa_id"
                                    value="{{ old('siswa_id', $angketMo->siswa_id) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="nis" name="kelas_id"
                                    value="{{ old('kelas_id', $angketMo->kelas_id) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">Pertanyaan 1</label>
                                <input type="text" class="form-control" id="nis" name="pertanyaan_1"
                                    value="{{ old('pertanyaan_21', $angketMo->pertanyaan_1) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 2</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_2"
                                    value="{{ old('pertanyaan_2', $angketMo->pertanyaan_2) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 3</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_3"
                                    value="{{ old('pertanyaan_3', $angketMo->pertanyaan_3) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 4</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_4"
                                    value="{{ old('pertanyaan_4', $angketMo->pertanyaan_4) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 5</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_5"
                                    value="{{ old('pertanyaan_5', $angketMo->pertanyaan_5) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 6</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_6"
                                    value="{{ old('pertanyaan_6', $angketMo->pertanyaan_6) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 2</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_2"
                                    value="{{ old('pertanyaan_2', $angketMo->pertanyaan_2) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 7</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_7"
                                    value="{{ old('pertanyaan_7', $angketMo->pertanyaan_7) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 8</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_8"
                                    value="{{ old('pertanyaan_8', $angketMo->pertanyaan_8) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 9</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_9"
                                    value="{{ old('pertanyaan_9', $angketMo->pertanyaan_9) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Pertanyaan 10</label>
                                <input type="text" class="form-control" id="kelas" name="pertanyaan_10"
                                    value="{{ old('pertanyaan_10', $angketMo->pertanyaan_10) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Total</label>
                                <input type="text" class="form-control" id="kelas" name="total"
                                    value="{{ old('total', $angketMo->total) }}" required>
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
