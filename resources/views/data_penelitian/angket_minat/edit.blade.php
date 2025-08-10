@extends('layouts.app')

@section('title', 'Edit Angket Minat')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Data Angket Minat</div>
                    <div class="card-body">
                        <form action="{{ route('angket-minat.update', $angketMinat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            @for ($i = 1; $i <= 14; $i++)
                                <div class="mb-3">
                                    <label for="nilai_{{ $i }}" class="form-label">Nilai
                                        {{ $i }}</label>
                                    <input type="number" class="form-control" id="nilai_{{ $i }}"
                                        name="nilai_{{ $i }}"
                                        value="{{ old('nilai_' . $i, $angketMinat->{'nilai_' . $i}) }}" min="1"
                                        max="5" required>
                                </div>
                            @endfor
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('angket-minat.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
