@extends('layouts.app')

@section('title', 'Tambah Angket Minat')

@section('content')
    <div class="dashboard-default-sec">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Tambah Data Angket Minat</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('angket-minat.index') }}">Data Angket Minat</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('angket-minat.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="row">
                                <!-- Siswa Selection -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="siswa_id" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                                        <select class="form-select @error('siswa_id') is-invalid @enderror" id="siswa_id" name="siswa_id" required>
                                            <option value="">Pilih Siswa</option>
                                            @foreach($siswas as $siswa)
                                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                                    {{ $siswa->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('siswa_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kelas Selection -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($kelas as $item)
                                                <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pertanyaan Values -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Nilai Pertanyaan (1-5)</h5>
                                </div>
                                
                                @for ($i = 1; $i <= 14; $i++)
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="pertanyaan_{{ $i }}" class="form-label">Pertanyaan {{ $i }} <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('pertanyaan_' . $i) is-invalid @enderror" 
                                                   id="pertanyaan_{{ $i }}" 
                                                   name="pertanyaan_{{ $i }}" 
                                                   min="1" 
                                                   max="5" 
                                                   value="{{ old('pertanyaan_' . $i, 1) }}" 
                                                   required>
                                            @error('pertanyaan_' . $i)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('angket-minat.index') }}" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add input validation
    $('input[type="number"]').on('input', function() {
        var value = parseInt($(this).val());
        if (value < 1) {
            $(this).val(1);
        } else if (value > 5) {
            $(this).val(5);
        }
    });
});
</script>
@endpush