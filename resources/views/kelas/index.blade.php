@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Data Kelas
                        <a href="{{ route('kelas.create') }}" class="btn btn-success btn-sm float-end">Tambah Kelas</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas as $kls)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kls->name }}</td>
                                        <td>
                                            <a href="{{ route('kelas.edit', $kls->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data kelas.</td>
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
