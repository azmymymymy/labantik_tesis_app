@extends('layouts.app')

@section('title', 'Hasil Belajar')
@push('styles')
    <style>
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-default-sec">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Kolom kiri -->
                            <div>
                                <h4 class="card-title mb-1">AHP</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item active" aria-current="page">Data Penelitian</li>
                                    </ol>
                                </nav>
                            </div>

                            <!-- Select pertama -->
                            <div class="d-flex align-items-center">
                                <select id="jenisSelect" class="custom-select form-control mb-2 me-2" style="width:auto;">
                                    <option value="">Pilih Jenis</option>
                                    <option value="kelompok">Kelompok</option>
                                    <option value="individu">Individu</option>
                                </select>

                                <form method="GET" action="">
                                    <select name="kelas_id" class="custom-select form-control mb-2" style="width:auto;"
                                        onchange="this.form.submit()">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ request('id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>

                            </div> '/ahp/{id}'

                            <!-- Kolom kanan -->
                            <div>
                                <span class="badge badge-primary">{{ date('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if (request()->has('kelas_id'))
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Langkah AHP - Data Awal & Matriks Perbandingan</h4>
                            <div class="mb-3">
                                <p>Kelompok: <span class="badge bg-info">70</span></p>
                                <p>Data:</p>
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C1 </span>= Minat : 54 / 70 × 100% =
                                        77
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C2 </span>= Motivasi : 38 / 40 = 95
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C3 </span>= Observasi : 22 / 35 = 63
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">Pretest</span> : 76
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">Posttest</span> : 81
                                    </li>
                                </ul>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>C1/C1</td>
                                            <td>C1/C2</td>
                                            <td>C1/C3</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>C2/C1</td>
                                            <td>C2/C2</td>
                                            <td>C2/C3</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>C3/C1</td>
                                            <td>C3/C2</td>
                                            <td>C3/C3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>54/54</td>
                                            <td>54/38</td>
                                            <td>54/22</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>38/54</td>
                                            <td>38/38</td>
                                            <td>38/22</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>22/54</td>
                                            <td>22/38</td>
                                            <td>22/22</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>1</td>
                                            <td>1,4211</td>
                                            <td>2,4545</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>0,7074</td>
                                            <td>1</td>
                                            <td>1,7273</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>0,4074</td>
                                            <td>0,5789</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Normalisasi untuk Mendapat Bobot</h4>
                            <div class="mb-3 mt-4">
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C1 </span>= Minat : 54 / 70 × 100% =
                                        77
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C2 </span>= Motivasi : 38 / 40 = 95
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">C3 </span>= Observasi : 22 / 35 = 63
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">Pretest</span> : 76
                                    </li>
                                    <li style="margin-bottom: 5px;">
                                        <span style="display:inline-block; width:80px;">Posttest</span> : 81
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Bobot (Weight)</h4>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>1 / 2,1148</td>
                                            <td>1,4211 / 3</td>
                                            <td>2,4545 / 5,1818</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>0,7074 / 2,1148</td>
                                            <td>1 / 3</td>
                                            <td>1,7273 / 5,1818</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>0,4074 / 2,1148</td>
                                            <td>0,5789 / 3</td>
                                            <td>1 / 5,1818</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="table">
                                        <tr>
                                            <th></th>
                                            <th>Minat</th>
                                            <th>Motivasi</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Minat</th>
                                            <td>0,4728</td>
                                            <td>0,4737</td>
                                            <td>0,4737</td>
                                        </tr>
                                        <tr>
                                            <th>Motivasi</th>
                                            <td>0,3345</td>
                                            <td>0,3333</td>
                                            <td>0,3333</td>
                                        </tr>
                                        <tr>
                                            <th>Observasi</th>
                                            <td>0,1926</td>
                                            <td>0,1930</td>
                                            <td>0,1930</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-4 mt-4">
                                <ul style="list-style: none; padding: 0;">
                                    <li style="margin-bottom: 5px">
                                        <span style="display:inline-block; width:80px;"><strong>Minat</strong></span> : (
                                        0,4728
                                        +
                                        0,4737 +
                                        0,4737 ) / 3 = 0,4734 = 47%
                                    </li>
                                    <li style="margin-bottom: 5px">
                                        <span style="display:inline-block; width:80px;"><strong>Motivasi</strong></span> :
                                        ( 0,3345 + 0,3333 +
                                        0,3333 ) / 3 = 0,3337 = 33%
                                    <li style="margin-bottom: 5px">
                                        <span style="display:inline-block; width:80px;"><strong>Observasi</strong></span> :
                                        ( 0,1926 + 0,1930 +
                                        0,1930 ) / 3 = 0,1930 = 19%
                                    </li>
                                </ul>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle"></i> Minat lebih berpengaruh terhadap hasil belajar sebanyak
                                <strong>47%</strong>.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
