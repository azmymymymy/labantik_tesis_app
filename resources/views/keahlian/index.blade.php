@extends('layouts.app')

@section('title', 'Data Keahlian')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Data Keahlian
                    <a href="{{ route('keahlian.create') }}" class="btn btn-success btn-sm float-end">Tambah Keahlian</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Keahlian</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($keahlian as $khl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $khl->name }}</td>
                                    <td>
                                        <a href="{{ route('keahlian.edit', $khl->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data keahlian.</td>
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
