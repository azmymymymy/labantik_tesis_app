@extends('layouts.app')

@section('title', 'Edit Angket Minat')

@section('content')
    <div class="dashboard-default-sec">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Edit Data Angket Minat</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('observasi.index') }}">Data Observasi</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('observasi.update', $observasi->id) }}" method="POST" class="mt-4">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Siswa Selection -->
                                
                            <!-- Pertanyaan Values -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Nilai Pertanyaan (1-5)</h5>
                                </div>
                                
                                @for ($i = 1; $i <= 7; $i++)
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="pertanyaan_{{ $i }}" class="form-label">Pertanyaan {{ $i }} <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('pertanyaan_' . $i) is-invalid @enderror" 
                                                   id="pertanyaan_{{ $i }}" 
                                                   name="pertanyaan_{{ $i }}" 
                                                   min="1" 
                                                   max="5" 
                                                   value="{{ old('pertanyaan_' . $i, $observasi->{'pertanyaan_' . $i}) }}" 
                                                   required>
                                            @error('pertanyaan_' . $i)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Current Total Display -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>Total Nilai Saat Ini:</strong> {{ $observasi->total }}
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('observasi.index') }}" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Update</button>
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
        
        // Calculate and display total in real-time
        calculateTotal();
    });
    
    function calculateTotal() {
        var total = 0;
        $('input[name^="pertanyaan_"]').each(function() {
            var value = parseInt($(this).val()) || 0;
            total += value;
        });
        
        // Update total display if exists
        if ($('.total-display').length) {
            $('.total-display').text(total);
        }
    }
});
</script>
@endpush