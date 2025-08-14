@extends('layouts.app')

@section('title', 'Daftar Pertanyaan Angket Minat')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Daftar Pertanyaan</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertanyaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>1</td><td>Minat terhadap Materi Percabangan</td></tr>
                                <tr><td>2</td><td>Minat terhadap Program Keahlian yang Dipilih</td></tr>
                                <tr><td>3</td><td>Motivasi Belajar</td></tr>
                                <tr><td>4</td><td>Interaksi dengan Media Scratch</td></tr>
                                <tr><td>5</td><td>Pemahaman Konsep Percabangan</td></tr>
                                <tr><td>6</td><td>Partisipasi Aktif dalam Kegiatan Kelas</td></tr>
                                <tr><td>7</td><td>Hasil Proyek Scratch</td></tr>
                         </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
