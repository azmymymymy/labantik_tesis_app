@extends('layouts.app')

@section('title', 'Data Konsentrasi')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Data Konsentrasi
                        <a href="{{ route('konsentrasi.create') }}" class="btn btn-success btn-sm float-end">Tambah
                            Konsentrasi</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Konsentrasi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($konsentrasi as $kons)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kons->name }}</td>
                                        <td>
                                            <a href="{{ route('konsentrasi.edit', $kons->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data konsentrasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
